<?php

namespace App\Exports;

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

class NeracaExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    private $tanggal;
    private $totalSimpanan;
    private $totalPiutang;
    private $totalAset;
    private $kewajibanSimpanan;
    private $kewajibanLainnya;
    private $totalKewajiban;
    private $modalAwal;
    private $shuBerjalan;
    private $totalEkuitas;
    private $totalKewajibanEkuitas;

    public function __construct($tanggal)
    {
        // Ensure tanggal is a valid Carbon instance
        $this->tanggal = \Carbon\Carbon::parse($tanggal)->format('Y-m-d');

        // Hitung data neraca
        $this->calculateNeracaData();
    }

    private function calculateNeracaData()
    {
        // Import models yang diperlukan
        $transaksiSimpanan = \App\Models\TransaksiSimpanan::whereDate('tanggal_transaksi', '<=', $this->tanggal)->get();
        $pengajuanPembiayaan = \App\Models\PengajuanPembiayaan::where('status', 'cair')
            ->whereDate('tanggal_cair', '<=', $this->tanggal)
            ->with('angsurans')
            ->get();

        // Aset
        // Kas - Total simpanan anggota
        $totalSetor = $transaksiSimpanan->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = $transaksiSimpanan->where('jenis_transaksi', 'tarik')->sum('jumlah');
        $this->totalSimpanan = $totalSetor - $totalTarik;

        // Piutang anggota - Sisa pembiayaan yang belum dibayar
        $this->totalPiutang = $pengajuanPembiayaan->sum(function($pengajuan) {
            return $pengajuan->sisaTotal();
        });

        $this->totalAset = $this->totalSimpanan + $this->totalPiutang;

        // Kewajiban
        // Simpanan anggota (kewajiban koperasi kepada anggota)
        $this->kewajibanSimpanan = $this->totalSimpanan;

        // Beban lainnya (placeholder)
        $this->kewajibanLainnya = 0;

        $this->totalKewajiban = $this->kewajibanSimpanan + $this->kewajibanLainnya;

        // Ekuitas
        // Modal awal (placeholder)
        $this->modalAwal = 0;

        // SHU berjalan
        $year = \Carbon\Carbon::parse($this->tanggal)->year;
        $angsuran = \App\Models\Angsuran::whereYear('tanggal_bayar', $year)
            ->where('status', 'terbayar')
            ->get();

        $marginReceived = $angsuran->sum('jumlah_margin');
        $otherIncome = 0;
        $expenses = 0;

        $this->shuBerjalan = $marginReceived + $otherIncome - $expenses;

        $this->totalEkuitas = $this->modalAwal + $this->shuBerjalan;

        // Total kewajiban + ekuitas harus sama dengan total aset
        $this->totalKewajibanEkuitas = $this->totalKewajiban + $this->totalEkuitas;
    }

    public function view(): View
    {
        return view('pengurus.laporan.export.neraca', [
            'tanggal' => $this->tanggal,
            'totalSimpanan' => $this->totalSimpanan,
            'totalPiutang' => $this->totalPiutang,
            'totalAset' => $this->totalAset,
            'kewajibanSimpanan' => $this->kewajibanSimpanan,
            'kewajibanLainnya' => $this->kewajibanLainnya,
            'totalKewajiban' => $this->totalKewajiban,
            'modalAwal' => $this->modalAwal,
            'shuBerjalan' => $this->shuBerjalan,
            'totalEkuitas' => $this->totalEkuitas,
            'totalKewajibanEkuitas' => $this->totalKewajibanEkuitas,
        ]);
    }

    public function title(): string
    {
        $formattedDate = \Carbon\Carbon::parse($this->tanggal)->format('d F Y');
        return "LAPORAN NERACA - {$formattedDate}";
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

            // Style untuk sub-header (Aset)
            4 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '059669'],
                    'endColor' => ['argb' => '059669'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ],

            // Style untuk sub-header (Kewajiban)
            10 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'DC2626'],
                    'endColor' => ['argb' => 'DC2626'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ],

            // Style untuk sub-header (Ekuitas)
            15 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '7C3AED'],
                    'endColor' => ['argb' => '7C3AED'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ],

            // Style untuk total rows
            8 => [ // Total Aset
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FCD34D'],
                    'endColor' => ['argb' => 'FCD34D'],
                ],
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                ],
            ],
            13 => [ // Total Kewajiban
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FCD34D'],
                    'endColor' => ['argb' => 'FCD34D'],
                ],
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                ],
            ],
            18 => [ // Total Ekuitas
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FCD34D'],
                    'endColor' => ['argb' => 'FCD34D'],
                ],
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
                ],
            ],
            20 => [ // Total Kewajiban + Ekuitas
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '059669'],
                    'endColor' => ['argb' => '059669'],
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
            'B' => 30,     // Uraian
            'C' => 20,     // Jumlah
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Apply borders untuk semua data cells
                $sheet->getStyle('A4:C20')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Merge cells untuk header utama
                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');

                // Merge cells untuk sub-headers
                $sheet->mergeCells('A4:B4');
                $sheet->mergeCells('A10:B10');
                $sheet->mergeCells('A15:B15');

                // Merge cells untuk total row
                $sheet->mergeCells('A8:B8');
                $sheet->mergeCells('A13:B13');
                $sheet->mergeCells('A18:B18');
                $sheet->mergeCells('A20:B20');

                // Apply number format untuk currency columns
                $sheet->getStyle('C5:C20')->getNumberFormat()->setFormatCode('#,##0.00');

                // Set auto-width for better readability
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);

                // Add footer info
                $lastRow = $sheet->getHighestRow();
                $sheet->setCellValue('A' . ($lastRow + 2), 'Dicetak pada: ' . now()->format('d F Y H:i:s'));
                $sheet->setCellValue('A' . ($lastRow + 3), 'Koperasi Syariah');
                $sheet->getStyle('A' . ($lastRow + 2) . ':C' . ($lastRow + 3))->applyFromArray([
                    'font' => [
                        'size' => 9,
                        'italic' => true,
                        'color' => ['argb' => '6B7280'],
                    ],
                ]);
            },
        ];
    }
}