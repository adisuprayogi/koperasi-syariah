<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnggotaImportErrorExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
{
    protected $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function collection()
    {
        $rows = [];

        foreach ($this->errors as $error) {
            $rows[] = [
                $error['row'],
                $error['error'],
                $error['data'][0] ?? '', // nama_lengkap
                $error['data'][1] ?? '', // jenis_kelamin
                $error['data'][2] ?? '', // tempat_lahir
                $error['data'][3] ?? '', // tanggal_lahir
                $error['data'][4] ?? '', // alamat
                $error['data'][5] ?? '', // no_hp
                $error['data'][6] ?? '', // pekerjaan
                $error['data'][7] ?? '', // penghasilan
                $error['data'][8] ?? '', // email
                $error['data'][9] ?? '', // password
                $error['data'][10] ?? '', // no_anggota
                $error['data'][11] ?? '', // nik
                $error['data'][12] ?? ''  // no_npwp
            ];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Baris',
            'Error',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'No HP',
            'Pekerjaan',
            'Penghasilan',
            'Email',
            'Password',
            'No Anggota',
            'NIK',
            'NPWP'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style header
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FF0000']
                ]
            ],
            // Style error column
            'B:B' => [
                'font' => [
                    'color' => ['rgb' => 'FF0000'],
                    'bold' => true
                ]
            ]
        ];
    }

    public function title(): string
    {
        return 'Error Report Import';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,  // Baris
            'B' => 40, // Error
            'C' => 25, // Nama
            'D' => 15, // Jenis Kelamin
            'E' => 20, // Tempat Lahir
            'F' => 15, // Tanggal Lahir
            'G' => 30, // Alamat
            'H' => 15, // No HP
            'I' => 20, // Pekerjaan
            'J' => 15, // Penghasilan
            'K' => 30, // Email
            'L' => 20, // Password
            'M' => 15, // No Anggota
            'N' => 20, // NIK
            'O' => 20, // NPWP
        ];
    }
}