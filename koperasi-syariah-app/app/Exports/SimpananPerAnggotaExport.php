<?php

namespace App\Exports;

use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\TransaksiSimpanan;
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

class SimpananPerAnggotaExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $anggotaId;
    protected $startDate;
    protected $endDate;
    protected $jenisSimpananId;
    protected $anggota;
    protected $reportData;

    public function __construct($anggotaId, $startDate = null, $endDate = null, $jenisSimpananId = null)
    {
        $this->anggotaId = $anggotaId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->jenisSimpananId = $jenisSimpananId;
        $this->anggota = Anggota::find($anggotaId);
        $this->reportData = $this->generateSimpananReportForAnggota($anggotaId, $startDate, $endDate, $jenisSimpananId);
    }

    /**
     * Generate simpanan report for specific anggota.
     *
     * @param  int  $anggotaId
     * @param  string|null  $startDate
     * @param  string|null  $endDate
     * @param  int|null  $jenisSimpananId
     * @return array
     */
    private function generateSimpananReportForAnggota($anggotaId, $startDate = null, $endDate = null, $jenisSimpananId = null)
    {
        // Build query for jenis simpanan
        $jenisSimpananQuery = JenisSimpanan::where('status', 1);

        // Filter by jenis simpanan if specified
        if ($jenisSimpananId) {
            $jenisSimpananQuery->where('id', $jenisSimpananId);
        }

        $jenisSimpanan = $jenisSimpananQuery->get();
        $reportData = [];

        foreach ($jenisSimpanan as $jenis) {
            // Build query for transaksi
            $transaksiQuery = TransaksiSimpanan::where('anggota_id', $anggotaId)
                ->where('jenis_simpanan_id', $jenis->id);

            // Apply date filters
            if ($startDate) {
                $transaksiQuery->whereDate('tanggal_transaksi', '>=', $startDate);
            }
            if ($endDate) {
                $transaksiQuery->whereDate('tanggal_transaksi', '<=', $endDate);
            }

            $totalSetor = $transaksiQuery->where('jenis_transaksi', 'setor')->sum('jumlah');
            $totalTarik = $transaksiQuery->where('jenis_transaksi', 'tarik')->sum('jumlah');

            $saldo = $totalSetor - $totalTarik;

            $reportData[] = [
                'jenis' => $jenis,
                'total_setor' => $totalSetor,
                'total_tarik' => $totalTarik,
                'saldo' => $saldo
            ];
        }

        return $reportData;
    }

    public function view(): View
    {
        return view('exports.simpanan_per_anggota', [
            'anggota' => $this->anggota,
            'reportData' => $this->reportData,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'jenisSimpananId' => $this->jenisSimpananId
        ]);
    }

    public function title(): string
    {
        return 'Laporan Simpanan';
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
                    'startColor' => ['rgb' => '22C55E'],
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
                    'startColor' => ['rgb' => 'F3F4F6'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Table header styles
            7 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '16A34A'],
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
            'A8:D100' => [
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
            'A' => 25, // Jenis Simpanan
            'B' => 20, // Total Setoran
            'C' => 20, // Total Penarikan
            'D' => 20, // Saldo
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set print area
                $sheet->getPageSetup()->setPrintArea('A1:D' . (7 + count($this->reportData) + 2));

                // Set page orientation
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);

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
                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 7);
            },
        ];
    }
}