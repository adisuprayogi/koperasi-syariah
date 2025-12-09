<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\User;
use App\Models\JenisSimpanan;
use App\Models\TransaksiSimpanan;
use App\Models\Pengurus;
use App\Models\PengajuanPembiayaan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PengurusController extends Controller
{
    /**
     * Check if current user can verify/approve (Ketua, Sekretaris, & Pengurus Lainnya)
     */
    private function canVerifyApprove()
    {
        $user = auth()->user();
        if (!$user || !$user->pengurus) {
            return false;
        }

        $posisi = $user->pengurus->posisi;
        return in_array($posisi, ['ketua', 'sekretaris', 'pengurus_lainnya']);
    }

    /**
     * Check if current user can disburse (Bendahara only)
     */
    private function canDisburse()
    {
        $user = auth()->user();
        if (!$user || !$user->pengurus) {
            return false;
        }

        return $user->pengurus->posisi === 'bendahara';
    }

    /**
     * Dashboard Pengurus
     */
    public function dashboard()
    {
        return view('pengurus.dashboard');
    }

    /**
     * Index Anggota
     */
    public function anggotaIndex()
    {
        $anggota = Anggota::with('user')->latest()->get();
        return view('pengurus.anggota.index', compact('anggota'));
    }

    /**
     * Create Anggota
     */
    public function anggotaCreate()
    {
        return view('pengurus.anggota.create');
    }

    /**
     * Store Anggota
     */
    public function anggotaStore(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:anggota,nik',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email',
            'alamat_lengkap' => 'required|string',
            'pekerjaan' => 'required|string|max:100',
            'penghasilan' => 'nullable|numeric|min:0',
            'no_npwp' => 'nullable|string|max:20',
            'jenis_anggota' => 'required|in:biasa,luar_biasa,kehormatan',
            'password' => 'required|string|min:8|confirmed',
            'create_user_account' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Generate nomor anggota
            $no_anggota = Anggota::generateNoAnggota();

            // Create user account with nomor anggota as username
            $user_id = null;
            if ($request->create_user_account || $request->has('create_user_account')) {
                $user = User::create([
                    'name' => $request->nama_lengkap,
                    'email' => $request->email,               // Email tetap disimpan tapi bukan username
                    'username' => $no_anggota,              // Nomor anggota sebagai username
                    'password' => Hash::make($request->password),
                    'role' => 'anggota',
                    'first_login' => true,
                ]);
                $user_id = $user->id;
            }

            // Create anggota
            Anggota::create([
                'no_anggota' => $no_anggota,
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'alamat_lengkap' => $request->alamat_lengkap,
                'pekerjaan' => $request->pekerjaan,
                'penghasilan' => $request->penghasilan,
                'no_npwp' => $request->no_npwp,
                'status_keanggotaan' => 'aktif',
                'tanggal_gabung' => now(),
                'jenis_anggota' => $request->jenis_anggota,
                'user_id' => $user_id,
            ]);

            DB::commit();

            $message = $user_id
                ? "Anggota {$no_anggota} berhasil ditambahkan. Akun user telah dibuat dengan email: {$request->email}"
                : "Anggota {$no_anggota} berhasil ditambahkan.";

            return redirect()->route('pengurus.anggota.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menambahkan anggota: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Edit Anggota
     */
    public function anggotaEdit($id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);

        // Check if user exists, if not redirect with error
        if (!$anggota->user) {
            return redirect()->route('pengurus.anggota.index')
                ->with('error', 'Data user untuk anggota ini tidak ditemukan. Silakan hubungi administrator.');
        }

        return view('pengurus.anggota.edit', compact('anggota'));
    }

    /**
     * Update Anggota
     */
    public function anggotaUpdate(Request $request, $id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);

        // Check if user exists, if not return error
        if (!$anggota->user) {
            return back()->with('error', 'Data user untuk anggota ini tidak ditemukan. Tidak dapat melakukan update.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:anggota,nik,' . $id,
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email,' . $anggota->user_id,
            'alamat_lengkap' => 'required|string',
            'pekerjaan' => 'required|string|max:100',
            'penghasilan' => 'nullable|numeric|min:0',
            'no_npwp' => 'nullable|string|max:20',
            'status_keanggotaan' => 'required|in:aktif,nonaktif,keluar,meninggal',
            'jenis_anggota' => 'required|in:biasa,luar_biasa,kehormatan',
        ]);

        try {
            DB::beginTransaction();

            // Update user account
            $anggota->user->update([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'required|string|min:8|confirmed',
                ]);
                $anggota->user->update([
                    'password' => Hash::make($request->password),
                    'first_login' => true, // Force password change on next login
                ]);
            }

            // Update anggota data
            $anggota->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'alamat_lengkap' => $request->alamat_lengkap,
                'pekerjaan' => $request->pekerjaan,
                'penghasilan' => $request->penghasilan,
                'no_npwp' => $request->no_npwp,
                'status_keanggotaan' => $request->status_keanggotaan,
                'jenis_anggota' => $request->jenis_anggota,
            ]);

            DB::commit();

            return redirect()->route('pengurus.anggota.index')
                ->with('success', 'Data anggota berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengupdate anggota: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Destroy Anggota
     */
    public function anggotaDestroy($id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);

        // Prevent deletion if the anggota is the current user
        if ($anggota->user_id == auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri');
        }

        try {
            DB::beginTransaction();

            // Soft delete anggota
            $anggota->delete();

            // Also soft delete the associated user account
            if ($anggota->user) {
                $anggota->user->delete();
            }

            DB::commit();

            return redirect()->route('pengurus.anggota.index')
                ->with('success', 'Anggota berhasil dihapus (soft delete). Data dapat dipulihkan kembali.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus anggota: ' . $e->getMessage());
        }
    }

    /**
     * Index Transaksi Simpanan
     */
    public function simpananIndex(Request $request)
    {
        $query = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'pengurus'])
                                ->latest();

        // Filter by date range
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        // Filter by jenis transaksi
        if ($request->has('jenis_transaksi') && $request->jenis_transaksi) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        // Filter by jenis simpanan
        if ($request->has('jenis_simpanan_id') && $request->jenis_simpanan_id) {
            $query->where('jenis_simpanan_id', $request->jenis_simpanan_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $transaksi = $query->paginate(20);
        $anggota = Anggota::orderBy('nama_lengkap')->get();
        $jenisSimpanan = JenisSimpanan::where('status', 1)->get();

        return view('pengurus.simpanan.index', compact(
            'transaksi',
            'anggota',
            'jenisSimpanan'
        ));
    }

    /**
     * Create Transaksi Simpanan
     */
    public function simpananCreate()
    {
        $anggota = Anggota::orderBy('nama_lengkap')->get();
        $jenisSimpanan = JenisSimpanan::where('status', 1)->get();

        return view('pengurus.simpanan.create', compact(
            'anggota',
            'jenisSimpanan'
        ));
    }

    /**
     * Store Transaksi Simpanan
     */
    public function simpananStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anggota_id' => 'required|exists:anggota,id',
            'jenis_simpanan_id' => 'required|exists:jenis_simpanan,id',
            'jenis_transaksi' => 'required|in:setor,tarik',
            'jumlah' => 'required|numeric|min:1000',
            'tanggal_transaksi' => 'required|date|before_or_equal:today',
            'keterangan' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Get jenis simpanan to check withdrawal rules
            $jenisSimpanan = JenisSimpanan::findOrFail($request->jenis_simpanan_id);

            // If it's a withdrawal, check if allowed
            if ($request->jenis_transaksi == 'tarik' && !$jenisSimpanan->bisa_ditarik) {
                return back()
                    ->with('error', 'Jenis simpanan ini tidak dapat ditarik')
                    ->withInput();
            }

            // Calculate saldo
            $saldo = TransaksiSimpanan::calculateSaldo(
                $request->anggota_id,
                $request->jenis_simpanan_id,
                $request->jenis_transaksi,
                $request->jumlah
            );

            // Check if withdrawal amount exceeds balance
            if ($request->jenis_transaksi == 'tarik' && $saldo['saldo_setelahnya'] < 0) {
                return back()
                    ->with('error', 'Saldo tidak mencukupi untuk penarikan')
                    ->withInput();
            }

            // Generate kode transaksi
            $kodeTransaksi = TransaksiSimpanan::generateKodeTransaksi($request->jenis_transaksi);

            // Get current pengurus
            $pengurusId = Auth::user()->role == 'admin'
                ? Pengurus::first()?->id
                : Pengurus::where('user_id', Auth::id())->first()?->id;

            // Create transaksi
            $transaksi = TransaksiSimpanan::create([
                'kode_transaksi' => $kodeTransaksi,
                'anggota_id' => $request->anggota_id,
                'jenis_simpanan_id' => $request->jenis_simpanan_id,
                'pengurus_id' => $pengurusId,
                'jenis_transaksi' => $request->jenis_transaksi,
                'jumlah' => $request->jumlah,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'keterangan' => $request->keterangan,
                'saldo_sebelumnya' => $saldo['saldo_sebelumnya'],
                'saldo_setelahnya' => $saldo['saldo_setelahnya'],
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => $pengurusId,
            ]);

            DB::commit();

            $message = $request->jenis_transaksi == 'setor'
                ? 'Setoran berhasil dicatat'
                : 'Penarikan berhasil dicatat';

            return redirect()
                ->route('pengurus.simpanan.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show Transaksi Simpanan
     */
    public function simpananShow($id)
    {
        $transaksi = TransaksiSimpanan::with([
            'anggota',
            'jenisSimpanan',
            'pengurus',
            'verifiedByPengurus'
        ])->findOrFail($id);

        // Get related transactions for this anggota and jenis simpanan
        $relatedTransaksi = TransaksiSimpanan::with(['pengurus'])
                                    ->where('anggota_id', $transaksi->anggota_id)
                                    ->where('jenis_simpanan_id', $transaksi->jenis_simpanan_id)
                                    ->where('id', '!=', $transaksi->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();

        return view('pengurus.simpanan.show', compact(
            'transaksi',
            'relatedTransaksi'
        ));
    }

    /**
     * Print Transaksi Simpanan
     */
    public function simpananPrint($id)
    {
        $transaksi = TransaksiSimpanan::with([
            'anggota',
            'jenisSimpanan',
            'pengurus',
            'verifiedByPengurus'
        ])->findOrFail($id);

        // Get related transactions for this anggota and jenis simpanan
        $relatedTransaksi = TransaksiSimpanan::with(['pengurus'])
                                    ->where('anggota_id', $transaksi->anggota_id)
                                    ->where('jenis_simpanan_id', $transaksi->jenis_simpanan_id)
                                    ->where('id', '!=', $transaksi->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();

        return view('pengurus.simpanan.print', compact(
            'transaksi',
            'relatedTransaksi'
        ));
    }

    /**
     * Index Pengajuan
     */
    public function pengajuanIndex()
    {
        $pengajuans = PengajuanPembiayaan::with(['anggota', 'jenisPembiayaan'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistics for sidebar
        $stats = [
            'total_diajukan' => PengajuanPembiayaan::whereIn('status', ['diajukan'])->count(),
            'total_verifikasi' => PengajuanPembiayaan::whereIn('status', ['verifikasi'])->count(),
            'total_approved' => PengajuanPembiayaan::whereIn('status', ['approved', 'cair'])->count(),
            'total_rejected' => PengajuanPembiayaan::whereIn('status', ['rejected'])->count(),
        ];

        return view('pengurus.pengajuan.index', compact('pengajuans', 'stats'));
    }

    /**
     * Show Pengajuan
     */
    public function pengajuanShow($id)
    {
        $pengajuan = PengajuanPembiayaan::with(['anggota', 'jenisPembiayaan', 'verifiedBy', 'approvedBy', 'disbursedBy'])
            ->findOrFail($id);

        return view('pengurus.pengajuan.show', compact('pengajuan'));
    }

    /**
     * Verifikasi Pengajuan
     */
    public function pengajuanVerifikasi(Request $request, $id)
    {
        // Check permission - only Ketua, Sekretaris, & Pengurus Lainnya can verify/approve
        if (!$this->canVerifyApprove()) {
            return redirect()->route('pengurus.pengajuan.index')
                ->with('error', 'Hanya Ketua, Sekretaris, dan Pengurus Lainnya yang berhak memverifikasi dan menyetujui pengajuan');
        }

        $pengajuan = PengajuanPembiayaan::findOrFail($id);

        // Validasi status
        if ($pengajuan->status !== 'diajukan') {
            return redirect()->route('pengurus.pengajuan.index')
                ->with('error', 'Pengajuan tidak dapat diverifikasi karena status tidak sesuai');
        }

        // Update status langsung ke approved (verifikasi + approval digabung)
        $pengajuan->update([
            'status' => 'approved',
            'tanggal_verifikasi' => now(),
            'verifikator_id' => auth()->user()->id,
            'tanggal_approve' => now(),
            'approver_id' => auth()->user()->id
        ]);

        return redirect()->route('pengurus.pengajuan.index')
            ->with('success', 'Pengajuan berhasil diverifikasi dan disetujui');
    }

    // Approve method digabung dengan verifikasi, tidak digunakan lagi

    /**
     * Reject Pengajuan
     */
    public function pengajuanReject(Request $request, $id)
    {
        // Check permission - only Ketua, Sekretaris, & Pengurus Lainnya can reject
        if (!$this->canVerifyApprove()) {
            return redirect()->route('pengurus.pengajuan.index')
                ->with('error', 'Hanya Ketua, Sekretaris, dan Pengurus Lainnya yang berhak menolak pengajuan');
        }

        $pengajuan = PengajuanPembiayaan::findOrFail($id);

        // Validasi status
        if (!in_array($pengajuan->status, ['diajukan', 'verifikasi'])) {
            return redirect()->route('pengurus.pengajuan.index')
                ->with('error', 'Pengajuan tidak dapat ditolak karena status tidak sesuai');
        }

        // Update status
        $pengajuan->update([
            'status' => 'rejected',
            'tanggal_reject' => now(),
            'rejecter_id' => auth()->user()->id,
            'alasan_reject' => $request->alasan_reject ?? 'Pengajuan tidak memenuhi kriteria'
        ]);

        return redirect()->route('pengurus.pengajuan.index')
            ->with('success', 'Pengajuan berhasil ditolak');
    }

    /**
     * Cairkan Pengajuan
     */
    public function pengajuanCairkan(Request $request, $id)
    {
        // Check permission - only Bendahara can disburse
        if (!$this->canDisburse()) {
            return redirect()->route('pengurus.pengajuan.index')
                ->with('error', 'Hanya Bendahara yang berhak mencairkan dana pembiayaan');
        }

        $pengajuan = PengajuanPembiayaan::findOrFail($id);

        // Validasi status
        if ($pengajuan->status !== 'approved') {
            return redirect()->route('pengurus.pengajuan.index')
                ->with('error', 'Pengajuan harus disetujui terlebih dahulu sebelum dapat dicairkan');
        }

        // Validasi request
        $request->validate([
            'bukti_pencairan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tanggal_jatuh_tempo_pertama' => 'required|date|after_or_equal:today'
        ], [
            'bukti_pencairan.required' => 'Bukti pencairan wajib diupload',
            'bukti_pencairan.mimes' => 'Format file harus PDF, JPG, atau PNG',
            'bukti_pencairan.max' => 'Ukuran file maksimal 2MB',
            'tanggal_jatuh_tempo_pertama.required' => 'Tanggal jatuh tempo pertama wajib diisi',
            'tanggal_jatuh_tempo_pertama.after_or_equal' => 'Tanggal jatuh tempo tidak boleh kurang dari hari ini'
        ]);

        // Upload bukti pencairan
        if ($request->hasFile('bukti_pencairan')) {
            $file = $request->file('bukti_pencairan');
            $fileName = 'bukti_pencairan_' . $pengajuan->kode_pengajuan . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('bukti_pencairan', $fileName, 'public');

            // Update status dengan bukti dan jatuh tempo
            $pengajuan->update([
                'status' => 'cair',
                'tanggal_cair' => now(),
                'pencair_id' => auth()->user()->id,
                'bukti_pencairan' => $filePath,
                'bukti_pencairan_original' => $file->getClientOriginalName(),
                'tanggal_jatuh_tempo_pertama' => $request->tanggal_jatuh_tempo_pertama ?? now()->addMonth(),
                'keterangan_jatuh_tempo' => $request->keterangan_jatuh_tempo ?? 'Angsuran pertama jatuh tempo'
            ]);
        }

        return redirect()->route('pengurus.pengajuan.index')
            ->with('success', 'Pembiayaan berhasil dicairkan dan bukti telah diupload');
    }

    /**
     * Index Pembiayaan
     */
    public function pembiayaanIndex()
    {
        return view('pengurus.pembiayaan.index');
    }

    /**
     * Show Pembiayaan
     */
    public function pembiayaanShow($id)
    {
        return view('pengurus.pembiayaan.show', compact('id'));
    }

    /**
     * Bayar Pembiayaan
     */
    public function pembiayaanBayar(Request $request, $id)
    {
        // TODO: Implement pembayaran logic
        return redirect()->route('pengurus.pembiayaan.index')->with('success', 'Pembayaran berhasil dicatat');
    }

    /**
     * Index Laporan
     */
    public function laporanIndex()
    {
        return view('pengurus.laporan.index');
    }

    /**
     * Laporan Simpanan
     */
    public function laporanSimpanan(Request $request)
    {
        return view('pengurus.laporan.simpanan');
    }

    /**
     * Laporan Pembiayaan
     */
    public function laporanPembiayaan(Request $request)
    {
        return view('pengurus.laporan.pembiayaan');
    }

    /**
     * Get Saldo Anggota for API
     */
    public function getSaldo(Request $request)
    {
        if (!$request->has('anggota_id') || !$request->has('jenis_simpanan_id')) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        try {
            $lastTransaksi = TransaksiSimpanan::where('anggota_id', $request->anggota_id)
                                        ->where('jenis_simpanan_id', $request->jenis_simpanan_id)
                                        ->where('status', 'verified')
                                        ->orderBy('created_at', 'desc')
                                        ->first();

            $saldo = $lastTransaksi ? $lastTransaksi->saldo_setelahnya : 0;

            return response()->json([
                'saldo' => $saldo,
                'saldo_formatted' => 'Rp ' . number_format($saldo, 0, ',', '.')
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
