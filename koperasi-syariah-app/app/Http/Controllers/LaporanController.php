<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiSimpanan;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Display the main reports page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pengurus.laporan.index');
    }

    /**
     * Generate daily transaction report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function harian(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        $transaksi = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'pengurus'])
            ->whereDate('tanggal_transaksi', $tanggal)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        // Group by jenis simpanan
        $groupByJenis = $transaksi->groupBy('jenis_simpanan_id');
        $summaryByJenis = [];

        foreach ($groupByJenis as $jenisId => $items) {
            $jenis = JenisSimpanan::find($jenisId);
            $summaryByJenis[] = [
                'jenis' => $jenis->nama_simpanan,
                'total_setor' => $items->where('jenis_transaksi', 'setor')->sum('jumlah'),
                'total_tarik' => $items->where('jenis_transaksi', 'tarik')->sum('jumlah'),
                'jumlah_transaksi' => $items->count()
            ];
        }

        // Calculate totals
        $totalSetor = $transaksi->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = $transaksi->where('jenis_transaksi', 'tarik')->sum('jumlah');
        $netTransaksi = $totalSetor - $totalTarik;

        return view('pengurus.laporan.harian', compact(
            'transaksi',
            'tanggal',
            'summaryByJenis',
            'totalSetor',
            'totalTarik',
            'netTransaksi'
        ));
    }

    /**
     * Generate weekly transaction report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mingguan(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfWeek()->format('Y-m-d'));

        $transaksi = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'pengurus'])
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        // Group by date
        $groupByDate = $transaksi->groupBy(function($item) {
            return $item->tanggal_transaksi->format('Y-m-d');
        });

        // Daily summary
        $dailySummary = [];
        foreach ($groupByDate as $date => $items) {
            $dailySummary[] = [
                'tanggal' => $date,
                'total_setor' => $items->where('jenis_transaksi', 'setor')->sum('jumlah'),
                'total_tarik' => $items->where('jenis_transaksi', 'tarik')->sum('jumlah'),
                'jumlah_transaksi' => $items->count()
            ];
        }

        // Group by jenis simpanan
        $groupByJenis = $transaksi->groupBy('jenis_simpanan_id');
        $summaryByJenis = [];

        foreach ($groupByJenis as $jenisId => $items) {
            $jenis = JenisSimpanan::find($jenisId);
            $summaryByJenis[] = [
                'jenis' => $jenis->nama_simpanan,
                'total_setor' => $items->where('jenis_transaksi', 'setor')->sum('jumlah'),
                'total_tarik' => $items->where('jenis_transaksi', 'tarik')->sum('jumlah'),
                'jumlah_transaksi' => $items->count()
            ];
        }

        // Calculate totals
        $totalSetor = $transaksi->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = $transaksi->where('jenis_transaksi', 'tarik')->sum('jumlah');
        $netTransaksi = $totalSetor - $totalTarik;

        return view('pengurus.laporan.mingguan', compact(
            'transaksi',
            'startDate',
            'endDate',
            'dailySummary',
            'summaryByJenis',
            'totalSetor',
            'totalTarik',
            'netTransaksi'
        ));
    }

    /**
     * Generate monthly transaction report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulanan(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $transaksi = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'pengurus'])
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        // Group by date
        $groupByDate = $transaksi->groupBy(function($item) {
            return $item->tanggal_transaksi->format('Y-m-d');
        });

        // Daily summary
        $dailySummary = [];
        foreach ($groupByDate as $date => $items) {
            $dailySummary[] = [
                'tanggal' => $date,
                'total_setor' => $items->where('jenis_transaksi', 'setor')->sum('jumlah'),
                'total_tarik' => $items->where('jenis_transaksi', 'tarik')->sum('jumlah'),
                'jumlah_transaksi' => $items->count()
            ];
        }

        // Group by jenis simpanan
        $groupByJenis = $transaksi->groupBy('jenis_simpanan_id');
        $summaryByJenis = [];

        foreach ($groupByJenis as $jenisId => $items) {
            $jenis = JenisSimpanan::find($jenisId);
            $summaryByJenis[] = [
                'jenis' => $jenis->nama_simpanan,
                'total_setor' => $items->where('jenis_transaksi', 'setor')->sum('jumlah'),
                'total_tarik' => $items->where('jenis_transaksi', 'tarik')->sum('jumlah'),
                'jumlah_transaksi' => $items->count()
            ];
        }

        // Group by anggota
        $groupByAnggota = $transaksi->groupBy('anggota_id');
        $summaryByAnggota = [];

        foreach ($groupByAnggota as $anggotaId => $items) {
            $anggota = Anggota::find($anggotaId);
            $summaryByAnggota[] = [
                'anggota' => $anggota,
                'total_setor' => $items->where('jenis_transaksi', 'setor')->sum('jumlah'),
                'total_tarik' => $items->where('jenis_transaksi', 'tarik')->sum('jumlah'),
                'jumlah_transaksi' => $items->count()
            ];
        }

        // Sort by total setor descending
        usort($summaryByAnggota, function($a, $b) {
            return $b['total_setor'] <=> $a['total_setor'];
        });

        // Calculate totals
        $totalSetor = $transaksi->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = $transaksi->where('jenis_transaksi', 'tarik')->sum('jumlah');
        $netTransaksi = $totalSetor - $totalTarik;

        // Compare with previous month
        $prevMonth = $bulan == 1 ? 12 : $bulan - 1;
        $prevYear = $bulan == 1 ? $tahun - 1 : $tahun;

        $prevTransaksi = TransaksiSimpanan::whereMonth('tanggal_transaksi', $prevMonth)
            ->whereYear('tanggal_transaksi', $prevYear)
            ->get();

        $prevTotalSetor = $prevTransaksi->where('jenis_transaksi', 'setor')->sum('jumlah');
        $prevTotalTarik = $prevTransaksi->where('jenis_transaksi', 'tarik')->sum('jumlah');

        $growthSetor = $prevTotalSetor > 0 ? (($totalSetor - $prevTotalSetor) / $prevTotalSetor) * 100 : 0;
        $growthTarik = $prevTotalTarik > 0 ? (($totalTarik - $prevTotalTarik) / $prevTotalTarik) * 100 : 0;

        return view('pengurus.laporan.bulanan', compact(
            'transaksi',
            'bulan',
            'tahun',
            'dailySummary',
            'summaryByJenis',
            'summaryByAnggota',
            'totalSetor',
            'totalTarik',
            'netTransaksi',
            'prevTotalSetor',
            'prevTotalTarik',
            'growthSetor',
            'growthTarik'
        ));
    }

    /**
     * Generate simpanan wajib report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function simpananWajib(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        // Get jenis simpanan wajib
        $jenisWajib = JenisSimpanan::where('tipe_simpanan', 'wajib')
            ->where('status', 1)
            ->first();

        if (!$jenisWajib) {
            return redirect()->back()->with('error', 'Jenis simpanan wajib tidak ditemukan!');
        }

        // Get all active anggota
        $anggotaAktif = Anggota::where('status_keanggotaan', 'aktif')->get();

        $reportData = [];
        $totalTerhutang = 0;
        $totalTerbayar = 0;
        $totalTunggakan = 0;

        foreach ($anggotaAktif as $anggota) {
            // Check if simpanan wajib for this month exists
            $transaksi = TransaksiSimpanan::where('anggota_id', $anggota->id)
                ->where('jenis_simpanan_id', $jenisWajib->id)
                ->whereMonth('tanggal_transaksi', $bulan)
                ->whereYear('tanggal_transaksi', $tahun)
                ->where('jenis_transaksi', 'setor')
                ->first();

            $terbayar = $transaksi ? $transaksi->jumlah : 0;
            $terhutang = $jenisWajib->minimal_setor;
            $tunggakan = max(0, $terhutang - $terbayar);

            $totalTerhutang += $terhutang;
            $totalTerbayar += $terbayar;
            $totalTunggakan += $tunggakan;

            $reportData[] = [
                'anggota' => $anggota,
                'terhutang' => $terhutang,
                'terbayar' => $terbayar,
                'tunggakan' => $tunggakan,
                'status' => $tunggakan > 0 ? 'Belum Lunas' : 'Lunas',
                'transaksi' => $transaksi
            ];
        }

        // Calculate statistics
        $persentasePembayaran = $totalTerhutang > 0 ? ($totalTerbayar / $totalTerhutang) * 100 : 0;
        $jumlahLunas = collect($reportData)->where('status', 'Lunas')->count();
        $jumlahBelumLunas = collect($reportData)->where('status', 'Belum Lunas')->count();

        return view('pengurus.laporan.simpanan_wajib', compact(
            'reportData',
            'bulan',
            'tahun',
            'jenisWajib',
            'totalTerhutang',
            'totalTerbayar',
            'totalTunggakan',
            'persentasePembayaran',
            'jumlahLunas',
            'jumlahBelumLunas'
        ));
    }

    /**
     * Export report to Excel (placeholder for future implementation).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request, $type)
    {
        // TODO: Implement Excel export functionality
        return redirect()->back()->with('info', 'Fitur export akan segera tersedia');
    }

    /**
     * Print report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request, $type)
    {
        switch ($type) {
            case 'harian':
                return $this->printHarian($request);
            case 'mingguan':
                return $this->printMingguan($request);
            case 'bulanan':
                return $this->printBulanan($request);
            case 'simpanan-wajib':
                return $this->printSimpananWajib($request);
            default:
                return redirect()->back()->with('error', 'Jenis laporan tidak valid');
        }
    }

    /**
     * Print daily report.
     */
    private function printHarian(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        $transaksi = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'pengurus'])
            ->whereDate('tanggal_transaksi', $tanggal)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $totalSetor = $transaksi->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = $transaksi->where('jenis_transaksi', 'tarik')->sum('jumlah');
        $netTransaksi = $totalSetor - $totalTarik;

        return view('pengurus.laporan.print.harian', compact(
            'transaksi',
            'tanggal',
            'totalSetor',
            'totalTarik',
            'netTransaksi'
        ));
    }

    /**
     * Print weekly report.
     */
    private function printMingguan(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfWeek()->format('Y-m-d'));

        $transaksi = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'pengurus'])
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $totalSetor = $transaksi->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = $transaksi->where('jenis_transaksi', 'tarik')->sum('jumlah');
        $netTransaksi = $totalSetor - $totalTarik;

        return view('pengurus.laporan.print.mingguan', compact(
            'transaksi',
            'startDate',
            'endDate',
            'totalSetor',
            'totalTarik',
            'netTransaksi'
        ));
    }

    /**
     * Print monthly report.
     */
    private function printBulanan(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $transaksi = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'pengurus'])
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $totalSetor = $transaksi->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = $transaksi->where('jenis_transaksi', 'tarik')->sum('jumlah');
        $netTransaksi = $totalSetor - $totalTarik;

        return view('pengurus.laporan.print.bulanan', compact(
            'transaksi',
            'bulan',
            'tahun',
            'totalSetor',
            'totalTarik',
            'netTransaksi'
        ));
    }

    /**
     * Print simpanan wajib report.
     */
    private function printSimpananWajib(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $jenisWajib = JenisSimpanan::where('tipe_simpanan', 'wajib')
            ->where('status', 1)
            ->first();

        $anggotaAktif = Anggota::where('status_keanggotaan', 'aktif')->get();

        $reportData = [];
        $totalTerhutang = 0;
        $totalTerbayar = 0;
        $totalTunggakan = 0;

        foreach ($anggotaAktif as $anggota) {
            $transaksi = TransaksiSimpanan::where('anggota_id', $anggota->id)
                ->where('jenis_simpanan_id', $jenisWajib->id)
                ->whereMonth('tanggal_transaksi', $bulan)
                ->whereYear('tanggal_transaksi', $tahun)
                ->where('jenis_transaksi', 'setor')
                ->first();

            $terbayar = $transaksi ? $transaksi->jumlah : 0;
            $terhutang = $jenisWajib->minimal_setor;
            $tunggakan = max(0, $terhutang - $terbayar);

            $totalTerhutang += $terhutang;
            $totalTerbayar += $terbayar;
            $totalTunggakan += $tunggakan;

            $reportData[] = [
                'anggota' => $anggota,
                'terhutang' => $terhutang,
                'terbayar' => $terbayar,
                'tunggakan' => $tunggakan,
                'status' => $tunggakan > 0 ? 'Belum Lunas' : 'Lunas',
                'transaksi' => $transaksi
            ];
        }

        $persentasePembayaran = $totalTerhutang > 0 ? ($totalTerbayar / $totalTerhutang) * 100 : 0;

        return view('pengurus.laporan.print.simpanan_wajib', compact(
            'reportData',
            'bulan',
            'tahun',
            'jenisWajib',
            'totalTerhutang',
            'totalTerbayar',
            'totalTunggakan',
            'persentasePembayaran'
        ));
    }
}
