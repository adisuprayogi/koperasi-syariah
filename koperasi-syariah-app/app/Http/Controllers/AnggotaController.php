<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiSimpanan;
use App\Models\JenisSimpanan;
use App\Models\JenisPembiayaan;
use App\Models\PengajuanPembiayaan;
use App\Models\Angsuran;
use Illuminate\Support\Facades\DB;

class AnggotaController extends Controller
{
    /**
     * Dashboard Anggota
     */
    public function dashboard()
    {
        $user = auth()->user();
        $anggota = $user->anggota;

        if (!$anggota) {
            return redirect()->route('anggota.profile')->with('error', 'Data anggota tidak ditemukan');
        }

        // Saldo per jenis simpanan
        $saldoPerJenis = JenisSimpanan::where('status', 1)
            ->with(['transaksi' => function($query) use ($anggota) {
                $query->where('anggota_id', $anggota->id)
                      ->where('status', 'verified')
                      ->latest();
            }])
            ->get()
            ->map(function($jenis) {
                $saldoTerakhir = $jenis->transaksi->first();
                return (object)[
                    'jenis' => $jenis,
                    'saldo' => $saldoTerakhir ? $saldoTerakhir->saldo_setelahnya : 0
                ];
            });

        $totalSimpanan = $saldoPerJenis->sum('saldo');

        // Statistik Pembiayaan
        $pembiayaans = PengajuanPembiayaan::where('anggota_id', $anggota->id)
                                          ->whereIn('status', ['approved', 'cair', 'lunas'])
                                          ->get();

        $totalPembiayaan = $pembiayaans->sum('jumlah_pengajuan');
        $activePembiayaan = $pembiayaans->where('status', 'cair')->count();
        $totalMargin = $pembiayaans->sum('jumlah_margin');

        // Hitung sisa pinjaman
        $sisaPinjaman = 0;
        foreach ($pembiayaans as $pembiayaan) {
            if (in_array($pembiayaan->status, ['cair', 'approved'])) {
                $sisaPinjaman += $pembiayaan->sisaTotal();
            }
        }

        // Status Pengajuan
        $statusPengajuan = [
            'draft' => PengajuanPembiayaan::where('anggota_id', $anggota->id)
                                          ->where('status', 'draft')
                                          ->count(),
            'diajukan' => PengajuanPembiayaan::where('anggota_id', $anggota->id)
                                             ->where('status', 'diajukan')
                                             ->count(),
            'approved' => PengajuanPembiayaan::where('anggota_id', $anggota->id)
                                            ->where('status', 'approved')
                                            ->count(),
            'cair' => PengajuanPembiayaan::where('anggota_id', $anggota->id)
                                         ->where('status', 'cair')
                                         ->count(),
            'rejected' => PengajuanPembiayaan::where('anggota_id', $anggota->id)
                                             ->where('status', 'rejected')
                                             ->count(),
        ];

        // Angsuran berikutnya
        $angsuranBerikutnya = Angsuran::whereHas('pengajuanPembiayaan', function($q) use ($anggota) {
                                        $q->where('anggota_id', $anggota->id)
                                          ->whereIn('status', ['cair', 'approved']);
                                    })
                                    ->where('status', 'pending')
                                    ->orderBy('tanggal_jatuh_tempo')
                                    ->first();

        // Recent activities
        $recentTransaksi = TransaksiSimpanan::where('anggota_id', $anggota->id)
                                            ->latest('tanggal_transaksi')
                                            ->limit(5)
                                            ->get();

        $recentPengajuan = PengajuanPembiayaan::where('anggota_id', $anggota->id)
                                              ->latest()
                                              ->limit(5)
                                              ->get();

        // Monthly summary
        $monthlySummary = TransaksiSimpanan::select(
                DB::raw('YEAR(tanggal_transaksi) as year'),
                DB::raw('MONTH(tanggal_transaksi) as month'),
                DB::raw('SUM(CASE WHEN jenis_transaksi = "setor" THEN jumlah ELSE 0 END) as total_setor'),
                DB::raw('SUM(CASE WHEN jenis_transaksi = "tarik" THEN jumlah ELSE 0 END) as total_tarik')
            )
            ->where('anggota_id', $anggota->id)
            ->where('tanggal_transaksi', '>=', now()->subMonths(6))
            ->groupBy(DB::raw('YEAR(tanggal_transaksi)'), DB::raw('MONTH(tanggal_transaksi)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Tunggakan Simpanan Wajib untuk anggota ini
        $tunggakanSimpananWajib = TransaksiSimpanan::hitungTunggakanPerAnggota($anggota->id);
        $bulanNunggak = $tunggakanSimpananWajib['bulan_nunggak'];
        $totalTunggakanWajib = $tunggakanSimpananWajib['total_tunggakan'];
        $detailBulanNunggak = $tunggakanSimpananWajib['detail_bulan_nunggak'];

        return view('anggota.dashboard', compact(
            'saldoPerJenis',
            'totalSimpanan',
            'totalPembiayaan',
            'sisaPinjaman',
            'activePembiayaan',
            'totalMargin',
            'statusPengajuan',
            'angsuranBerikutnya',
            'recentTransaksi',
            'recentPengajuan',
            'monthlySummary',
            'bulanNunggak',
            'totalTunggakanWajib',
            'detailBulanNunggak'
        ));
    }

    /**
     * Profile
     */
    public function profile()
    {
        return view('anggota.profile');
    }

    /**
     * Update Profile
     */
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'pendapatan' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $user = auth()->user();
            $anggota = $user->anggota;

            // Update anggota data
            $anggota->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat_lengkap' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'penghasilan' => $request->pendapatan,
            ]);

            // Handle photo upload
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                $path = 'anggota/photos/' . $filename;
                $file->storeAs('anggota/photos', $filename, 'public');
                $anggota->update(['foto' => $path]);

                // Sync to public/storage for direct access
                \App\Helpers\StorageSyncHelper::syncToPublic($path);
            }

            return redirect()->route('anggota.profile')->with('success', 'Profil berhasil diperbarui');

        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Index Simpanan
     */
    public function simpananIndex(Request $request)
    {
        $user = auth()->user();
        $anggota = $user->anggota;

        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Data anggota tidak ditemukan');
        }

        // Query untuk transaksi simpanan anggota ini saja
        $query = TransaksiSimpanan::with(['jenisSimpanan', 'pengurus'])
                                ->where('anggota_id', $anggota->id)
                                ->orderByDesc('tahun')
                                ->orderByDesc('bulan')
                                ->orderByDesc('created_at')
                                ->orderByDesc('id');

        // Filter by date range
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        // Filter by jenis simpanan
        if ($request->has('jenis_simpanan_id') && $request->jenis_simpanan_id) {
            $query->where('jenis_simpanan_id', $request->jenis_simpanan_id);
        }

        $transaksi = $query->paginate(10);
        $jenisSimpanan = JenisSimpanan::where('status', 1)->get();

        // Hitung total simpanan per jenis berdasarkan saldo terakhir
        $totalSimpananPokok = 0;
        $totalSimpananWajib = 0;
        $totalSimpananWajibBulanan = 0;
        $totalSimpananSukarela = 0;
        $totalSimpananModal = 0;

        foreach ($jenisSimpanan as $jenis) {
            // Ambil transaksi terakhir untuk jenis simpanan ini
            $transaksiTerakhir = TransaksiSimpanan::where('anggota_id', $anggota->id)
                ->where('jenis_simpanan_id', $jenis->id)
                ->where('status', 'verified')
                ->latest()
                ->first();

            $saldo = $transaksiTerakhir ? $transaksiTerakhir->saldo_setelahnya : 0;

            $namaSimpanan = strtolower($jenis->nama_simpanan);
            if (strpos($namaSimpanan, 'pokok') !== false) {
                $totalSimpananPokok += $saldo;
            } elseif (strpos($namaSimpanan, 'wajib') !== false) {
                if (strpos($namaSimpanan, 'bulanan') !== false) {
                    $totalSimpananWajibBulanan += $saldo;
                } else {
                    $totalSimpananWajib += $saldo;
                }
            } elseif (strpos($namaSimpanan, 'sukarela') !== false) {
                $totalSimpananSukarela += $saldo;
            } elseif (strpos($namaSimpanan, 'modal') !== false) {
                $totalSimpananModal += $saldo;
            }
        }

        return view('anggota.simpanan.index', compact(
            'transaksi',
            'jenisSimpanan',
            'totalSimpananPokok',
            'totalSimpananWajib',
            'totalSimpananSukarela',
            'totalSimpananModal'
        ));
    }

    /**
     * Show Simpanan
     */
    public function simpananShow($id)
    {
        $user = auth()->user();
        $anggota = $user->anggota;

        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Data anggota tidak ditemukan');
        }

        // Get transaksi yang dimiliki anggota ini
        $transaksi = TransaksiSimpanan::with(['jenisSimpanan', 'anggota', 'pengurus'])
                                    ->where('id', $id)
                                    ->where('anggota_id', $anggota->id)
                                    ->first();

        if (!$transaksi) {
            return redirect()->route('anggota.simpanan.index')->with('error', 'Transaksi tidak ditemukan');
        }

        return view('anggota.simpanan.show', compact('transaksi'));
    }

    /**
     * Index Pengajuan
     */
    public function pengajuanIndex()
    {
        return view('anggota.pengajuan.index');
    }

    /**
     * Create Pengajuan
     */
    public function pengajuanCreate()
    {
        $user = auth()->user();
        $anggota = $user->anggota;

        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Data anggota tidak ditemukan');
        }

        $jenisPembiayaan = JenisPembiayaan::where('status', 1)->get();

        $tujuanOptions = [
            'modal_kerja' => 'Modal Kerja',
            'investasi' => 'Investasi',
            'konsumtif' => 'Konsumtif',
            'pendidikan' => 'Pendidikan',
            'sewa' => 'Sewa',
            'lainnya' => 'Lainnya'
        ];

        return view('anggota.pengajuan.create', compact('anggota', 'jenisPembiayaan', 'tujuanOptions'));
    }

    /**
     * Store Pengajuan
     */
    public function pengajuanStore(Request $request)
    {
        // TODO: Implement store logic
        return redirect()->route('anggota.pengajuan.index')->with('success', 'Pengajuan berhasil dikirim');
    }

    /**
     * Show Pengajuan
     */
    public function pengajuanShow($id)
    {
        $user = auth()->user();
        $anggota = $user->anggota;

        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Data anggota tidak ditemukan');
        }

        // Get pengajuan yang dimiliki anggota ini
        $pengajuan = PengajuanPembiayaan::with(['jenisPembiayaan', 'anggota', 'verifiedBy', 'approvedBy', 'pencair'])
                                     ->where('id', $id)
                                     ->where('anggota_id', $anggota->id)
                                     ->first();

        if (!$pengajuan) {
            return redirect()->route('anggota.pengajuan.index')->with('error', 'Pengajuan tidak ditemukan');
        }

        return view('anggota.pengajuan.show', compact('pengajuan'));
    }

    /**
     * Index Pembiayaan
     */
    public function pembiayaanIndex(Request $request)
    {
        $user = auth()->user();
        $anggota = $user->anggota;

        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Data anggota tidak ditemukan');
        }

        // Query untuk pengajuan pembiayaan anggota ini saja
        $query = PengajuanPembiayaan::with(['jenisPembiayaan'])
                                   ->where('anggota_id', $anggota->id)
                                   ->latest();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by jenis pembiayaan
        if ($request->has('jenis_pembiayaan_id') && $request->jenis_pembiayaan_id) {
            $query->where('jenis_pembiayaan_id', $request->jenis_pembiayaan_id);
        }

        $pembiayaan = $query->paginate(10);
        $jenisPembiayaan = JenisPembiayaan::where('status', 1)->get();

        // Hitung statistik
        $totalPinjaman = PengajuanPembiayaan::where('anggota_id', $anggota->id)
                                          ->whereIn('status', ['approved', 'cair'])
                                          ->sum('jumlah_pengajuan');

        $sisaPinjaman = 0;
        $angsuranTerbayar = 0;
        $totalAngsuran = 0;
        $pembiayaanAktif = 0;

        foreach ($pembiayaan as $item) {
            if (in_array($item->status, ['approved', 'cair'])) {
                $pembiayaanAktif++;
                // Hitung angsuran
                $angsurans = Angsuran::where('pengajuan_pembiayaan_id', $item->id)
                                   ->get();
                $totalAngsuran += $angsurans->count();

                $terbayarCount = Angsuran::where('pengajuan_pembiayaan_id', $item->id)
                                      ->where('status', 'terbayar')
                                      ->count();
                $angsuranTerbayar += $terbayarCount;

                // Hitung sisa pinjaman
                $sisaPinjaman += $item->sisaTotal();
            }
        }

        return view('anggota.pembiayaan.index', compact(
            'pembiayaan',
            'jenisPembiayaan',
            'totalPinjaman',
            'sisaPinjaman',
            'angsuranTerbayar',
            'totalAngsuran',
            'pembiayaanAktif'
        ));
    }

    /**
     * Show Pembiayaan
     */
    public function pembiayaanShow($id)
    {
        $user = auth()->user();
        $anggota = $user->anggota;

        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Data anggota tidak ditemukan');
        }

        // Get pengajuan pembiayaan yang dimiliki anggota ini
        $pembiayaan = PengajuanPembiayaan::with(['jenisPembiayaan', 'anggota', 'verifiedBy', 'approvedBy', 'pencair', 'angsurans'])
                                       ->where('id', $id)
                                       ->where('anggota_id', $anggota->id)
                                       ->first();

        if (!$pembiayaan) {
            return redirect()->route('anggota.pembiayaan.index')->with('error', 'Pembiayaan tidak ditemukan');
        }

        // Get angsuran jika status cair/lunas
        $angsurans = null;
        if (in_array($pembiayaan->status, ['cair', 'lunas'])) {
            $angsurans = Angsuran::where('pengajuan_pembiayaan_id', $pembiayaan->id)
                                ->orderBy('angsuran_ke')
                                ->get();
        }

        return view('anggota.pembiayaan.show', compact('pembiayaan', 'angsurans'));
    }

    /**
     * Download Kartu Anggota
     */
    public function downloadKartu()
    {
        try {
            $user = auth()->user();
            $anggota = $user->anggota;

            if (!$anggota) {
                return redirect()->back()->with('error', 'Data anggota tidak ditemukan');
            }

            // Use existing KartuAnggotaController method
            $kartuController = new \App\Http\Controllers\Admin\KartuAnggotaController();
            return $kartuController->downloadPDF($anggota->id);

        } catch (\Exception $e) {
            \Log::error('Kartu download error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal download kartu anggota');
        }
    }

    }
