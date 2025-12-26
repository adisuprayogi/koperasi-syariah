<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\TransaksiSimpanan;
use App\Models\PengajuanPembiayaan;
use App\Models\Angsuran;
use App\Models\JenisSimpanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard Anggota
     */
    public function index()
    {
        $user = auth()->user();
        $anggota = Anggota::where('user_id', $user->id)->firstOrFail();

        // Saldo per jenis simpanan
        $jenisSimpanan = JenisSimpanan::all();
        $saldoPerJenis = collect();

        foreach ($jenisSimpanan as $jenis) {
            $totalSetor = TransaksiSimpanan::where('anggota_id', $anggota->id)
                                          ->where('jenis_simpanan_id', $jenis->id)
                                          ->where('jenis_transaksi', 'setor')
                                          ->sum('jumlah');

            $totalTarik = TransaksiSimpanan::where('anggota_id', $anggota->id)
                                          ->where('jenis_simpanan_id', $jenis->id)
                                          ->where('jenis_transaksi', 'tarik')
                                          ->sum('jumlah');

            $saldo = $totalSetor - $totalTarik;

            $saldoPerJenis->push((object) [
                'jenis' => $jenis,
                'saldo' => $saldo,
                'total_setor' => $totalSetor,
                'total_tarik' => $totalTarik,
                'formatted_saldo' => 'Rp ' . number_format($saldo, 0, ',', '.')
            ]);
        }

        $totalSimpanan = $saldoPerJenis->sum('saldo');

        // Statistik Pembiayaan
        $pengajuanPembiayaan = PengajuanPembiayaan::where('anggota_id', $anggota->id)
                                                  ->with('jenisPembiayaan')
                                                  ->latest()
                                                  ->get();

        $totalPembiayaan = $pengajuanPembiayaan->whereIn('status', ['approved', 'cair', 'lunas'])
                                               ->sum('jumlah_pengajuan');

        $sisaPinjaman = 0;
        $totalMargin = 0;
        $activePembiayaan = 0;

        foreach ($pengajuanPembiayaan as $pengajuan) {
            if (in_array($pengajuan->status, ['cair'])) {
                $totalMargin += $pengajuan->jumlah_margin;
                $sisaPinjaman += $pengajuan->sisaTotal();
                $activePembiayaan++;
            }
        }

        // Angsuran berikutnya
        $angsuranBerikutnya = Angsuran::with('pengajuanPembiayaan')
                                     ->where('anggota_id', $anggota->id)
                                     ->whereIn('status', ['pending', 'terlambat'])
                                     ->orderBy('tanggal_jatuh_tempo', 'asc')
                                     ->first();

        // Recent Transaksi
        $recentTransaksi = TransaksiSimpanan::with('jenisSimpanan')
                                           ->where('anggota_id', $anggota->id)
                                           ->latest('tanggal_transaksi')
                                           ->limit(5)
                                           ->get();

        // Recent Pengajuan
        $recentPengajuan = $pengajuanPembiayaan->take(3);

        // Status Pengajuan Summary
        $statusPengajuan = [
            'draft' => $pengajuanPembiayaan->where('status', 'draft')->count(),
            'diajukan' => $pengajuanPembiayaan->where('status', 'diajukan')->count(),
            'verifikasi' => $pengajuanPembiayaan->where('status', 'verifikasi')->count(),
            'approved' => $pengajuanPembiayaan->where('status', 'approved')->count(),
            'cair' => $pengajuanPembiayaan->where('status', 'cair')->count(),
            'lunas' => $pengajuanPembiayaan->where('status', 'lunas')->count(),
            'rejected' => $pengajuanPembiayaan->where('status', 'rejected')->count(),
        ];

        // Angsuran Statistics
        $totalAngsuran = Angsuran::where('anggota_id', $anggota->id)->count();
        $angsuranTerbayar = Angsuran::where('anggota_id', $anggota->id)
                                   ->where('status', 'terbayar')
                                   ->count();
        $angsuranPending = Angsuran::where('anggota_id', $anggota->id)
                                   ->where('status', 'pending')
                                   ->count();
        $angsuranTerlambat = Angsuran::where('anggota_id', $anggota->id)
                                    ->where('status', 'terlambat')
                                    ->count();

        // Monthly Summary (6 bulan terakhir)
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
            'anggota',
            'saldoPerJenis',
            'totalSimpanan',
            'pengajuanPembiayaan',
            'totalPembiayaan',
            'sisaPinjaman',
            'totalMargin',
            'activePembiayaan',
            'angsuranBerikutnya',
            'recentTransaksi',
            'recentPengajuan',
            'statusPengajuan',
            'totalAngsuran',
            'angsuranTerbayar',
            'angsuranPending',
            'angsuranTerlambat',
            'monthlySummary',
            'bulanNunggak',
            'totalTunggakanWajib',
            'detailBulanNunggak'
        ));
    }
}