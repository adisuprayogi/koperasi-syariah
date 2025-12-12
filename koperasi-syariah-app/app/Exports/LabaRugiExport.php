<?php

namespace App\Exports;

use App\Models\Angsuran;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class LabaRugiExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    private $bulan;
    private $tahun;
    private $marginReceived;
    private $otherIncome;
    private $totalPendapatan;
    private $bebanOperasional;
    private $bebanAdministrasi;
    private $totalBeban;
    private $shuSebelumPajak;
    private $pajak;
    private $shuSetelahPajak;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;

        // Hitung data laporan laba rugi
        $this->marginReceived = Angsuran::whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->where('status', 'terbayar')
            ->sum('jumlah_margin');

        $this->otherIncome = 0; // Placeholder untuk pendapatan lainnya
        $this->totalPendapatan = $this->marginReceived + $this->otherIncome;

        $this->bebanOperasional = 0; // Placeholder
        $this->bebanAdministrasi = 0; // Placeholder
        $this->totalBeban = $this->bebanOperasional + $this->bebanAdministrasi;

        $this->shuSebelumPajak = $this->totalPendapatan - $this->totalBeban;
        $this->pajak = $this->shuSebelumPajak * 0.05; // Contoh 5%
        $this->shuSetelahPajak = $this->shuSebelumPajak - $this->pajak;
    }

    public function view(): View
    {
        return view('pengurus.laporan.export.laba_rugi', [
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'marginReceived' => $this->marginReceived,
            'otherIncome' => $this->otherIncome,
            'totalPendapatan' => $this->totalPendapatan,
            'bebanOperasional' => $this->bebanOperasional,
            'bebanAdministrasi' => $this->bebanAdministrasi,
            'totalBeban' => $this->totalBeban,
            'shuSebelumPajak' => $this->shuSebelumPajak,
            'pajak' => $this->pajak,
            'shuSetelahPajak' => $this->shuSetelahPajak,
        ]);
    }

    public function title(): string
    {
        $namaBulan = $this->getNamaBulan($this->bulan);
        return "LAPORAN LABA RUGI - {$namaBulan} {$this->tahun}";
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Style untuk header utama
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '059669'],
                    'endColor' => ['argb' => '059669'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],

            // Style untuk sub-header (Pendapatan)
            4 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '10B981'],
                    'endColor' => ['argb' => '10B981'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ],

            // Style untuk sub-header (Beban)
            9 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'EF4444'],
                    'endColor' => ['argb' => 'EF4444'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ],

            // Style untuk sub-header (SHU)
            15 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '8B5CF6'],
                    'endColor' => ['argb' => '8B5CF6'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ],

            // Style untuk total row
            18 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FEF3C7'],
                    'endColor' => ['argb' => 'FEF3C7'],
                ],
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,      // No
            'B' => 25,     // Uraian
            'C' => 20,     // Jumlah
            'D' => 5,      // %
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Apply borders untuk semua data cells
                $sheet->getStyle('A4:D18')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Merge cells untuk header utama
                $sheet->mergeCells('A1:D1');

                // Merge cells untuk sub-headers
                $sheet->mergeCells('A4:B4');
                $sheet->mergeCells('A9:B9');
                $sheet->mergeCells('A15:B15');

                // Merge cells untuk total row
                $sheet->mergeCells('A18:A18');

                // Apply number format untuk currency columns
                $sheet->getStyle('C5:C18')->getNumberFormat()->setFormatCode('#,##0.00');

                // Apply percentage format untuk persen column
                $sheet->getStyle('D5:D18')->getNumberFormat()->setFormatCode('0.00%');

                // Set auto-width for better readability
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);

                // Add footer info
                $lastRow = $sheet->getHighestRow();
                $sheet->setCellValue('A' . ($lastRow + 2), 'Dicetak pada: ' . now()->format('d F Y H:i:s'));
                $sheet->setCellValue('A' . ($lastRow + 3), 'Koperasi Syariah');
                $sheet->getStyle('A' . ($lastRow + 2) . ':D' . ($lastRow + 3))->applyFromArray([
                    'font' => [
                        'size' => 9,
                        'italic' => true,
                        'color' => ['argb' => '6B7280'],
                    ],
                ]);
            },
        ];
    }

    private function getNamaBulan($bulan)
    {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $namaBulan[$bulan] ?? 'Tidak Diketahui';
    }
}