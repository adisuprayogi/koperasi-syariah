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

        // Total yang sudah dibayar (angsuran yang terbayar)
        $totalSudahBayar = Angsuran::where('status', 'terbayar')
                                    ->whereHas('pengajuanPembiayaan', function($q) {
                                        $q->whereIn('status', ['cair', 'approved', 'lunas']);
                                    })
                                    ->sum('jumlah_angsuran');

        // Total Sisa Angsuran
        $angsuranBelumLunas = Angsuran::where('status', 'pending')
                                       ->whereHas('pengajuanPembiayaan', function($q) {
                                           $q->whereIn('status', ['cair', 'approved']);
                                       });

        $totalSisaAngsuran = $angsuranBelumLunas->sum('jumlah_angsuran');
        $countAngsuranBelumLunas = $angsuranBelumLunas->count();

        // Hitung Total Saldo: ((total simpanan - total penarikan) - total pembiayaan cair) + total pembayaran angsuran
        $saldoSimpanan = $totalSimpanan - $totalPenarikan;
        $totalSaldo = ($saldoSimpanan - $totalPembiayaanCair) + $totalSudahBayar;

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

        // Tunggakan Simpanan Wajib
        $tunggakanSimpananWajib = TransaksiSimpanan::hitungTunggakanSimpananWajib();
        $totalTunggakanWajib = $tunggakanSimpananWajib['total_tunggakan'];
        $jumlahAnggotaNunggakWajib = $tunggakanSimpananWajib['jumlah_anggota_nunggak'];

        // Add tunggakan task if any
        if ($jumlahAnggotaNunggakWajib > 0) {
            $pendingTasks->push((object) [
                'task' => 'Tunggakan Simpanan Wajib',
                'count' => $jumlahAnggotaNunggakWajib,
                'url' => route('pengurus.simpanan.index'),
                'priority' => $jumlahAnggotaNunggakWajib > 10 ? 'high' : 'normal'
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

        // Total Simpanan per Jenis
        $simpananPerJenis = JenisSimpanan::where('status', 1)
            ->get()
            ->map(function($jenis) {
                $totalSetor = TransaksiSimpanan::where('jenis_simpanan_id', $jenis->id)
                    ->where('jenis_transaksi', 'setor')
                    ->sum('jumlah');
                $totalTarik = TransaksiSimpanan::where('jenis_simpanan_id', $jenis->id)
                    ->where('jenis_transaksi', 'tarik')
                    ->sum('jumlah');
                $saldo = $totalSetor - $totalTarik;

                return (object)[
                    'jenis' => $jenis,
                    'total_setor' => $totalSetor,
                    'total_tarik' => $totalTarik,
                    'saldo' => $saldo
                ];
            });

        return view('pengurus.dashboard', compact(
            'pengurus',
            'totalSimpanan',
            'totalPenarikan',
            'saldoSimpanan',
            'totalSaldo',
            'transaksiHariIni',
            'setoranHariIni',
            'penarikanHariIni',
            'totalPembiayaan',
            'totalPembiayaanCair',
            'activePembiayaan',
            'totalMargin',
            'totalSisaAngsuran',
            'countAngsuranBelumLunas',
            'totalSudahBayar',
            'pendingTasks',
            'recentTransaksi',
            'recentPengajuan',
            'monthlySummary',
            'totalTunggakanWajib',
            'jumlahAnggotaNunggakWajib',
            'tunggakanSimpananWajib',
            'simpananPerJenis'
        ));
    }

    /**
     * Index Anggota
     */
    public function anggotaIndex(Request $request)
    {
        $query = Anggota::with('user')->latest();

        // Filter by status
        if ($request->filled('status_keanggotaan')) {
            $query->where('status_keanggotaan', $request->status_keanggotaan);
        }

        // Filter by jenis anggota
        if ($request->filled('jenis_anggota')) {
            $query->where('jenis_anggota', $request->jenis_anggota);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_anggota', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_gabung', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_gabung', '<=', $request->tanggal_selesai);
        }

        $anggota = $query->paginate(10);

        // Get statistics from base query without pagination
        $statsQuery = Anggota::query();
        if ($request->filled('status_keanggotaan')) {
            $statsQuery->where('status_keanggotaan', $request->status_keanggotaan);
        }
        if ($request->filled('jenis_anggota')) {
            $statsQuery->where('jenis_anggota', $request->jenis_anggota);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $statsQuery->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_anggota', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }
        if ($request->filled('tanggal_mulai')) {
            $statsQuery->whereDate('tanggal_gabung', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $statsQuery->whereDate('tanggal_gabung', '<=', $request->tanggal_selesai);
        }

        // Get total counts for statistics
        $totalAnggota = $statsQuery->count();
        $totalAktif = (clone $statsQuery)->where('status_keanggotaan', 'aktif')->count();
        $totalBiasa = (clone $statsQuery)->where('jenis_anggota', 'biasa')->count();
        $totalBulanIni = (clone $statsQuery)->where('tanggal_gabung', '>=', now()->startOfMonth())->count();

        return view('pengurus.anggota.index', compact('anggota', 'totalAnggota', 'totalAktif', 'totalBiasa', 'totalBulanIni'));
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
            'tanggal_gabung' => 'required|date|before_or_equal:today',
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
                'tanggal_gabung' => $request->tanggal_gabung,
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
            'status_keanggotaan' => 'required|in:aktif,tidak_aktif,keluar',
            'jenis_anggota' => 'required|in:biasa,luar_biasa,kehormatan',
            'tanggal_gabung' => 'required|date|before_or_equal:today',
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
                'tanggal_gabung' => $request->tanggal_gabung,
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
     * Show form to change anggota status to keluar
     */
    public function anggotaKeluar($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('pengurus.anggota.keluar', compact('anggota'));
    }

    /**
     * Process anggota keluar status change
     */
    public function anggotaProcessKeluar(Request $request, $id)
    {
        $request->validate([
            'alasan_keluar' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $anggota = Anggota::findOrFail($id);

            // Update status to keluar
            $anggota->update([
                'status_keanggotaan' => 'keluar',
                'tanggal_keluar' => now(),
                'alasan_keluar' => $request->alasan_keluar
            ]);

            // Soft delete the associated user account to deactivate it
            if ($anggota->user) {
                $anggota->user->delete();
            }

            DB::commit();

            return redirect()->route('pengurus.anggota.index')
                ->with('success', 'Anggota ' . $anggota->nama_lengkap . ' telah ditandai sebagai keluar dan akun pengguna telah dinonaktifkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate status anggota: ' . $e->getMessage());
        }
    }

    /**
     * Reactivate anggota (change status back to aktif)
     */
    public function anggotaReaktif($id)
    {
        try {
            DB::beginTransaction();

            $anggota = Anggota::findOrFail($id);

            // Update status back to aktif
            $anggota->update([
                'status_keanggotaan' => 'aktif',
                'tanggal_keluar' => null,
                'alasan_keluar' => null
            ]);

            // Restore the associated user account if it was soft deleted
            if ($anggota->user && $anggota->user->trashed()) {
                $anggota->user->restore();
            }

            DB::commit();

            return redirect()->route('pengurus.anggota.index')
                ->with('success', 'Anggota ' . $anggota->nama_lengkap . ' telah diaktifkan kembali dan akun pengguna telah dipulihkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengaktifkan kembali anggota: ' . $e->getMessage());
        }
    }

    /**
     * Index Transaksi Simpanan
     */
    public function simpananIndex(Request $request)
    {
        // Set default date range to current month if not provided
        $tanggalDari = $request->get('tanggal_dari', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSampai = $request->get('tanggal_sampai', now()->endOfMonth()->format('Y-m-d'));

        $query = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'pengurus'])
                                ->latest();

        // Filter by date range (always applied, with default values)
        $query->whereDate('tanggal_transaksi', '>=', $tanggalDari)
              ->whereDate('tanggal_transaksi', '<=', $tanggalSampai);

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

        $transaksi = $query->paginate(10)->appends([
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'jenis_transaksi' => $request->jenis_transaksi,
            'jenis_simpanan_id' => $request->jenis_simpanan_id,
            'status' => $request->status,
        ]);
        $anggota = Anggota::orderBy('nama_lengkap')->get();
        $jenisSimpanan = JenisSimpanan::where('status', 1)->get();

        // Get statistics from base query without pagination
        $statsQuery = TransaksiSimpanan::query();

        // Apply same filters to statistics (including default date range)
        $statsQuery->whereDate('tanggal_transaksi', '>=', $tanggalDari)
                   ->whereDate('tanggal_transaksi', '<=', $tanggalSampai);

        if ($request->has('jenis_transaksi') && $request->jenis_transaksi) {
            $statsQuery->where('jenis_transaksi', $request->jenis_transaksi);
        }
        if ($request->has('jenis_simpanan_id') && $request->jenis_simpanan_id) {
            $statsQuery->where('jenis_simpanan_id', $request->jenis_simpanan_id);
        }
        if ($request->has('status') && $request->status) {
            $statsQuery->where('status', $request->status);
        }

        // Calculate totals
        $totalSetoran = (clone $statsQuery)->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalPenarikan = (clone $statsQuery)->where('jenis_transaksi', 'tarik')->sum('jumlah');
        $totalTransaksi = $statsQuery->count();
        $totalHariIni = (clone $statsQuery)->whereDate('tanggal_transaksi', today())->count();

        return view('pengurus.simpanan.index', compact(
            'transaksi',
            'anggota',
            'jenisSimpanan',
            'totalSetoran',
            'totalPenarikan',
            'totalTransaksi',
            'totalHariIni',
            'tanggalDari',
            'tanggalSampai'
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
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'keterangan' => 'nullable|string|max:500',
            'bukti_transaksi' => 'nullable|file|mimes:jpeg,png,jpg,pdf,application/pdf|max:500',
        ],
        [
            'bukti_transaksi.max' => 'Ukuran file bukti pembayaran maksimal 500KB. Silakan kompress file terlebih dahulu.',
            'bukti_transaksi.mimes' => 'Format file harus PNG, JPG, JPEG, atau PDF',
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

            // Handle bukti pembayaran upload
            $buktiTransaksiPath = null;
            if ($request->hasFile('bukti_transaksi')) {
                $file = $request->file('bukti_transaksi');
                $filename = time() . '_' . $request->anggota_id . '_' . $kodeTransaksi . '.' . $file->getClientOriginalExtension();
                $path = 'simpanan/bukti/' . $filename;

                // Store file
                $file->storeAs('simpanan/bukti', $filename, 'public');

                // Sync to public/storage for direct access (if needed)
                if (class_exists('\App\Helpers\StorageSyncHelper')) {
                    \App\Helpers\StorageSyncHelper::syncToPublic($path);
                }

                $buktiTransaksiPath = $path;
            }

            // Create transaksi
            $transaksi = TransaksiSimpanan::create([
                'kode_transaksi' => $kodeTransaksi,
                'anggota_id' => $request->anggota_id,
                'jenis_simpanan_id' => $request->jenis_simpanan_id,
                'pengurus_id' => $pengurusId,
                'jenis_transaksi' => $request->jenis_transaksi,
                'jumlah' => $request->jumlah,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'keterangan' => $request->keterangan,
                'bukti_transaksi' => $buktiTransaksiPath,
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
     * Get Saldo API untuk form penarikan
     */
    public function getSaldo(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'jenis_simpanan_id' => 'required|exists:jenis_simpanan,id'
        ]);

        // Hitung saldo dari total semua transaksi: total setoran - total penarikan
        $totalSetor = TransaksiSimpanan::where('anggota_id', $request->anggota_id)
            ->where('jenis_simpanan_id', $request->jenis_simpanan_id)
            ->where('jenis_transaksi', 'setor')
            ->where('status', 'verified')
            ->sum('jumlah');

        $totalTarik = TransaksiSimpanan::where('anggota_id', $request->anggota_id)
            ->where('jenis_simpanan_id', $request->jenis_simpanan_id)
            ->where('jenis_transaksi', 'tarik')
            ->where('status', 'verified')
            ->sum('jumlah');

        $saldo = $totalSetor - $totalTarik;

        return response()->json([
            'saldo' => (float)$saldo
        ]);
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
    public function pengajuanIndex(Request $request)
    {
        $query = PengajuanPembiayaan::with(['anggota', 'jenisPembiayaan'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by search (kode pengajuan or nama anggota)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_pengajuan', 'like', "%{$search}%")
                  ->orWhereHas('anggota', function($query) use ($search) {
                      $query->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        $pengajuans = $query->paginate(10)->appends([
            'status' => $request->status,
            'search' => $request->search,
        ]);

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
        // Rumus BARU: Margin per bulan dikalikan tenor
        $marginPerBulan = $pengajuan->jumlah_pengajuan * ($marginPercent / 100);
        $jumlahMargin = $marginPerBulan * (int)$pengajuan->tenor;
        $totalPembiayaan = $pengajuan->jumlah_pengajuan + $jumlahMargin;

        // Hitung angsuran pokok dan margin per bulan
        $angsuranPokok = $pengajuan->jumlah_pengajuan / (int)$pengajuan->tenor;
        $angsuranMargin = $marginPerBulan;
        $totalAngsuran = $angsuranPokok + $angsuranMargin;

        // Get pengurus ID from logged in user
        $pengurus = Pengurus::where('user_id', auth()->user()->id)->first();
        $pengurusId = $pengurus ? $pengurus->id : null;

        // Update status langsung ke approved dengan perhitungan margin yang benar
        $pengajuan->update([
            'status' => 'approved',
            'tanggal_verifikasi' => now(),
            'verified_by' => $pengurusId,
            'verified_at' => now(),
            'tanggal_approve' => now(),
            'approved_by' => $pengurusId,
            'approved_at' => now(),
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

            // Generate jadwal angsuran otomatis saat pencairan
            try {
                Angsuran::generateJadwalAngsuran($pengajuan);
            } catch (\Exception $e) {
                // Log error but don't fail the disbursement
                \Log::error('Failed to generate jadwal: ' . $e->getMessage());
            }

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
        // Build base query with filters
        $query = PengajuanPembiayaan::with(['anggota', 'angsurans', 'jenisPembiayaan'])
            ->whereIn('status', ['cair', 'lunas'])
            ->when($request->search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('kode_pengajuan', 'like', "%{$search}%")
                      ->orWhereHas('anggota', function($sq) use ($search) {
                          $sq->where('nama_lengkap', 'like', "%{$search}%")
                             ->orWhere('no_anggota', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->jenis_pembiayaan_id, function ($query, $jenisId) {
                $query->where('jenis_pembiayaan_id', $jenisId);
            });

        // Get paginated results
        $pembiayaans = $query->orderBy('updated_at', 'desc')->paginate(10);

        // Get statistics from same query without pagination
        $statsQuery = PengajuanPembiayaan::with(['anggota', 'angsurans', 'jenisPembiayaan'])
            ->whereIn('status', ['cair', 'lunas'])
            ->when($request->search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('kode_pengajuan', 'like', "%{$search}%")
                      ->orWhereHas('anggota', function($sq) use ($search) {
                          $sq->where('nama_lengkap', 'like', "%{$search}%")
                             ->orWhere('no_anggota', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->jenis_pembiayaan_id, function ($query, $jenisId) {
                $query->where('jenis_pembiayaan_id', $jenisId);
            });

        // Calculate statistics
        $totalPembiayaan = $statsQuery->count();
        $totalAktif = (clone $statsQuery)->where('status', 'cair')->count();
        $totalLunas = (clone $statsQuery)->where('status', 'lunas')->count();
        $totalNilai = $statsQuery->sum('jumlah_pengajuan');

        $jenisPembiayaans = JenisPembiayaan::orderBy('nama_pembiayaan')->get();

        return view('pengurus.pembiayaan.index', compact(
            'pembiayaans',
            'jenisPembiayaans',
            'totalPembiayaan',
            'totalAktif',
            'totalLunas',
            'totalNilai'
        ));
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
        $totalTerbayar = $pengajuan->angsurans->whereIn('status', ['terbayar', 'lunas_lebih_cepat'])->count();
        $totalPending = $pengajuan->angsurans->where('status', 'pending')->count();
        $totalTerlambat = $pengajuan->angsurans->where('status', 'terlambat')->count();

        $angsuranBerikutnya = $pengajuan->angsurans()
            ->where('status', 'pending')
            ->where('tanggal_jatuh_tempo', '>=', now()->startOfDay())
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

        // Check if already fully paid
        if (in_array($angsuran->status, ['terbayar', 'lunas_lebih_cepat'])) {
            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('error', 'Angsuran ini sudah lunas');
        }

        try {
            DB::beginTransaction();

            $jumlahBayar = (float)$request->jumlah_bayar;
            $totalHarusBayar = (float)$angsuran->jumlah_angsuran;

            // Upload bukti pembayaran
            $buktiFile = $request->file('bukti_pembayaran');
            $buktiName = time() . '_bukti_' . $angsuran->kode_angsuran . '.' . $buktiFile->getClientOriginalExtension();
            $buktiFile->storeAs('public/bukti_pembayaran', $buktiName);

            // Calculate late days
            $hariTerlambat = 0;
            if ($request->tanggal_bayar > $angsuran->tanggal_jatuh_tempo) {
                $hariTerlambat = Carbon::parse($angsuran->tanggal_jatuh_tempo)
                    ->diffInDays($request->tanggal_bayar);
            }

            // Process payment (support partial payment)
            $sisaSetelahBayar = $totalHarusBayar - $jumlahBayar;

            if ($angsuran->status == 'partial_bayar') {
                // If already partial, add to existing payment
                $jumlahDibayarSebelumnya = (float)$angsuran->jumlah_dibayar;
                $totalDibayar = $jumlahDibayarSebelumnya + $jumlahBayar;
                $sisaBaru = $totalHarusBayar - $totalDibayar;

                if ($sisaBaru <= 0) {
                    // Fully paid
                    $status = 'terbayar';
                    $finalDibayar = $totalHarusBayar;
                    $finalSisa = 0;
                } else {
                    // Still partial
                    $status = 'partial_bayar';
                    $finalDibayar = $totalDibayar;
                    $finalSisa = $sisaBaru;
                }

                $angsuran->update([
                    'status' => $status,
                    'jumlah_dibayar' => $finalDibayar,
                    'sisa_dibawa' => $finalSisa,
                    'tanggal_bayar' => $request->tanggal_bayar,
                    'denda' => 0, // Syariah compliance: No denda
                    'persentase_denda' => 0,
                    'hari_terlambat' => $hariTerlambat,
                    'keterangan' => $request->keterangan,
                    'bukti_pembayaran' => $buktiName,
                    'bukti_pembayaran_original' => $buktiFile->getClientOriginalName(),
                    'dibayar_oleh' => auth()->id(),
                    'tanggal_jatuh_tempo_akhir' => $request->tanggal_bayar
                ]);
            } else {
                // First payment
                if ($sisaSetelahBayar <= 0) {
                    // Full payment - lunas_lebih_cepat if paid before due date
                    // TAPI perpanjangan TIDAK BOLEH status lunas_lebih_cepat karena itu dari keterlambatan
                    if ($angsuran->is_perpanjangan) {
                        $status = 'terbayar';
                    } else {
                        $status = ($request->tanggal_bayar < $angsuran->tanggal_jatuh_tempo) ? 'lunas_lebih_cepat' : 'terbayar';
                    }
                    $finalDibayar = $totalHarusBayar;
                    $finalSisa = 0;

                    $angsuran->update([
                        'status' => $status,
                        'jumlah_dibayar' => $finalDibayar,
                        'sisa_dibawa' => $finalSisa,
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'denda' => 0,
                        'persentase_denda' => 0,
                        'hari_terlambat' => $hariTerlambat,
                        'keterangan' => $request->keterangan,
                        'bukti_pembayaran' => $buktiName,
                        'bukti_pembayaran_original' => $buktiFile->getClientOriginalName(),
                        'dibayar_oleh' => auth()->id(),
                        'tanggal_jatuh_tempo_akhir' => $request->tanggal_bayar
                    ]);
                } else {
                    // Partial payment
                    $angsuran->update([
                        'status' => 'partial_bayar',
                        'jumlah_dibayar' => $jumlahBayar,
                        'sisa_dibawa' => $sisaSetelahBayar,
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'denda' => 0,
                        'persentase_denda' => 0,
                        'hari_terlambat' => $hariTerlambat,
                        'keterangan' => $request->keterangan,
                        'bukti_pembayaran' => $buktiName,
                        'bukti_pembayaran_original' => $buktiFile->getClientOriginalName(),
                        'dibayar_oleh' => auth()->id(),
                        'tanggal_jatuh_tempo_akhir' => $request->tanggal_bayar
                    ]);

                    // Roll sisa to next period
                    $this->rollSisaKePeriodeBerikutnya($pengajuan, $angsuran->angsuran_ke, $sisaSetelahBayar);
                }
            }

            // Check if all installments are fully paid (including partial_bayar that's completed)
            $this->checkLunas($pengajuan);

            // Create transaction record for the actual payment amount
            $transaksi = Transaksi::create([
                'kode_transaksi' => 'AGS-' . date('Ymd') . '-' . str_pad($angsuran->angsuran_ke, 3, '0', STR_PAD_LEFT),
                'pengajuan_pembiayaan_id' => $pengajuan->id,
                'anggota_id' => $pengajuan->anggota_id,
                'jenis_transaksi' => 'angsuran',
                'jumlah' => $jumlahBayar,
                'keterangan' => "Pembayaran angsuran ke-{$angsuran->angsuran_ke}" . ($sisaSetelahBayar > 0 ? " (partial)" : ""),
                'status' => 'completed',
                'created_by' => auth()->id()
            ]);

            // Send notification to anggota about installment payment
            try {
                $anggota = Anggota::find($pengajuan->anggota_id);
                if ($anggota && $anggota->user) {
                    // Get next installment for notification
                    $nextAngsuran = $pengajuan->angsurans()
                        ->where('status', 'pending')
                        ->orderBy('angsuran_ke', 'asc')
                        ->first();

                    $anggota->user->notify(new AngsuranNotification($transaksi, $pengajuan, $nextAngsuran));
                }
            } catch (\Exception $e) {
                // Log error but don't fail the payment recording
                \Log::error('Failed to send installment notification: ' . $e->getMessage());
            }

            DB::commit();

            $message = $sisaSetelahBayar > 0
                ? "Pembayaran sebagian berhasil dicatat! Sisa Rp " . number_format($sisaSetelahBayar, 0, ',', '.') . " akan ditambahkan ke periode berikutnya."
                : "Pembayaran angsuran berhasil dicatat!";

            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Form Lunas Lebih Cepat (Bayar semua sisa angsuran sekaligus)
     */
    public function lunasLebihCepatForm($id)
    {
        $pengajuan = PengajuanPembiayaan::with([
            'anggota',
            'jenisPembiayaan',
            'angsurans' => function($query) {
                $query->orderBy('angsuran_ke', 'asc');
            }
        ])->findOrFail($id);

        // Cek apakah sudah lunas
        if ($pengajuan->status == 'lunas') {
            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('info', 'Pembiayaan ini sudah lunas');
        }

        // Cek apakah masih ada angsuran pending
        $angsuranPending = $pengajuan->angsurans()
            ->where('status', 'pending')
            ->orderBy('angsuran_ke', 'asc')
            ->get();

        if ($angsuranPending->isEmpty()) {
            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('info', 'Tidak ada angsuran yang perlu dibayar');
        }

        // Hitung total sisa
        $sisaPokok = $pengajuan->sisaPokok();
        $sisaMargin = $pengajuan->sisaMargin();
        $sisaTotal = $pengajuan->sisaTotal();
        $jumlahAngsuranPending = $angsuranPending->count();

        return view('pengurus.pembiayaan.lunas-lebih-cepat', compact(
            'pengajuan',
            'sisaPokok',
            'sisaMargin',
            'sisaTotal',
            'jumlahAngsuranPending',
            'angsuranPending'
        ));
    }

    /**
     * Proses Lunas Lebih Cepat (Bayar semua sisa angsuran sekaligus)
     */
    public function lunasLebihCepatStore(Request $request, $id)
    {
        $request->validate([
            'tanggal_bayar' => 'required|date|before_or_equal:today',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string|max:500'
        ]);

        $pengajuan = PengajuanPembiayaan::findOrFail($id);

        // Cek apakah sudah lunas
        if ($pengajuan->status == 'lunas') {
            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('info', 'Pembiayaan ini sudah lunas');
        }

        try {
            DB::beginTransaction();

            // Upload bukti pembayaran
            $buktiFile = $request->file('bukti_pembayaran');
            $buktiName = time() . '_lunas_cepat_' . $pengajuan->kode_pengajuan . '.' . $buktiFile->getClientOriginalExtension();
            $buktiFile->storeAs('public/bukti_pembayaran', $buktiName);

            // Ambil semua angsuran yang masih pending
            $angsuranPending = $pengajuan->angsurans()
                ->where('status', 'pending')
                ->orderBy('angsuran_ke', 'asc')
                ->get();

            $totalDibayar = 0;
            $tanggalBayar = $request->tanggal_bayar;

            // Update semua angsuran pending menjadi lunas_lebih_cepat
            foreach ($angsuranPending as $angsuran) {
                // Cek apakah tanggal bayar sebelum jatuh tempo
                // TAPI perpanjangan TIDAK BOLEH status lunas_lebih_cepat
                $isLebihCepat = !$angsuran->is_perpanjangan && ($tanggalBayar < $angsuran->tanggal_jatuh_tempo);
                $status = $isLebihCepat ? 'lunas_lebih_cepat' : 'terbayar';

                $angsuran->update([
                    'status' => $status,
                    'jumlah_dibayar' => $angsuran->jumlah_angsuran,
                    'sisa_dibawa' => 0,
                    'tanggal_bayar' => $tanggalBayar,
                    'denda' => 0,
                    'persentase_denda' => 0,
                    'hari_terlambat' => 0,
                    'keterangan' => 'Pelunasan lebih cepat - ' . ($request->keterangan ?? ''),
                    'bukti_pembayaran' => $buktiName,
                    'bukti_pembayaran_original' => $buktiFile->getClientOriginalName(),
                    'dibayar_oleh' => auth()->id(),
                    'tanggal_jatuh_tempo_akhir' => $tanggalBayar
                ]);

                $totalDibayar += $angsuran->jumlah_angsuran;
            }

            // Update status pembiayaan menjadi lunas
            $pengajuan->update(['status' => 'lunas']);

            // Create transaction record
            $transaksi = Transaksi::create([
                'kode_transaksi' => 'LC-' . date('Ymd') . '-' . $pengajuan->kode_pengajuan,
                'pengajuan_pembiayaan_id' => $pengajuan->id,
                'anggota_id' => $pengajuan->anggota_id,
                'jenis_transaksi' => 'lunas_lebih_cepat',
                'jumlah' => $totalDibayar,
                'keterangan' => 'Pelunasan lebih cepat ' . $angsuranPending->count() . ' angsuran',
                'status' => 'completed',
                'created_by' => auth()->id()
            ]);

            DB::commit();

            return redirect()->route('pengurus.pembiayaan.show', $id)
                ->with('success', 'Alhamdulillah! Pembiayaan telah dilunasi lebih cepat. Total: Rp ' . number_format($totalDibayar, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal melunasi lebih cepat: ' . $e->getMessage());
        }
    }

    /**
     * Roll sisa pembayaran ke periode berikutnya
     * Jika tidak ada periode berikutnya, buat perpanjangan 1 bulan (untuk tracking keterlambatan)
     */
    private function rollSisaKePeriodeBerikutnya($pengajuan, $currentPeriode, $sisa)
    {
        // Cari periode berikutnya yang masih pending
        $nextAngsuran = $pengajuan->angsurans()
            ->where('angsuran_ke', '>', $currentPeriode)
            ->whereIn('status', ['pending'])
            ->orderBy('angsuran_ke', 'asc')
            ->first();

        if ($nextAngsuran) {
            // Tambah sisa ke periode berikutnya
            $nextAngsuran->update([
                'jumlah_angsuran' => $nextAngsuran->jumlah_angsuran + $sisa,
                'catatan' => 'Membawa sisa dari periode ' . $currentPeriode . ': Rp ' . number_format($sisa, 0, ',', '.')
            ]);
        } else {
            // Tidak ada periode berikutnya? Buat perpanjangan 1 bulan untuk tracking keterlambatan
            if ($pengajuan->needsPerpanjangan()) {
                $pengajuan->buatPerpanjangan(1); // Hanya 1 bulan, bukan 6
            }
        }
    }

    /**
     * Check if all installments are paid and update status
     */
    private function checkLunas($pengajuan)
    {
        // Hitung total yang harus dibayar (pokok + margin)
        $totalPokok = (float)$pengajuan->jumlah_pengajuan;
        $totalMargin = (float)$pengajuan->jumlah_margin;
        $totalHarusDibayar = $totalPokok + $totalMargin;

        // Hitung total yang sudah dibayar dari semua angsuran (termasuk partial_bayar)
        $totalDibayar = (float)$pengajuan->totalDibayar();

        // Cek apakah ada angsuran pending yang BELUM dibayar sama sekali
        $pendingBelumBayar = $pengajuan->angsurans()
            ->where('status', 'pending')
            ->where('jumlah_dibayar', 0)
            ->count();

        // Cek apakah semua sudah lunas
        // Syarat: totalDibayar >= totalHarusDibayar DAN tidak ada pending yang belum dibayar
        $isLunas = ($totalDibayar >= ($totalHarusDibayar - 0.01)) && ($pendingBelumBayar === 0);

        \Log::info('checkLunas for pembiayaan ' . $pengajuan->id, [
            'totalPokok' => $totalPokok,
            'totalMargin' => $totalMargin,
            'totalHarusDibayar' => $totalHarusDibayar,
            'totalDibayar' => $totalDibayar,
            'pendingBelumBayar' => $pendingBelumBayar,
            'isLunas' => $isLunas,
            'current_status' => $pengajuan->status
        ]);

        if ($isLunas) {
            $pengajuan->update(['status' => 'lunas']);
            \Log::info('Updated pembiayaan ' . $pengajuan->id . ' to lunas');
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

    /**
     * Bayar Jadwal Angsuran (Support Partial Payment)
     */
    public function bayarJadwalAngsuran(Request $request, $id, $periode)
    {
        $pengajuan = PengajuanPembiayaan::findOrFail($id);
        $angsuran = Angsuran::where('pengajuan_pembiayaan_id', $id)
            ->where('angsuran_ke', $periode)
            ->whereIn('status', ['pending', 'partial_bayar'])
            ->firstOrFail();

        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $jumlahBayar = (float)$request->jumlah_bayar;
            $sisaHarusBayar = $angsuran->jumlah_angsuran - $angsuran->jumlah_dibayar;
            $totalSetelahBayar = $angsuran->jumlah_dibayar + $jumlahBayar;

            // CEK: Jika partial payment dan akan membuat perpanjangan ke-7
            if ($totalSetelahBayar < $angsuran->jumlah_angsuran) {
                // Ini partial payment, cek apakah akan butuh perpanjangan
                $totalPerpanjangan = $pengajuan->angsurans()->where('is_perpanjangan', true)->count();

                // Cek apakah ada periode berikutnya yang masih pending
                $nextAngsuran = Angsuran::where('pengajuan_pembiayaan_id', $id)
                    ->where('angsuran_ke', '>', $periode)
                    ->where('status', 'pending')
                    ->orderBy('angsuran_ke', 'asc')
                    ->first();

                // Jika tidak ada periode berikutnya DAN sudah 6 perpanjangan
                if (!$nextAngsuran && $totalPerpanjangan >= 6) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Batas maksimal perpanjangan (6 bulan) telah tercapai. Pembayaran harus LUNAS TEPAT sebesar Rp ' . number_format($sisaHarusBayar, 0, ',', '.') . '. Tidak bisa melakukan pembayaran partial lagi.',
                        'require_exact_amount' => true,
                        'required_amount' => $sisaHarusBayar
                    ], 400);
                }

                // Jika akan membuat perpanjangan ke-7, beri peringatan
                if (!$nextAngsuran && $totalPerpanjangan >= 5) {
                    // Masih bisa, tapi ini akan jadi perpanjangan terakhir (ke-6)
                }
            }

            if ($totalSetelahBayar >= $angsuran->jumlah_angsuran) {
                // Lunas penuh untuk periode ini
                $kelebihan = $totalSetelahBayar - $angsuran->jumlah_angsuran;

                $angsuran->update([
                    'status' => 'terbayar',
                    'jumlah_dibayar' => $angsuran->jumlah_angsuran,
                    'sisa_dibawa' => 0,
                    'tanggal_bayar' => $request->tanggal_bayar,
                    'catatan' => $request->catatan
                ]);

                // Jika ada kelebihan, tambahkan ke periode berikutnya
                if ($kelebihan > 0) {
                    $nextAngsuran = Angsuran::where('pengajuan_pembiayaan_id', $id)
                        ->where('angsuran_ke', '>', $periode)
                        ->where('status', 'pending')
                        ->orderBy('angsuran_ke', 'asc')
                        ->first();

                    if ($nextAngsuran) {
                        // Tambahkan ke periode berikutnya (rolling)
                        $nextAngsuran->update([
                            'jumlah_pokok' => $nextAngsuran->jumlah_pokok + $kelebihan,
                            'jumlah_angsuran' => $nextAngsuran->jumlah_pokok + $nextAngsuran->jumlah_margin + $kelebihan
                        ]);
                    }
                }
            } else {
                // Partial payment
                $sisaDibawa = $angsuran->jumlah_angsuran - $totalSetelahBayar;

                $angsuran->update([
                    'status' => 'partial_bayar',
                    'jumlah_dibayar' => $totalSetelahBayar,
                    'sisa_dibawa' => $sisaDibawa,
                    'tanggal_bayar' => $request->tanggal_bayar,
                    'catatan' => $request->catatan
                ]);

                // Tambahkan sisa ke periode berikutnya (rolling)
                $nextAngsuran = Angsuran::where('pengajuan_pembiayaan_id', $id)
                    ->where('angsuran_ke', '>', $periode)
                    ->where('status', 'pending')
                    ->orderBy('angsuran_ke', 'asc')
                    ->first();

                if ($nextAngsuran) {
                    $nextAngsuran->update([
                        'jumlah_pokok' => $nextAngsuran->jumlah_pokok + $sisaDibawa,
                        'jumlah_angsuran' => $nextAngsuran->jumlah_pokok + $nextAngsuran->jumlah_margin + $sisaDibawa
                    ]);
                } else {
                    // Tidak ada periode berikutnya? Buat perpanjangan 1 bulan untuk tracking keterlambatan
                    if ($pengajuan->needsPerpanjangan()) {
                        $pengajuan->buatPerpanjangan(1); // Hanya 1 bulan, bukan 6
                    }
                }
            }

            // Cek jika semua sudah lunas (termasuk partial yang sudah lunas)
            $this->checkLunas($pengajuan);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pelunasan Lebih Cepat
     */
    public function lunasLebihCepat(Request $request, $id)
    {
        $pengajuan = PengajuanPembiayaan::findOrFail($id);

        try {
            DB::beginTransaction();

            $catatan = $request->catatan ?? 'Pelunasan lebih cepat oleh ' . auth()->user()->name;

            // Get all pending angsuran
            $pendingAngsurans = $pengajuan->jadwalPending()->get();

            if ($pendingAngsurans->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada angsuran pending yang bisa dilunasi.'
                ], 400);
            }

            // Update all pending angsuran to lunas_lebih_cepat
            // TAPI perpanjangan TIDAK BOLEH status lunas_lebih_cepat
            $count = 0;
            foreach ($pendingAngsurans as $angsuran) {
                $status = $angsuran->is_perpanjangan ? 'terbayar' : 'lunas_lebih_cepat';
                $angsuran->update([
                    'status' => $status,
                    'tanggal_bayar' => now(),
                    'catatan' => $catatan
                ]);
                $count++;
            }

            // Update pengajuan status to lunas
            $pengajuan->update(['status' => 'lunas']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil melunasi {$count} periode sekaligus!",
                'data' => ['processed' => $count]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
