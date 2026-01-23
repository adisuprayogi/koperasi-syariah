<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembiayaan;
use App\Models\JenisPembiayaan;
use App\Models\Anggota;
use App\Notifications\PengajuanStatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PengajuanPembiayaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

        $query = PengajuanPembiayaan::with(['jenisPembiayaan'])
            ->where('anggota_id', $anggota->id);

        // Filter by status
        if ($request->has('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->whereIn('status', ['diajukan', 'verifikasi']);
                    break;
                case 'approved':
                    $query->whereIn('status', ['approved', 'cair']);
                    break;
                case 'rejected':
                    $query->where('status', 'rejected');
                    break;
            }
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->get();

        return view('anggota.pengajuan.index', compact('pengajuans', 'anggota'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

        // Check if anggota is blacklisted
        if ($anggota->is_blacklisted) {
            return redirect()->route('anggota.pengajuan.index')
                ->with('error', 'Maaf, Anda saat ini di-blacklist. Hubungi pengurus untuk informasi lebih lanjut. Alasan: ' . $anggota->blacklist_reason);
        }

        // Check if there's pending application
        $pendingCount = PengajuanPembiayaan::where('anggota_id', $anggota->id)
            ->whereIn('status', ['draft', 'diajukan', 'verifikasi'])
            ->count();

        if ($pendingCount > 0) {
            return redirect()->route('anggota.pengajuan.index')
                ->with('error', 'Anda masih memiliki pengajuan yang pending. Silakan selesaikan terlebih dahulu.');
        }

        $jenisPembiayaan = JenisPembiayaan::where('status', 1)
            ->orderBy('nama_pembiayaan')
            ->get();

        $tujuanOptions = [
            'modal_kerja' => 'Modal Kerja',
            'investasi' => 'Investasi',
            'konsumtif' => 'Konsumtif',
            'pendidikan' => 'Pendidikan',
            'renovasi' => 'Renovasi',
            'lainnya' => 'Lainnya'
        ];

        return view('anggota.pengajuan.create', compact('jenisPembiayaan', 'tujuanOptions', 'anggota'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get jenis pembiayaan untuk validasi dinamis
        $jenisPembiayaan = JenisPembiayaan::findOrFail($request->jenis_pembiayaan_id);

        // Capture uploaded file names before validation (for display after validation error)
        $uploadedFileNames = [];
        $fileFields = ['ktp_file', 'kk_file', 'slip_gaji_file', 'proposal_file'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $uploadedFileNames[$field] = $request->file($field)->getClientOriginalName();
            }
        }
        if ($request->hasFile('jaminan_files')) {
            $uploadedFileNames['jaminan_files'] = [];
            foreach ($request->file('jaminan_files') as $file) {
                $uploadedFileNames['jaminan_files'][] = $file->getClientOriginalName();
            }
        }
        if ($request->hasFile('dokumen_lainnya_files')) {
            $uploadedFileNames['dokumen_lainnya_files'] = [];
            foreach ($request->file('dokumen_lainnya_files') as $file) {
                $uploadedFileNames['dokumen_lainnya_files'][] = $file->getClientOriginalName();
            }
        }

        $validator = \Validator::make($request->all(), [
            'jenis_pembiayaan_id' => 'required|exists:jenis_pembiayaans,id',
            'jumlah_pengajuan' => 'required|numeric|min:' . $jenisPembiayaan->minimal_pembiayaan . ($jenisPembiayaan->maksimal_pembiayaan ? '|max:' . $jenisPembiayaan->maksimal_pembiayaan : ''),
            'tenor' => 'required|integer|min:' . $jenisPembiayaan->jangka_waktu_min . '|max:' . $jenisPembiayaan->jangka_waktu_max,
            'tipe_angsuran' => 'required|in:flat,menurun,menaik',
            'tujuan_pembiayaan' => 'required|in:modal_kerja,investasi,konsumtif,pendidikan,renovasi,lainnya',
            'deskripsi' => 'required|string|min:20',
            'no_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:100',
            'ktp_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kk_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'slip_gaji_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'proposal_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'jaminan_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'jaminan_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_lainnya_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ], [
            'jumlah_pengajuan.min' => 'Minimal pengajuan Rp ' . number_format($jenisPembiayaan->minimal_pembiayaan, 0, ',', '.'),
            'jumlah_pengajuan.max' => 'Maksimal pengajuan Rp ' . number_format($jenisPembiayaan->maksimal_pembiayaan, 0, ',', '.'),
            'tenor.min' => 'Minimal tenor ' . $jenisPembiayaan->jangka_waktu_min . ' bulan',
            'tenor.max' => 'Maksimal tenor ' . $jenisPembiayaan->jangka_waktu_max . ' bulan',
            'deskripsi.min' => 'Deskripsi minimal 20 karakter',
            'ktp_file.required' => 'KTP wajib diupload',
            'ktp_file.max' => 'Ukuran file maksimal 2MB',
            'jaminan_files.*.max' => 'Ukuran file jaminan maksimal 2MB',
            'dokumen_lainnya_files.*.max' => 'Ukuran file dokumen lainnya maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('uploadedFileNames', $uploadedFileNames);
        }

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

            // Get jenis pembiayaan untuk margin
            $jenisPembiayaan = JenisPembiayaan::findOrFail($request->jenis_pembiayaan_id);

            // Calculate margin and angsuran
            $marginPercent = $jenisPembiayaan->margin; // Use margin from jenis pembiayaan
            // Rumus BARU: Margin per bulan dikalikan tenor
            $marginPerBulan = $request->jumlah_pengajuan * ($marginPercent / 100);
            $jumlahMargin = $marginPerBulan * (int)$request->tenor;
            $totalPembiayaan = $request->jumlah_pengajuan + $jumlahMargin;
            $angsuranPokok = $request->jumlah_pengajuan / (int)$request->tenor;
            $angsuranMargin = $marginPerBulan;
            $totalAngsuran = $angsuranPokok + $angsuranMargin;

            // Generate kode pengajuan dengan parameter jenis pembiayaan
            $kodePengajuan = PengajuanPembiayaan::generateKodePengajuan($request->jenis_pembiayaan_id);

            $data = $request->all();
            $data['kode_pengajuan'] = $kodePengajuan;
            $data['anggota_id'] = $anggota->id;
            $data['margin_percent'] = $marginPercent;
            $data['jumlah_margin'] = $jumlahMargin;
            $data['angsuran_pokok'] = $angsuranPokok;
            $data['angsuran_margin'] = $angsuranMargin;
            $data['total_angsuran'] = $totalAngsuran;
            $data['status'] = 'diajukan';
            $data['tanggal_jatuh_tempo'] = now()->addMonths((int)$request->tenor);

            // Handle single file uploads
            $files = ['ktp_file', 'kk_file', 'slip_gaji_file', 'proposal_file', 'jaminan_file'];
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    $path = $request->file($file)->store('dokumen/pengajuan/' . date('Y/m'), 'public');
                    $data[$file] = $path;
                }
            }

            // Handle multiple jaminan files
            if ($request->hasFile('jaminan_files')) {
                $jaminanFiles = $request->file('jaminan_files');
                foreach ($jaminanFiles as $index => $file) {
                    if ($index < 3) { // Store max 3 additional jaminan files
                        $path = $file->store('dokumen/pengajuan/' . date('Y/m'), 'public');
                        $data["jaminan_file_" . ($index + 2)] = $path; // jaminan_file_2, jaminan_file_3
                    }
                }
            }

            // Handle multiple dokumen lainnya files
            if ($request->hasFile('dokumen_lainnya_files')) {
                $dokumenLainnyaFiles = $request->file('dokumen_lainnya_files');
                foreach ($dokumenLainnyaFiles as $index => $file) {
                    if ($index < 5) { // Store max 5 dokumen lainnya files
                        $path = $file->store('dokumen/pengajuan/' . date('Y/m'), 'public');
                        $data["dokumen_lainnya_" . ($index + 1)] = $path; // dokumen_lainnya_1, ..., dokumen_lainnya_5
                    }
                }
            }

            $pengajuan = PengajuanPembiayaan::create($data);

            // Send notification to anggota about application submission
            try {
                if ($anggota->user) {
                    $anggota->user->notify(new PengajuanStatusNotification($pengajuan, 'diajukan'));
                }
            } catch (\Exception $e) {
                // Log error but don't fail the application submission
                \Log::error('Failed to send application notification: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('anggota.pengajuan.show', $pengajuan->id)
                ->with('success', 'Pengajuan pembiayaan berhasil diajukan dengan kode: ' . $kodePengajuan);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

        $pengajuan = PengajuanPembiayaan::with([
            'anggota',
            'jenisPembiayaan',
            'verifiedBy',
            'approvedBy',
            'disbursedBy'
        ])->where('anggota_id', $anggota->id)->findOrFail($id);

        return view('anggota.pengajuan.show', compact('pengajuan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

        $pengajuan = PengajuanPembiayaan::where('anggota_id', $anggota->id)
            ->whereIn('status', ['draft', 'rejected'])
            ->findOrFail($id);

        $jenisPembiayaan = JenisPembiayaan::where('status', 1)
            ->orderBy('nama_pembiayaan')
            ->get();

        $tujuanOptions = [
            'modal_kerja' => 'Modal Kerja',
            'investasi' => 'Investasi',
            'konsumtif' => 'Konsumtif',
            'pendidikan' => 'Pendidikan',
            'renovasi' => 'Renovasi',
            'lainnya' => 'Lainnya'
        ];

        return view('anggota.pengajuan.edit', compact('pengajuan', 'jenisPembiayaan', 'tujuanOptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get jenis pembiayaan untuk validasi dinamis
        $jenisPembiayaan = JenisPembiayaan::findOrFail($request->jenis_pembiayaan_id);

        $request->validate([
            'jenis_pembiayaan_id' => 'required|exists:jenis_pembiayaans,id',
            'jumlah_pengajuan' => 'required|numeric|min:' . $jenisPembiayaan->minimal_pembiayaan . ($jenisPembiayaan->maksimal_pembiayaan ? '|max:' . $jenisPembiayaan->maksimal_pembiayaan : ''),
            'tenor' => 'required|integer|min:' . $jenisPembiayaan->jangka_waktu_min . '|max:' . $jenisPembiayaan->jangka_waktu_max,
            'tujuan_pembiayaan' => 'required|in:modal_kerja,investasi,konsumtif,pendidikan,renovasi,lainnya',
            'deskripsi' => 'required|string|min:20',
            'no_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:100',
            'ktp_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kk_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'slip_gaji_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'proposal_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'jaminan_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

            $pengajuan = PengajuanPembiayaan::where('anggota_id', $anggota->id)
                ->whereIn('status', ['draft', 'rejected'])
                ->findOrFail($id);

            // Get jenis pembiayaan untuk margin
            $jenisPembiayaan = JenisPembiayaan::findOrFail($request->jenis_pembiayaan_id);

            // Calculate margin and angsuran
            $marginPercent = $jenisPembiayaan->margin ?? $jenisPembiayaan->nisbah_mushoni ?? 10;
            // Rumus BARU: Margin per bulan dikalikan tenor
            $marginPerBulan = $request->jumlah_pengajuan * ($marginPercent / 100);
            $jumlahMargin = $marginPerBulan * (int)$request->tenor;
            $totalPembiayaan = $request->jumlah_pengajuan + $jumlahMargin;
            $angsuranPokok = $request->jumlah_pengajuan / (int)$request->tenor;
            $angsuranMargin = $marginPerBulan;
            $totalAngsuran = $angsuranPokok + $angsuranMargin;

            $data = $request->all();
            $data['margin_percent'] = $marginPercent;
            $data['jumlah_margin'] = $jumlahMargin;
            $data['angsuran_pokok'] = $angsuranPokok;
            $data['angsuran_margin'] = $angsuranMargin;
            $data['total_angsuran'] = $totalAngsuran;
            $data['status'] = 'diajukan'; // Reset status to diajukan
            $data['tanggal_jatuh_tempo'] = now()->addMonths((int)$request->tenor);

            // Handle file uploads
            $files = ['ktp_file', 'kk_file', 'slip_gaji_file', 'proposal_file', 'jaminan_file'];
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    // Delete old file if exists
                    if ($pengajuan->$file) {
                        Storage::disk('public')->delete($pengajuan->$file);
                    }
                    $path = $request->file($file)->store('dokumen/pengajuan/' . date('Y/m'), 'public');
                    $data[$file] = $path;
                }
            }

            $pengajuan->update($data);

            DB::commit();

            return redirect()->route('anggota.pengajuan.show', $pengajuan->id)
                ->with('success', 'Pengajuan pembiayaan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = auth()->user();
            $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

            $pengajuan = PengajuanPembiayaan::where('anggota_id', $anggota->id)
                ->whereIn('status', ['draft', 'rejected'])
                ->findOrFail($id);

            // Delete files
            $files = ['ktp_file', 'kk_file', 'slip_gaji_file', 'proposal_file', 'jaminan_file'];
            foreach ($files as $file) {
                if ($pengajuan->$file) {
                    Storage::disk('public')->delete($pengajuan->$file);
                }
            }

            $pengajuan->delete();

            return redirect()->route('anggota.pengajuan.index')
                ->with('success', 'Pengajuan pembiayaan berhasil dihapus');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Submit draft pengajuan
     */
    public function submit($id)
    {
        try {
            $user = auth()->user();
            $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

            $pengajuan = PengajuanPembiayaan::where('anggota_id', $anggota->id)
                ->where('status', 'draft')
                ->findOrFail($id);

            // Validate required fields
            if (!$pengajuan->ktp_file) {
                return back()->with('error', 'KTP wajib diupload sebelum submit');
            }

            $pengajuan->update([
                'status' => 'diajukan',
                'submitted_at' => now()
            ]);

            return redirect()->route('anggota.pengajuan.show', $pengajuan->id)
                ->with('success', 'Pengajuan berhasil diajukan menunggu verifikasi');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
