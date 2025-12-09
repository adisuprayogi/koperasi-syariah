<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanPembiayaan;
use App\Models\JenisPembiayaan;
use App\Models\Anggota;
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
    public function index()
    {
        $user = auth()->user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

        $pengajuans = PengajuanPembiayaan::with(['jenisPembiayaan'])
            ->where('anggota_id', $anggota->id)
            ->orderBy('created_at', 'desc')
            ->get();

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
        $request->validate([
            'jenis_pembiayaan_id' => 'required|exists:jenis_pembiayaans,id',
            'jumlah_pengajuan' => 'required|numeric|min:1000000',
            'tenor' => 'required|integer|min:1|max:60',
            'tujuan_pembiayaan' => 'required|in:modal_kerja,investasi,konsumtif,pendidikan,renovasi,lainnya',
            'deskripsi' => 'required|string|min:20',
            'no_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:100',
            'ktp_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kk_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'slip_gaji_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'proposal_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'jaminan_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'jumlah_pengajuan.min' => 'Minimal pengajuan Rp 1.000.000',
            'tenor.max' => 'Maksimal tenor 60 bulan',
            'deskripsi.min' => 'Deskripsi minimal 20 karakter',
            'ktp_file.required' => 'KTP wajib diupload',
            'ktp_file.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

            // Get jenis pembiayaan untuk margin
            $jenisPembiayaan = JenisPembiayaan::findOrFail($request->jenis_pembiayaan_id);

            // Calculate margin and angsuran
            $marginPercent = $jenisPembiayaan->nisbah_mushoni ?? 10; // Default 10%
            $jumlahMargin = $request->jumlah_pengajuan * ($marginPercent / 100);
            $totalPembiayaan = $request->jumlah_pengajuan + $jumlahMargin;
            $angsuranPokok = $request->jumlah_pengajuan / $request->tenor;
            $angsuranMargin = $jumlahMargin / $request->tenor;
            $totalAngsuran = $angsuranPokok + $angsuranMargin;

            // Generate kode pengajuan
            $kodePengajuan = PengajuanPembiayaan::generateKodePengajuan();

            $data = $request->all();
            $data['kode_pengajuan'] = $kodePengajuan;
            $data['anggota_id'] = $anggota->id;
            $data['margin_percent'] = $marginPercent;
            $data['jumlah_margin'] = $jumlahMargin;
            $data['angsuran_pokok'] = $angsuranPokok;
            $data['angsuran_margin'] = $angsuranMargin;
            $data['total_angsuran'] = $totalAngsuran;
            $data['status'] = 'diajukan';
            $data['tanggal_jatuh_tempo'] = now()->addMonths($request->tenor);

            // Handle file uploads
            $files = ['ktp_file', 'kk_file', 'slip_gaji_file', 'proposal_file', 'jaminan_file'];
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    $path = $request->file($file)->store('dokumen/pengajuan/' . date('Y/m'), 'public');
                    $data[$file] = $path;
                }
            }

            $pengajuan = PengajuanPembiayaan::create($data);

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
        $request->validate([
            'jenis_pembiayaan_id' => 'required|exists:jenis_pembiayaans,id',
            'jumlah_pengajuan' => 'required|numeric|min:1000000',
            'tenor' => 'required|integer|min:1|max:60',
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
            $marginPercent = $jenisPembiayaan->nisbah_mushoni ?? 10;
            $jumlahMargin = $request->jumlah_pengajuan * ($marginPercent / 100);
            $totalPembiayaan = $request->jumlah_pengajuan + $jumlahMargin;
            $angsuranPokok = $request->jumlah_pengajuan / $request->tenor;
            $angsuranMargin = $jumlahMargin / $request->tenor;
            $totalAngsuran = $angsuranPokok + $angsuranMargin;

            $data = $request->all();
            $data['margin_percent'] = $marginPercent;
            $data['jumlah_margin'] = $jumlahMargin;
            $data['angsuran_pokok'] = $angsuranPokok;
            $data['angsuran_margin'] = $angsuranMargin;
            $data['total_angsuran'] = $totalAngsuran;
            $data['status'] = 'diajukan'; // Reset status to diajukan
            $data['tanggal_jatuh_tempo'] = now()->addMonths($request->tenor);

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
