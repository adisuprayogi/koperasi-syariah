<?php

namespace App\Exports;

use App\Models\Angsuran;
use App\Models\Anggota;
use App\Models\PengajuanPembiayaan;
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

class TunggakanExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $tanggalLaporan;
    protected $status;
    protected $reportData;

    public function __construct($tanggalLaporan = null, $status = 'all')
    {
        $this->tanggalLaporan = $tanggalLaporan ?: now()->format('Y-m-d');
        $this->status = $status;
        $this->reportData = $this->generateTunggakanReport($this->tanggalLaporan, $status);
    }

    /**
     * Generate tunggakan report.
     *
     * @param  string  $tanggalLaporan
     * @param  string  $status
     * @return array
     */
    private function generateTunggakanReport($tanggalLaporan, $status)
    {
        $query = Angsuran::with(['anggota', 'pengajuanPembiayaan', 'pengajuanPembiayaan.jenisPembiayaan'])
            ->where('tanggal_jatuh_tempo', '<=', $tanggalLaporan)
            ->where('status', '!=', 'terbayar');

        // Filter by status if specified
        if ($status !== 'all') {
            switch ($status) {
                case 'pending':
                    $query->where('status', 'pending');
                    break;
                case 'terlambat':
                    $query->where('status', 'terlambat');
                    break;
                case 'overdue':
                    $query->overdue();
                    break;
            }
        }

        $angsurans = $query->orderBy('tanggal_jatuh_tempo')->get();

        $reportData = [];
        $totalPokok = 0;
        $totalMargin = 0;
        $totalDenda = 0;
        $totalGrand = 0;

        foreach ($angsurans as $angsuran) {
            // Hitung denda berdasarkan keterlambatan
            $hariTerlambat = 0;
            $denda = 0;

            if ($angsuran->tanggal_jatuh_tempo < $tanggalLaporan) {
                $hariTerlambat = Carbon::parse($tanggalLaporan)->diffInDays($angsuran->tanggal_jatuh_tempo);

                // Hitung denda (biasanya 0.1% per hari dari total angsuran)
                if ($angsuran->persentase_denda > 0) {
                    $denda = ($angsuran->jumlah_angsuran * $angsuran->persentase_denda / 100) * $hariTerlambat;
                } else {
                    // Default denda 0.1% per hari
                    $denda = ($angsuran->jumlah_angsuran * 0.1 / 100) * $hariTerlambat;
                }
            }

            $totalAngsuran = $angsuran->jumlah_angsuran + $denda;

            $totalPokok += $angsuran->jumlah_pokok;
            $totalMargin += $angsuran->jumlah_margin;
            $totalDenda += $denda;
            $totalGrand += $totalAngsuran;

            $reportData[] = [
                'angsuran' => $angsuran,
                'hari_terlambat' => $hariTerlambat,
                'denda' => $denda,
                'total_angsuran' => $totalAngsuran
            ];
        }

        return [
            'data' => $reportData,
            'summary' => [
                'total_pokok' => $totalPokok,
                'total_margin' => $totalMargin,
                'total_denda' => $totalDenda,
                'total_grand' => $totalGrand,
                'total_transaksi' => count($reportData)
            ]
        ];
    }

    public function view(): View
    {
        return view('exports.tunggakan', [
            'reportData' => $this->reportData,
            'tanggalLaporan' => $this->tanggalLaporan,
            'status' => $this->status
        ]);
    }

    public function title(): string
    {
        return 'Laporan Tunggakan Angsuran';
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
                    'startColor' => ['rgb' => 'DC2626'],
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
                    'startColor' => ['rgb' => 'FEE2E2'],
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
                    'startColor' => ['rgb' => 'EF4444'],
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
            'A6:J1000' => [
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
            'A' => 15, // No
            'B' => 25, // Nama Anggota
            'C' => 20, // Kode Angsuran
            'D' => 12, // Angsuran Ke
            'E' => 15, // Jatuh Tempo
            'F' => 12, // Hari Terlambat
            'G' => 18, // Pokok
            'H' => 18, // Margin
            'I' => 15, // Denda
            'J' => 18, // Total
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $rowCount = count($this->reportData['data']);

                // Set print area
                $sheet->getPageSetup()->setPrintArea('A1:J' . (5 + $rowCount + 2));

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

                // Format currency columns
                $sheet->getStyle('G:J')->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
            },
        ];
    }
}