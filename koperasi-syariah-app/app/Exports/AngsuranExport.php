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

class AngsuranExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $status;
    protected $tanggalMulai;
    protected $tanggalSelesai;
    protected $anggotaId;
    protected $reportData;

    public function __construct($status = 'all', $tanggalMulai = null, $tanggalSelesai = null, $anggotaId = null)
    {
        $this->status = $status;
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSelesai = $tanggalSelesai;
        $this->anggotaId = $anggotaId;
        $this->reportData = $this->generateAngsuranReport($status, $tanggalMulai, $tanggalSelesai, $anggotaId);
    }

    /**
     * Generate angsuran report based on filters.
     *
     * @param  string  $status
     * @param  string|null  $tanggalMulai
     * @param  string|null  $tanggalSelesai
     * @param  int|null  $anggotaId
     * @return array
     */
    private function generateAngsuranReport($status, $tanggalMulai = null, $tanggalSelesai = null, $anggotaId = null)
    {
        $query = Angsuran::with(['anggota', 'pengajuanPembiayaan', 'dibayarOleh']);

        // Apply status filter
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Apply date filters
        if ($tanggalMulai) {
            $query->whereDate('tanggal_jatuh_tempo', '>=', $tanggalMulai);
        }
        if ($tanggalSelesai) {
            $query->whereDate('tanggal_jatuh_tempo', '<=', $tanggalSelesai);
        }

        // Apply anggota filter
        if ($anggotaId) {
            $query->where('anggota_id', $anggotaId);
        }

        $angsuran = $query->orderBy('tanggal_jatuh_tempo', 'asc')->get();

        // Group by status for summary
        $groupByStatus = $angsuran->groupBy('status');
        $summaryByStatus = [];

        foreach ($groupByStatus as $statusKey => $items) {
            $summaryByStatus[] = [
                'status' => $statusKey,
                'status_label' => $this->getStatusLabel($statusKey),
                'jumlah' => $items->count(),
                'total_pokok' => $items->sum('jumlah_pokok'),
                'total_margin' => $items->sum('jumlah_margin'),
                'total_denda' => $items->sum('denda'),
                'total_grand' => $items->sum(function($item) {
                    return $item->jumlah_angsuran + $item->denda;
                })
            ];
        }

        // Calculate totals
        $totalPokok = $angsuran->sum('jumlah_pokok');
        $totalMargin = $angsuran->sum('jumlah_margin');
        $totalDenda = $angsuran->sum('denda');
        $totalGrand = $angsuran->sum(function($item) {
            return $item->jumlah_angsuran + $item->denda;
        });

        return [
            'data' => $angsuran,
            'summary_by_status' => $summaryByStatus,
            'summary' => [
                'total_transaksi' => $angsuran->count(),
                'total_pokok' => $totalPokok,
                'total_margin' => $totalMargin,
                'total_denda' => $totalDenda,
                'total_grand' => $totalGrand
            ]
        ];
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'terbayar' => 'Sudah Dibayar',
            'terlambat' => 'Terlambat Bayar'
        ];

        return $labels[$status] ?? $status;
    }

    public function view(): View
    {
        $statusLabel = $this->status === 'all' ? 'Semua Status' : $this->getStatusLabel($this->status);

        return view('exports.angsuran', [
            'reportData' => $this->reportData,
            'status' => $statusLabel,
            'filterStatus' => $this->status,
            'tanggalMulai' => $this->tanggalMulai,
            'tanggalSelesai' => $this->tanggalSelesai,
            'tanggalLaporan' => now()
        ]);
    }

    public function title(): string
    {
        return 'Laporan Angsuran';
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
                    'startColor' => ['rgb' => '059669'],
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
                    'startColor' => ['rgb' => 'D1FAE5'],
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
                    'startColor' => ['rgb' => '059669'],
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
            'A6:K1000' => [
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
            'B' => 20, // Nama Anggota
            'C' => 18, // Kode Angsuran
            'D' => 12, // Angsuran Ke
            'E' => 15, // Jatuh Tempo
            'F' => 12, // Status
            'G' => 10, // Hari Terlambat
            'H' => 15, // Pokok
            'I' => 15, // Margin
            'J' => 12, // Denda
            'K' => 15, // Total Bayar
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $rowCount = count($this->reportData['data']);

                // Set print area
                $sheet->getPageSetup()->setPrintArea('A1:K' . (5 + $rowCount + 2));

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
                $sheet->getStyle('H')->getNumberFormat()->setFormatCode('_(\"Rp\"* #,##0_);_(\"Rp\"* \\(#,##0\\);_(\"Rp\"* \"-\"??_);_(@_)');
                $sheet->getStyle('I')->getNumberFormat()->setFormatCode('_(\"Rp\"* #,##0_);_(\"Rp\"* \\(#,##0\\);_(\"Rp\"* \"-\"??_);_(@_)');
                $sheet->getStyle('J')->getNumberFormat()->setFormatCode('_(\"Rp\"* #,##0_);_(\"Rp\"* \\(#,##0\\);_(\"Rp\"* \"-\"??_);_(@_)');
                $sheet->getStyle('K')->getNumberFormat()->setFormatCode('_(\"Rp\"* #,##0_);_(\"Rp\"* \\(#,##0\\);_(\"Rp\"* \"-\"??_);_(@_)');
            },
        ];
    }
}