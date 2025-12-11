<?php

namespace App\Exports;

use App\Models\TransaksiSimpanan;
use App\Models\JenisSimpanan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class SimpananExport implements FromView, WithColumnWidths, WithStyles, WithTitle
{
    protected $startDate;
    protected $endDate;
    protected $jenisSimpananId;

    public function __construct($startDate = null, $endDate = null, $jenisSimpananId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->jenisSimpananId = $jenisSimpananId;
    }

    public function view(): View
    {
        $query = TransaksiSimpanan::with(['anggota', 'jenisSimpanan', 'pengurus']);

        // Filter berdasarkan tanggal
        if ($this->startDate) {
            $query->whereDate('tanggal_transaksi', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('tanggal_transaksi', '<=', $this->endDate);
        }

        // Filter berdasarkan jenis simpanan
        if ($this->jenisSimpananId) {
            $query->where('jenis_simpanan_id', $this->jenisSimpananId);
        }

        $transaksi = $query->orderBy('tanggal_transaksi', 'desc')->get();

        // Calculate statistics
        $totalSimpananWajib = $transaksi->where('jenis_simpanan_id', 1)->sum('jumlah');
        $totalSimpananSukarela = $transaksi->where('jenis_simpanan_id', 2)->sum('jumlah');
        $totalSimpananWajibBulanan = $transaksi->where('jenis_simpanan_id', 3)->sum('jumlah');

        return view('exports.simpanan', [
            'transaksi' => $transaksi,
            'totalSimpananWajib' => $totalSimpananWajib,
            'totalSimpananSukarela' => $totalSimpananSukarela,
            'totalSimpananWajibBulanan' => $totalSimpananWajibBulanan,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'jenisSimpanan' => $this->jenisSimpananId ? JenisSimpanan::find($this->jenisSimpananId) : null,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // No
            'B' => 20, // Tanggal
            'C' => 18, // Kode Transaksi
            'D' => 25, // Nama Anggota
            'E' => 15, // No. Anggota
            'F' => 20, // Jenis Simpanan
            'G' => 18, // Jumlah (Debit)
            'H' => 18, // Jumlah (Kredit)
            'I' => 25, // Keterangan
            'J' => 15, // Petugas
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            // Style untuk sub-header
            3 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            // Style untuk column headers
            5 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E3F2FD']
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
          ];
    }

    public function title(): string
    {
        return 'Laporan Simpanan';
    }
}