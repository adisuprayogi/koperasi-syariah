<?php

namespace App\Exports;

use App\Models\Anggota;
use App\Models\PengajuanPembiayaan;
use App\Models\Angsuran;
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

class PembiayaanPerAnggotaExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $anggotaId;
    protected $anggota;
    protected $reportData;
    protected $status;

    public function __construct($anggotaId, $status = 'all')
    {
        $this->anggotaId = $anggotaId;
        $this->anggota = Anggota::find($anggotaId);
        $this->status = $status;
        $this->reportData = $this->generatePembiayaanReportForAnggota($anggotaId, $status);
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

            $reportData[] = [
                'pengajuan' => $p,
                'total_pinjaman' => $p->jumlah_pengajuan + $p->jumlah_margin,
                'total_dibayar' => $p->totalDibayar(),
                'sisa_total' => $p->sisaTotal(),
                'jumlah_angsuran' => $p->angsurans->count(),
                'angsuran_terbayar' => $p->angsurans->where('status', 'terbayar')->count()
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

    public function view(): View
    {
        return view('exports.pembiayaan_per_anggota', [
            'anggota' => $this->anggota,
            'reportData' => $this->reportData,
            'status' => $this->status
        ]);
    }

    public function title(): string
    {
        return 'Laporan Pembiayaan';
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
            'A8:I100' => [
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
            'A' => 20, // Kode Pembiayaan
            'B' => 25, // Jenis Pembiayaan
            'C' => 15, // Jangka Waktu
            'D' => 20, // Plafond
            'E' => 18, // Margin
            'F' => 20, // Total Pembiayaan
            'G' => 20, // Terbayar
            'H' => 20, // Sisa
            'I' => 15, // Status
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set print area
                $sheet->getPageSetup()->setPrintArea('A1:I' . (7 + count($this->reportData['pengajuan']) + 2));

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