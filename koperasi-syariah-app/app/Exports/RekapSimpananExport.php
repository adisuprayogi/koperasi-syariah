<?php

namespace App\Exports;

use App\Models\JenisSimpanan;
use App\Models\TransaksiSimpanan;
use App\Models\Anggota;
use App\Models\Koperasi;
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

class RekapSimpananExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $jenisSimpananId;
    protected $reportData;
    protected $totalData;

    public function __construct($startDate = null, $endDate = null, $jenisSimpananId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->jenisSimpananId = $jenisSimpananId;
        $this->reportData = $this->generateRekapSimpanan($startDate, $endDate, $jenisSimpananId);
        $this->totalData = $this->calculateTotals();
    }

    /**
     * Generate rekap simpanan data.
     *
     * @param  string|null  $startDate
     * @param  string|null  $endDate
     * @param  int|null  $jenisSimpananId
     * @return array
     */
    private function generateRekapSimpanan($startDate = null, $endDate = null, $jenisSimpananId = null)
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
            $transaksiQuery = TransaksiSimpanan::where('jenis_simpanan_id', $jenis->id);

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

            // Count active members with this savings type
            $jumlahAnggota = TransaksiSimpanan::where('jenis_simpanan_id', $jenis->id)
                ->distinct('anggota_id')
                ->count('anggota_id');

            // Get average savings per member
            $rataRata = $jumlahAnggota > 0 ? $saldo / $jumlahAnggota : 0;

            $reportData[] = [
                'jenis' => $jenis,
                'total_setor' => $totalSetor,
                'total_tarik' => $totalTarik,
                'saldo' => $saldo,
                'jumlah_anggota' => $jumlahAnggota,
                'rata_rata' => $rataRata
            ];
        }

        return $reportData;
    }

    /**
     * Calculate total summary.
     *
     * @return array
     */
    private function calculateTotals()
    {
        $totalSetor = TransaksiSimpanan::where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = TransaksiSimpanan::where('jenis_transaksi', 'tarik')->sum('jumlah');
        $totalSaldo = $totalSetor - $totalTarik;
        $totalAnggota = Anggota::where('status_keanggotaan', 'aktif')->count();

        return [
            'total_setor' => $totalSetor,
            'total_tarik' => $totalTarik,
            'total_saldo' => $totalSaldo,
            'total_anggota' => $totalAnggota
        ];
    }

    public function view(): View
    {
        $koperasi = Koperasi::first();

        return view('exports.rekap_simpanan', [
            'koperasi' => $koperasi,
            'reportData' => $this->reportData,
            'totalData' => $this->totalData
        ]);
    }

    public function title(): string
    {
        return 'Rekap Simpanan';
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
            'A8:G100' => [
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
            'B' => 18, // Jumlah Anggota
            'C' => 20, // Total Setoran
            'D' => 20, // Total Penarikan
            'E' => 20, // Saldo
            'F' => 20, // Rata-rata
            'G' => 15, // % dari Total
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set print area
                $sheet->getPageSetup()->setPrintArea('A1:G' . (7 + count($this->reportData) + 2));

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
                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 7);
            },
        ];
    }
}