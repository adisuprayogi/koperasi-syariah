<?php

namespace App\Exports;

use App\Models\TransaksiSimpanan;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class PeriodeTransaksiExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $tipePeriode;
    protected $tanggal;
    protected $startDate;
    protected $endDate;
    protected $bulan;
    protected $tahun;
    protected $reportData;

    public function __construct($tipePeriode, $params = [])
    {
        $this->tipePeriode = $tipePeriode;

        switch ($tipePeriode) {
            case 'harian':
                $this->tanggal = $params['tanggal'] ?? now()->format('Y-m-d');
                break;
            case 'mingguan':
                $this->startDate = $params['start_date'] ?? now()->startOfWeek()->format('Y-m-d');
                $this->endDate = $params['end_date'] ?? now()->endOfWeek()->format('Y-m-d');
                break;
            case 'bulanan':
                $this->bulan = $params['bulan'] ?? now()->month;
                $this->tahun = $params['tahun'] ?? now()->year;
                break;
        }

        $this->reportData = $this->generatePeriodeReport($tipePeriode, $params);
    }

    /**
     * Generate periodic transaction report.
     *
     * @param  string  $tipePeriode
     * @param  array  $params
     * @return array
     */
    private function generatePeriodeReport($tipePeriode, $params)
    {
        $query = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'pengurus']);

        // Apply date filters based on period type
        switch ($tipePeriode) {
            case 'harian':
                $query->whereDate('tanggal_transaksi', $this->tanggal);
                break;
            case 'mingguan':
                $query->whereBetween('tanggal_transaksi', [$this->startDate, $this->endDate]);
                break;
            case 'bulanan':
                $query->whereMonth('tanggal_transaksi', $this->bulan)
                      ->whereYear('tanggal_transaksi', $this->tahun);
                break;
        }

        $transaksi = $query->orderBy('tanggal_transaksi', 'desc')->get();

        // Group by date for summary
        $groupByDate = $transaksi->groupBy(function($item) {
            return $item->tanggal_transaksi->format('Y-m-d');
        });

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
                'jenis' => $jenis,
                'total_setor' => $items->where('jenis_transaksi', 'setor')->sum('jumlah'),
                'total_tarik' => $items->where('jenis_transaksi', 'tarik')->sum('jumlah'),
                'jumlah_transaksi' => $items->count()
            ];
        }

        // Calculate totals
        $totalSetor = $transaksi->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = $transaksi->where('jenis_transaksi', 'tarik')->sum('jumlah');
        $netTransaksi = $totalSetor - $totalTarik;

        return [
            'transaksi' => $transaksi,
            'daily_summary' => $dailySummary,
            'summary_by_jenis' => $summaryByJenis,
            'total_setor' => $totalSetor,
            'total_tarik' => $totalTarik,
            'net_transaksi' => $netTransaksi
        ];
    }

    public function view(): View
    {
        $viewData = [
            'reportData' => $this->reportData,
            'tipePeriode' => $this->tipePeriode
        ];

        switch ($this->tipePeriode) {
            case 'harian':
                $viewData['tanggal'] = $this->tanggal;
                break;
            case 'mingguan':
                $viewData['startDate'] = $this->startDate;
                $viewData['endDate'] = $this->endDate;
                break;
            case 'bulanan':
                $viewData['bulan'] = $this->bulan;
                $viewData['tahun'] = $this->tahun;
                break;
        }

        return view('exports.periode_transaksi', $viewData);
    }

    public function title(): string
    {
        switch ($this->tipePeriode) {
            case 'harian':
                return 'Laporan Transaksi Harian';
            case 'mingguan':
                return 'Laporan Transaksi Mingguan';
            case 'bulanan':
                return 'Laporan Transaksi Bulanan';
            default:
                return 'Laporan Transaksi';
        }
    }

    public function styles($sheet)
    {
        return [
            // Header styles
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2563EB'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Sub-header styles
            3 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EFF6FF'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Table header styles
            5 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3B82F6'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
            // Data row styles
            'A6:H1000' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
            // Summary row styles
            'A' => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,  // No
            'B' => 15, // Tanggal
            'C' => 20, // Kode Transaksi
            'D' => 25, // Nama Anggota
            'E' => 15, // Jenis Simpanan
            'F' => 12, // Jenis Transaksi
            'G' => 18, // Jumlah
            'H' => 25, // Petugas
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $rowCount = count($this->reportData['transaksi']);

                // Set print area
                $sheet->getPageSetup()->setPrintArea('A1:H' . (5 + $rowCount + 2));

                // Set page orientation
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                // Set paper size
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

                // Set margins
                $sheet->getPageMargins()->setTop(0.5);
                $sheet->getPageMargins()->setRight(0.5);
                $sheet->getPageMargins()->setLeft(0.5);
                $sheet->getPageMargins()->setBottom(0.5);

                // Center on page
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setVerticalCentered(false);

                // Repeat rows at top
                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 5);

                // Format currency column
                $sheet->getStyle('G')->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
            },
        ];
    }
}