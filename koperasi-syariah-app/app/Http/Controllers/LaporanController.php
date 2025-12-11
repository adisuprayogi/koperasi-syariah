<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiSimpanan;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\PengajuanPembiayaan;
use App\Models\Angsuran;
use App\Models\Transaksi;
use App\Models\Koperasi;
use App\Exports\SimpananPerAnggotaExport;
use App\Exports\RekapSimpananExport;
use App\Exports\PembiayaanPerAnggotaExport;
use App\Exports\SimpananExport;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

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
     * Generate simpanan per anggota report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function simpananPerAnggota(Request $request)
    {
        $request->validate([
            'anggota_id' => 'nullable|exists:anggota,id'
        ]);

        $anggotaId = $request->get('anggota_id');
        $anggota = null;
        $reportData = [];

        if ($anggotaId) {
            $anggota = Anggota::find($anggotaId);
            $reportData = $this->generateSimpananReportForAnggota($anggotaId);
        }

        $listAnggota = Anggota::where('status_keanggotaan', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        return view('pengurus.laporan.simpanan_per_anggota', compact(
            'reportData',
            'anggota',
            'listAnggota'
        ));
    }

    /**
     * Print simpanan per anggota report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function printSimpananPerAnggota(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id'
        ]);

        $anggotaId = $request->get('anggota_id');
        $anggota = Anggota::find($anggotaId);
        $reportData = $this->generateSimpananReportForAnggota($anggotaId);

        return view('pengurus.laporan.print.simpanan_per_anggota', compact(
            'reportData',
            'anggota'
        ));
    }

    /**
     * Generate pembiayaan per anggota report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pembiayaanPerAnggota(Request $request)
    {
        $request->validate([
            'anggota_id' => 'nullable|exists:anggota,id',
            'status' => 'nullable|in:all,cair,lunas,approved'
        ]);

        $anggotaId = $request->get('anggota_id');
        $status = $request->get('status', 'all');
        $anggota = null;
        $reportData = [];

        if ($anggotaId) {
            $anggota = Anggota::find($anggotaId);
            $reportData = $this->generatePembiayaanReportForAnggota($anggotaId, $status);
        }

        $listAnggota = Anggota::where('status_keanggotaan', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        return view('pengurus.laporan.pembiayaan_per_anggota', compact(
            'reportData',
            'anggota',
            'status',
            'listAnggota'
        ));
    }

    /**
     * Generate laba rugi report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function labaRugi(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        // Pendapatan - Margin dari pembiayaan yang dibayar
        $marginReceived = Angsuran::whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->where('status', 'terbayar')
            ->sum('jumlah_margin');

        // Pendapatan lainnya (bisa ditambahkan nanti)
        $otherIncome = 0;

        $totalPendapatan = $marginReceived + $otherIncome;

        // Beban operasional (contoh, bisa disesuaikan)
        $bebanOperasional = 0; // Placeholder
        $bebanAdministrasi = 0; // Placeholder

        $totalBeban = $bebanOperasional + $bebanAdministrasi;

        // SHU sebelum pajak
        $shuSebelumPajak = $totalPendapatan - $totalBeban;

        // Pajak (jika ada, untuk koperasi biasanya ada keringanan)
        $pajak = $shuSebelumPajak * 0.05; // Contoh 5%
        $shuSetelahPajak = $shuSebelumPajak - $pajak;

        return view('pengurus.laporan.laba_rugi', compact(
            'bulan',
            'tahun',
            'marginReceived',
            'otherIncome',
            'totalPendapatan',
            'bebanOperasional',
            'bebanAdministrasi',
            'totalBeban',
            'shuSebelumPajak',
            'pajak',
            'shuSetelahPajak'
        ));
    }

    /**
     * Generate neraca report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function neraca(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        // Aset
        // Kas - Total simpanan anggota
        $totalSimpanan = $this->calculateTotalSimpanan($tanggal);

        // Piutang anggota - Sisa pembiayaan yang belum dibayar
        $totalPiutang = PengajuanPembiayaan::where('status', 'cair')
            ->whereDate('tanggal_cair', '<=', $tanggal)
            ->with('angsurans')
            ->get()
            ->sum(function($pengajuan) {
                return $pengajuan->sisaTotal();
            });

        $totalAset = $totalSimpanan + $totalPiutang;

        // Kewajiban
        // Simpanan anggota (kewajiban koperasi kepada anggota)
        $kewajibanSimpanan = $totalSimpanan;

        // Beban lainnya (placeholder)
        $kewajibanLainnya = 0;

        $totalKewajiban = $kewajibanSimpanan + $kewajibanLainnya;

        // Ekuitas
        // Modal awal (placeholder)
        $modalAwal = 0;

        // SHU tahun berjalan
        $shuBerjalan = $this->calculateSHUBerjalan($tanggal);

        $totalEkuitas = $modalAwal + $shuBerjalan;

        // Total kewajiban + ekuitas harus sama dengan total aset
        $totalKewajibanEkuitas = $totalKewajiban + $totalEkuitas;

        return view('pengurus.laporan.neraca', compact(
            'tanggal',
            'totalSimpanan',
            'totalPiutang',
            'totalAset',
            'kewajibanSimpanan',
            'kewajibanLainnya',
            'totalKewajiban',
            'modalAwal',
            'shuBerjalan',
            'totalEkuitas',
            'totalKewajibanEkuitas'
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
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $jenisSimpananId = $request->get('jenis_simpanan_id');
        $format = $request->get('format', 'excel'); // excel or pdf

        try {
            $filename = 'Laporan_Simpanan_' . date('Y-m-d_H-i-s');

            if ($format === 'pdf') {
                // Export to PDF
                $query = TransaksiSimpanan::with(['anggota', 'jenisSimpanan']);

                if ($startDate) {
                    $query->whereDate('tanggal_transaksi', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('tanggal_transaksi', '<=', $endDate);
                }
                if ($jenisSimpananId) {
                    $query->where('jenis_simpanan_id', $jenisSimpananId);
                }

                $transaksi = $query->orderBy('tanggal_transaksi', 'desc')->get();
                $totalSimpananWajib = $transaksi->where('jenis_simpanan_id', 1)->sum('jumlah');
                $totalSimpananSukarela = $transaksi->where('jenis_simpanan_id', 2)->sum('jumlah');
                $totalSimpananWajibBulanan = $transaksi->where('jenis_simpanan_id', 3)->sum('jumlah');

                $data = [
                    'transaksi' => $transaksi,
                    'totalSimpananWajib' => $totalSimpananWajib,
                    'totalSimpananSukarela' => $totalSimpananSukarela,
                    'totalSimpananWajibBulanan' => $totalSimpananWajibBulanan,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'jenisSimpanan' => $jenisSimpananId ? JenisSimpanan::find($jenisSimpananId) : null,
                ];

                $pdf = PDF::loadView('exports.simpanan_pdf', $data);
                $pdf->setPaper('A4', 'landscape');

                return $pdf->download($filename . '.pdf');
            } else {
                // Export to Excel
                return Excel::download(new SimpananExport($startDate, $endDate, $jenisSimpananId), $filename . '.xlsx');
            }

        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export laporan: ' . $e->getMessage());
        }
    }

    /**
     * Generate simpanan report for specific anggota.
     *
     * @param  int  $anggotaId
     * @return array
     */
    private function generateSimpananReportForAnggota($anggotaId)
    {
        $jenisSimpanan = JenisSimpanan::where('status', 1)->get();
        $reportData = [];

        foreach ($jenisSimpanan as $jenis) {
            $totalSetor = TransaksiSimpanan::where('anggota_id', $anggotaId)
                ->where('jenis_simpanan_id', $jenis->id)
                ->where('jenis_transaksi', 'setor')
                ->sum('jumlah');

            $totalTarik = TransaksiSimpanan::where('anggota_id', $anggotaId)
                ->where('jenis_simpanan_id', $jenis->id)
                ->where('jenis_transaksi', 'tarik')
                ->sum('jumlah');

            $saldo = $totalSetor - $totalTarik;

            // Get recent transactions
            $recentTransaksi = TransaksiSimpanan::where('anggota_id', $anggotaId)
                ->where('jenis_simpanan_id', $jenis->id)
                ->with('pengurus')
                ->latest('tanggal_transaksi')
                ->limit(5)
                ->get();

            $reportData[] = [
                'jenis' => $jenis,
                'total_setor' => $totalSetor,
                'total_tarik' => $totalTarik,
                'saldo' => $saldo,
                'recent_transaksi' => $recentTransaksi
            ];
        }

        return $reportData;
    }

    /**
     * Generate pembiayaan report for specific anggota.
     *
     * @param  int  $anggotaId
     * @param  string  $status
     * @return array
     */
    private function generatePembiayaanReportForAnggota($anggotaId, $status)
    {
        $query = PengajuanPembiayaan::where('anggota_id', $anggotaId)
            ->with('jenisPembiayaan', 'angsurans');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $pengajuan = $query->latest('created_at')->get();

        $reportData = [];
        $totalPlafond = 0;
        $totalMargin = 0;
        $totalPinjaman = 0;
        $totalDibayar = 0;
        $totalSisa = 0;

        foreach ($pengajuan as $p) {
            $totalPlafond += $p->jumlah_pengajuan;
            $totalMargin += $p->jumlah_margin;
            $totalPinjaman += ($p->jumlah_pengajuan + $p->jumlah_margin);
            $totalDibayar += $p->totalDibayar();
            $totalSisa += $p->sisaTotal();

            // Get recent installments
            $recentAngsuran = Angsuran::where('pengajuan_pembiayaan_id', $p->id)
                ->where('status', 'terbayar')
                ->with('transaksi')
                ->latest('tanggal_bayar')
                ->limit(3)
                ->get();

            $reportData[] = [
                'pengajuan' => $p,
                'recent_angsuran' => $recentAngsuran
            ];
        }

        return [
            'pengajuan' => $reportData,
            'total_plafond' => $totalPlafond,
            'total_margin' => $totalMargin,
            'total_pinjaman' => $totalPinjaman,
            'total_dibayar' => $totalDibayar,
            'total_sisa' => $totalSisa
        ];
    }

    /**
     * Calculate total simpanan.
     *
     * @param  string  $tanggal
     * @return float
     */
    private function calculateTotalSimpanan($tanggal)
    {
        return TransaksiSimpanan::whereDate('tanggal_transaksi', '<=', $tanggal)
            ->selectRaw('SUM(CASE WHEN jenis_transaksi = "setor" THEN jumlah ELSE 0 END) as total_setor,
                         SUM(CASE WHEN jenis_transaksi = "tarik" THEN jumlah ELSE 0 END) as total_tarik')
            ->first()
            ->total_setor - TransaksiSimpanan::whereDate('tanggal_transaksi', '<=', $tanggal)
            ->where('jenis_transaksi', 'tarik')
            ->sum('jumlah');
    }

    /**
     * Calculate SHU for current year.
     *
     * @param  string  $tanggal
     * @return float
     */
    private function calculateSHUBerjalan($tanggal)
    {
        $year = date('Y', strtotime($tanggal));

        // Margin received from all installments this year
        $marginReceived = Angsuran::whereYear('tanggal_bayar', $year)
            ->where('status', 'terbayar')
            ->sum('jumlah_margin');

        // Placeholder for other income and expenses
        $otherIncome = 0;
        $expenses = 0;

        return $marginReceived + $otherIncome - $expenses;
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
            case 'laba-rugi':
                return $this->printLabaRugi($request);
            case 'neraca':
                return $this->printNeraca($request);
            case 'simpanan-wajib':
                return $this->printSimpananWajib($request);
            case 'simpanan-per-anggota':
                return $this->printSimpananPerAnggota($request);
            case 'pembiayaan-per-anggota':
                return $this->printPembiayaanPerAnggota($request);
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

    /**
     * Print pembiayaan per anggota report.
     */
    private function printPembiayaanPerAnggota(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'status' => 'nullable|in:all,cair,lunas,approved'
        ]);

        $anggotaId = $request->get('anggota_id');
        $status = $request->get('status', 'all');
        $anggota = Anggota::find($anggotaId);
        $reportData = $this->generatePembiayaanReportForAnggota($anggotaId, $status);

        return view('pengurus.laporan.print.pembiayaan_per_anggota', compact(
            'reportData',
            'anggota',
            'status'
        ));
    }

    /**
     * Print laba rugi report.
     */
    private function printLabaRugi(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        // Pendapatan - Margin dari pembiayaan yang dibayar
        $marginReceived = Angsuran::whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->where('status', 'terbayar')
            ->sum('jumlah_margin');

        // Pendapatan lainnya (bisa ditambahkan nanti)
        $otherIncome = 0;

        $totalPendapatan = $marginReceived + $otherIncome;

        // Beban operasional (contoh, bisa disesuaikan)
        $bebanOperasional = 0; // Placeholder
        $bebanAdministrasi = 0; // Placeholder

        $totalBeban = $bebanOperasional + $bebanAdministrasi;

        // SHU sebelum pajak
        $shuSebelumPajak = $totalPendapatan - $totalBeban;

        // Pajak (jika ada, untuk koperasi biasanya ada keringanan)
        $pajak = $shuSebelumPajak * 0.05; // Contoh 5%
        $shuSetelahPajak = $shuSebelumPajak - $pajak;

        return view('pengurus.laporan.print.laba_rugi', compact(
            'bulan',
            'tahun',
            'marginReceived',
            'otherIncome',
            'totalPendapatan',
            'bebanOperasional',
            'bebanAdministrasi',
            'totalBeban',
            'shuSebelumPajak',
            'pajak',
            'shuSetelahPajak'
        ));
    }

    /**
     * Print neraca report.
     */
    private function printNeraca(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));

        // Aset
        // Kas - Total simpanan anggota
        $totalSimpanan = $this->calculateTotalSimpanan($tanggal);

        // Piutang anggota - Sisa pembiayaan yang belum dibayar
        $totalPiutang = PengajuanPembiayaan::where('status', 'cair')
            ->whereDate('tanggal_cair', '<=', $tanggal)
            ->with('angsurans')
            ->get()
            ->sum(function($pengajuan) {
                return $pengajuan->sisaTotal();
            });

        $totalAset = $totalSimpanan + $totalPiutang;

        // Kewajiban
        // Simpanan anggota (kewajiban koperasi kepada anggota)
        $kewajibanSimpanan = $totalSimpanan;

        // Ekuitas
        // Modal awal (contoh, bisa disesuaikan)
        $modalAwal = 0; // Placeholder

        // SHU berjalan
        $shuBerjalan = $this->calculateSHUBerjalan($tanggal);

        $totalEkuitas = $modalAwal + $shuBerjalan;

        $totalKewajibanEkuitas = $kewajibanSimpanan + $totalEkuitas;

        return view('pengurus.laporan.print.neraca', compact(
            'tanggal',
            'totalSimpanan',
            'totalPiutang',
            'totalAset',
            'kewajibanSimpanan',
            'modalAwal',
            'shuBerjalan',
            'totalEkuitas',
            'totalKewajibanEkuitas'
        ));
    }

    /**
     * Export Simpanan Per Anggota to Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportSimpananPerAnggota(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'jenis_simpanan_id' => 'nullable|exists:jenis_simpanan,id'
        ]);

        $anggotaId = $request->get('anggota_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $jenisSimpananId = $request->get('jenis_simpanan_id');

        $anggota = Anggota::find($anggotaId);

        $filename = 'Laporan_Simpanan_' . str_replace(' ', '_', $anggota->nama_lengkap) . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new SimpananPerAnggotaExport($anggotaId, $startDate, $endDate, $jenisSimpananId), $filename);
    }

    /**
     * Export Rekap Simpanan to Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportRekapSimpanan(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'jenis_simpanan_id' => 'nullable|exists:jenis_simpanan,id'
        ]);

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $jenisSimpananId = $request->get('jenis_simpanan_id');

        $filename = 'Rekap_Simpanan_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new RekapSimpananExport($startDate, $endDate, $jenisSimpananId), $filename);
    }

    /**
     * Export Pembiayaan Per Anggota to Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportPembiayaanPerAnggota(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'status' => 'nullable|in:all,cair,lunas,approved'
        ]);

        $anggotaId = $request->get('anggota_id');
        $status = $request->get('status', 'all');
        $anggota = Anggota::find($anggotaId);

        $filename = 'Laporan_Pembiayaan_' . str_replace(' ', '_', $anggota->nama_lengkap) . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new PembiayaanPerAnggotaExport($anggotaId, $status), $filename);
    }
}
