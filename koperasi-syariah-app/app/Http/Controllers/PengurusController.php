<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\User;
use App\Models\JenisSimpanan;
use App\Models\TransaksiSimpanan;
use App\Models\Pengurus;
use App\Models\PengajuanPembiayaan;
use App\Models\Angsuran;
use App\Models\JenisPembiayaan;
use App\Models\Transaksi;
use App\Notifications\AnggotaBaruNotification;
use App\Notifications\PengajuanStatusNotification;
use App\Notifications\SimpananNotification;
use App\Notifications\AngsuranNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
        $user = auth()->user();
        $pengurus = Pengurus::where('user_id', $user->id)->first();

        // Statistik Simpanan
        $totalSimpanan = TransaksiSimpanan::where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalPenarikan = TransaksiSimpanan::where('jenis_transaksi', 'tarik')->sum('jumlah');
        $totalSaldo = $totalSimpanan - $totalPenarikan;

        // Transaksi hari ini
        $transaksiHariIni = TransaksiSimpanan::whereDate('tanggal_transaksi', today())->count();
        $setoranHariIni = TransaksiSimpanan::whereDate('tanggal_transaksi', today())
                                           ->where('jenis_transaksi', 'setor')
                                           ->sum('jumlah');
        $penarikanHariIni = TransaksiSimpanan::whereDate('tanggal_transaksi', today())
                                             ->where('jenis_transaksi', 'tarik')
                                             ->sum('jumlah');

        // Statistik Pembiayaan
        $totalPembiayaan = PengajuanPembiayaan::whereIn('status', ['approved', 'cair', 'lunas'])
                                              ->sum('jumlah_pengajuan');
        $totalPembiayaanCair = PengajuanPembiayaan::where('status', 'cair')
                                                  ->sum('jumlah_pengajuan');
        $activePembiayaan = PengajuanPembiayaan::where('status', 'cair')->count();
        $totalMargin = PengajuanPembiayaan::whereIn('status', ['cair', 'lunas'])
                                           ->sum('jumlah_margin');

        // Pending Tasks berdasarkan posisi
        $pendingTasks = collect();
        if ($this->canVerifyApprove()) {
            // Ketua, Sekretaris, Pengurus Lainnya melihat pengajuan yang perlu verifikasi
            $pengajuanPending = PengajuanPembiayaan::whereIn('status', ['diajukan', 'verifikasi'])->count();
            $pendingTasks->push((object) [
                'task' => 'Pengajuan Menunggu Verifikasi',
                'count' => $pengajuanPending,
                'url' => route('pengurus.pengajuan.index'),
                'priority' => $pengajuanPending > 5 ? 'high' : 'normal'
            ]);
        }

        if ($this->canDisburse()) {
            // Bendahara melihat pengajuan yang perlu dicairkan
            $pengajuanApproved = PengajuanPembiayaan::where('status', 'approved')->count();
            $pendingTasks->push((object) [
                'task' => 'Pengajuan Menunggu Pencairan',
                'count' => $pengajuanApproved,
                'url' => route('pengurus.pengajuan.index'),
                'priority' => $pengajuanApproved > 3 ? 'high' : 'normal'
            ]);
        }

        // Angsuran yang jatuh tempo bulan ini
        $angsuranJatuhTempo = Angsuran::whereMonth('tanggal_jatuh_tempo', now()->month)
                                     ->whereYear('tanggal_jatuh_tempo', now()->year)
                                     ->where('status', '!=', 'terbayar')
                                     ->count();

        if ($angsuranJatuhTempo > 0) {
            $pendingTasks->push((object) [
                'task' => 'Angsuran Jatuh Tempo Bulan Ini',
                'count' => $angsuranJatuhTempo,
                'url' => route('pengurus.pembiayaan.index'),
                'priority' => $angsuranJatuhTempo > 10 ? 'high' : 'normal'
            ]);
        }

        // Recent Activities
        $recentTransaksi = TransaksiSimpanan::with('anggota')
                                          ->latest('tanggal_transaksi')
                                          ->limit(5)
                                          ->get();

        $recentPengajuan = PengajuanPembiayaan::with('anggota')
                                             ->latest()
                                             ->limit(5)
                                             ->get();

        // Monthly Summary (6 bulan terakhir)
        $monthlySummary = TransaksiSimpanan::select(
                DB::raw('YEAR(tanggal_transaksi) as year'),
                DB::raw('MONTH(tanggal_transaksi) as month'),
                DB::raw('SUM(CASE WHEN jenis_transaksi = "setor" THEN jumlah ELSE 0 END) as total_setor'),
                DB::raw('SUM(CASE WHEN jenis_transaksi = "tarik" THEN jumlah ELSE 0 END) as total_tarik')
            )
            ->where('tanggal_transaksi', '>=', now()->subMonths(6))
            ->groupBy(DB::raw('YEAR(tanggal_transaksi)'), DB::raw('MONTH(tanggal_transaksi)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return view('pengurus.dashboard', compact(
            'pengurus',
            'totalSimpanan',
            'totalPenarikan',
            'totalSaldo',
            'transaksiHariIni',
            'setoranHariIni',
            'penarikanHariIni',
            'totalPembiayaan',
            'totalPembiayaanCair',
            'activePembiayaan',
            'totalMargin',
            'pendingTasks',
            'recentTransaksi',
            'recentPengajuan',
            'monthlySummary'
        ));
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
            $anggota = Anggota::create([
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

            // Send welcome email notification if user account was created
            if ($user_id && $user) {
                try {
                    $user->notify(new AnggotaBaruNotification($anggota, $request->password));
                } catch (\Exception $e) {
                    // Log error but don't fail the registration
                    \Log::error('Failed to send welcome email: ' . $e->getMessage());
                }
            }

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

            // Send notification to anggota about the transaction
            try {
                $anggota = Anggota::find($request->anggota_id);
                if ($anggota && $anggota->user) {
                    $anggota->user->notify(new SimpananNotification($transaksi, $request->jenis_transaksi));
                }
            } catch (\Exception $e) {
                // Log error but don't fail the transaction
                \Log::error('Failed to send simpanan notification: ' . $e->getMessage());
            }

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

        // Hitung ulang margin berdasarkan jenis pembiayaan
        $marginPercent = $pengajuan->jenisPembiayaan->margin;
        $jumlahMargin = $pengajuan->jumlah_pengajuan * ($marginPercent / 100);
        $totalPembiayaan = $pengajuan->jumlah_pengajuan + $jumlahMargin;

        // Hitung angsuran pokok dan margin per bulan
        $angsuranPokok = $pengajuan->jumlah_pengajuan / $pengajuan->tenor;
        $angsuranMargin = $jumlahMargin / $pengajuan->tenor;
        $totalAngsuran = $angsuranPokok + $angsuranMargin;

        // Update status langsung ke approved dengan perhitungan margin yang benar
        $pengajuan->update([
            'status' => 'approved',
            'tanggal_verifikasi' => now(),
            'verifikator_id' => auth()->user()->id,
            'tanggal_approve' => now(),
            'approver_id' => auth()->user()->id,
            'margin_percent' => $marginPercent,
            'jumlah_margin' => $jumlahMargin,
            'total_pembiayaan' => $totalPembiayaan,
            'angsuran_pokok' => $angsuranPokok,
            'angsuran_margin' => $angsuranMargin,
            'total_angsuran' => $totalAngsuran
        ]);

        // Send notification to anggota about approval
        try {
            $anggota = Anggota::find($pengajuan->anggota_id);
            if ($anggota && $anggota->user) {
                $anggota->user->notify(new PengajuanStatusNotification($pengajuan, 'approved'));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the approval
            \Log::error('Failed to send approval notification: ' . $e->getMessage());
        }

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

        // Send notification to anggota about rejection
        try {
            $anggota = Anggota::find($pengajuan->anggota_id);
            if ($anggota && $anggota->user) {
                $anggota->user->notify(new PengajuanStatusNotification($pengajuan, 'rejected'));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the rejection
            \Log::error('Failed to send rejection notification: ' . $e->getMessage());
        }

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

            // Send notification to anggota about disbursement
            try {
                $anggota = Anggota::find($pengajuan->anggota_id);
                if ($anggota && $anggota->user) {
                    $anggota->user->notify(new PengajuanStatusNotification($pengajuan, 'cair'));
                }
            } catch (\Exception $e) {
                // Log error but don't fail the disbursement
                \Log::error('Failed to send disbursement notification: ' . $e->getMessage());
            }
        }

        return redirect()->route('pengurus.pengajuan.index')
            ->with('success', 'Pembiayaan berhasil dicairkan dan bukti telah diupload');
    }


    /**
     * Display a listing of active pembiayaan for management
     */
    public function pembiayaanIndex(Request $request)
    {
        $pembiayaans = PengajuanPembiayaan::with(['anggota', 'angsurans', 'jenisPembiayaan'])
            ->whereIn('status', ['cair', 'lunas'])
            ->when($request->search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('kode_pengajuan', 'like', "%{$search}%")
                      ->orWhereHas('anggota', function($sq) use ($search) {
                          $sq->where('nama_lengkap', 'like', "%{$search}%")
                             ->orWhere('nomor_anggota', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->jenis_pembiayaan_id, function ($query, $jenisId) {
                $query->where('jenis_pembiayaan_id', $jenisId);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        $jenisPembiayaans = JenisPembiayaan::orderBy('nama_pembiayaan')->get();

        return view('pengurus.pembiayaan.index', compact('pembiayaans', 'jenisPembiayaans'));
    }

    /**
     * Display the specified pembiayaan with angsuran details
     */
    public function pembiayaanShow($id)
    {
        $pengajuan = PengajuanPembiayaan::with([
            'anggota',
            'jenisPembiayaan',
            'angsurans' => function($query) {
                $query->orderBy('angsuran_ke', 'asc');
            },
            'verifiedBy',
            'approvedBy',
            'disbursedBy',
            'pencair'
        ])->findOrFail($id);

        // Hitung statistik angsuran
        $totalAngsuran = $pengajuan->angsurans->count();
        $totalTerbayar = $pengajuan->angsurans->where('status', 'terbayar')->count();
        $totalPending = $pengajuan->angsurans->where('status', 'pending')->count();
        $totalTerlambat = $pengajuan->angsurans->where('status', 'terlambat')->count();

        $angsuranBerikutnya = $pengajuan->angsurans()
            ->whereIn('status', ['pending', 'terlambat'])
            ->orderBy('angsuran_ke', 'asc')
            ->first();

        return view('pengurus.pembiayaan.show', compact(
            'pengajuan',
            'totalAngsuran',
            'totalTerbayar',
            'totalPending',
            'totalTerlambat',
            'angsuranBerikutnya'
        ));
    }

    /**
     * Show form for recording installment payment
     */
    public function pembiayaanBayar($id, $angsuranId)
    {
        $pengajuan = PengajuanPembiayaan::with([
            'anggota',
            'jenisPembiayaan',
            'angsurans'
        ])->findOrFail($id);

        $angsuran = Angsuran::findOrFail($angsuranId);

        // Validate that this angsuran belongs to the pengajuan
        if ($angsuran->pengajuan_pembiayaan_id != $pengajuan->id) {
            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('error', 'Angsuran tidak ditemukan untuk pembiayaan ini');
        }

        // Only allow payment for pending or overdue installments
        if ($angsuran->status == 'terbayar') {
            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('error', 'Angsuran ini sudah terbayar');
        }

        // Syariah compliance: Calculate late days but no denda
        $hariTerlambat = 0;
        $denda = 0;
        if (now()->gt($angsuran->tanggal_jatuh_tempo)) {
            $hariTerlambat = now()->diffInDays($angsuran->tanggal_jatuh_tempo);
            // No penalty (denda) in akad syariah - only tracking for information
            $denda = 0;
        }

        return view('pengurus.pembiayaan.bayar', compact(
            'pengajuan',
            'angsuran',
            'hariTerlambat',
            'denda'
        ));
    }

    /**
     * Store the installment payment record
     */
    public function pembiayaanBayarStore(Request $request, $id, $angsuranId)
    {
        $request->validate([
            'tanggal_bayar' => 'required|date|before_or_equal:today',
            'jumlah_bayar' => 'required|numeric|min:0',
            'denda' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $pengajuan = PengajuanPembiayaan::findOrFail($id);
        $angsuran = Angsuran::findOrFail($angsuranId);

        // Validate ownership
        if ($angsuran->pengajuan_pembiayaan_id != $pengajuan->id) {
            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('error', 'Angsuran tidak ditemukan untuk pembiayaan ini');
        }

        // Check if already paid
        if ($angsuran->status == 'terbayar') {
            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('error', 'Angsuran ini sudah terbayar');
        }

        try {
            DB::beginTransaction();

            // Upload bukti pembayaran
            $buktiFile = $request->file('bukti_pembayaran');
            $buktiName = time() . '_bukti_' . $angsuran->kode_angsuran . '.' . $buktiFile->getClientOriginalExtension();
            $buktiFile->storeAs('public/bukti_pembayaran', $buktiName);

            // Calculate late days and update status
            $hariTerlambat = 0;
            $status = 'terbayar';

            if ($request->tanggal_bayar > $angsuran->tanggal_jatuh_tempo) {
                $hariTerlambat = Carbon::parse($angsuran->tanggal_jatuh_tempo)
                    ->diffInDays($request->tanggal_bayar);
                $status = 'terlambat';
            }

            // Update angsuran record
            $angsuran->update([
                'status' => $status,
                'tanggal_bayar' => $request->tanggal_bayar,
                'denda' => 0, // Syariah compliance: No denda
                'persentase_denda' => 0, // Syariah compliance: No penalty percentage
                'hari_terlambat' => $hariTerlambat, // Still tracking for information
                'keterangan' => $request->keterangan,
                'bukti_pembayaran' => $buktiName,
                'bukti_pembayaran_original' => $buktiFile->getClientOriginalName(),
                'dibayar_oleh' => auth()->id(),
                'tanggal_jatuh_tempo_akhir' => $request->tanggal_bayar
            ]);

            // Check if all installments are paid
            $totalAngsuran = $pengajuan->angsurans()->count();
            $totalTerbayar = $pengajuan->angsurans()->where('status', 'terbayar')->count();

            if ($totalTerbayar == $totalAngsuran) {
                $pengajuan->update(['status' => 'lunas']);
            }

            // Create transaction record (Syariah: No denda included)
            $transaksi = Transaksi::create([
                'kode_transaksi' => 'AGS-' . date('Ymd') . '-' . str_pad($angsuran->angsuran_ke, 3, '0', STR_PAD_LEFT),
                'pengajuan_pembiayaan_id' => $pengajuan->id,
                'anggota_id' => $pengajuan->anggota_id,
                'jenis_transaksi' => 'angsuran',
                'jumlah' => $angsuran->jumlah_angsuran, // Only installment amount, no denda
                'keterangan' => "Pembayaran angsuran ke-{$angsuran->angsuran_ke}",
                'status' => 'completed',
                'created_by' => auth()->id()
            ]);

            // Send notification to anggota about installment payment
            try {
                $anggota = Anggota::find($pengajuan->anggota_id);
                if ($anggota && $anggota->user) {
                    // Get next installment for notification
                    $nextAngsuran = $pengajuan->angsurans()
                        ->whereIn('status', ['pending', 'terlambat'])
                        ->orderBy('angsuran_ke', 'asc')
                        ->first();

                    $anggota->user->notify(new AngsuranNotification($transaksi, $pengajuan, $nextAngsuran));
                }
            } catch (\Exception $e) {
                // Log error but don't fail the payment recording
                \Log::error('Failed to send installment notification: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('success', 'Pembayaran angsuran berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Generate payment schedule for disbursed financing
     */
    public function generateJadwalAngsuran($id)
    {
        $pengajuan = PengajuanPembiayaan::findOrFail($id);

        // Only generate for approved and disbursed financing
        if ($pengajuan->status != 'cair') {
            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('error', 'Jadwal angsuran hanya bisa dibuat untuk pembiayaan yang sudah cair');
        }

        // Check if schedule already exists
        $existingAngsuran = $pengajuan->angsurans()->count();
        if ($existingAngsuran > 0) {
            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('error', 'Jadwal angsuran sudah dibuat sebelumnya');
        }

        try {
            DB::beginTransaction();

            // Generate installment schedule
            Angsuran::generateJadwalAngsuran($pengajuan);

            DB::commit();

            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('success', 'Jadwal angsuran berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('error', 'Gagal membuat jadwal angsuran: ' . $e->getMessage());
        }
    }

    /**
     * Print installment payment receipt
     */
    public function printBuktiBayar($id, $angsuranId)
    {
        $angsuran = Angsuran::with([
            'pengajuanPembiayaan.anggota',
            'pengajuanPembiayaan.jenisPembiayaan',
            'dibayarOleh'
        ])->findOrFail($angsuranId);

        // Verify ownership
        if ($angsuran->pengajuan_pembiayaan_id != $id) {
            abort(404);
        }

        return view('pengurus.pembiayaan.print-bukti', compact('angsuran'));
    }
}
