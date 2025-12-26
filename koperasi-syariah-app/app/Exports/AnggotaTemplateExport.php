<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnggotaTemplateExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function collection()
    {
        // Contoh data (3 baris contoh)
        return collect([
            [
                'Ahmad Rizki',
                'L',
                'Jakarta',
                '1990-01-15',
                'Jl. Merdeka No. 123',
                '08123456789',
                'Pegawai Swasta',
                '5000000',
                'ahmad.rizki@email.com',
                'password123',
                '', // Kosongkan untuk auto-generate: YYMM.00001
                '1234567890123456', // NIK (16 digit)
                '', // NPWP (opsional)
                'aktif', // status_keanggotaan: aktif/tidak_aktif/keluar
                '2025-12-16', // tanggal_gabung: YYYY-MM-DD
                '', // tanggal_keluar: YYYY-MM-DD (kosongkan jika aktif)
                '' // alasan_keluar (kosongkan jika aktif)
                // NOTE: Username akan sama dengan nomor anggota: 2512.00001
            ],
            [
                'Siti Nurhaliza',
                'P',
                'Surabaya',
                '1992-05-20',
                'Jl. Sudirman No. 456',
                '08234567890',
                'Wiraswasta',
                '7500000',
                'siti.nur@email.com',
                'password456',
                '2512.00015', // Manual input dengan format: YYMM.00001
                '2345678901234567', // NIK (16 digit)
                '', // NPWP (opsional)
                'aktif', // status_keanggotaan: aktif/tidak_aktif/keluar
                '2025-12-10', // tanggal_gabung: YYYY-MM-DD
                '', // tanggal_keluar: YYYY-MM-DD (kosongkan jika aktif)
                '' // alasan_keluar (kosongkan jika aktif)
                // NOTE: Username akan sama dengan nomor anggota: 2512.00015
            ],
            [
                'Budi Santoso',
                'L',
                'Bandung',
                '1988-11-10',
                'Jl. Gatot Subroto No. 789',
                '08345678901',
                'Guru',
                '4000000',
                'budi.santoso@email.com',
                'password789',
                '2601.00100', // Manual input dengan format: YYMM.00001
                '3456789012345678', // NIK (16 digit)
                '123456789012345', // NPWP (opsional)
                'keluar', // status_keanggotaan: aktif/tidak_aktif/keluar
                '2025-01-15', // tanggal_gabung: YYYY-MM-DD
                '2025-11-30', // tanggal_keluar: YYYY-MM-DD (isi jika keluar)
                'Pindah tugas ke luar kota' // alasan_keluar (isi jika keluar)
                // NOTE: Username akan sama dengan nomor anggota: 2601.00100
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'nama_lengkap',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'alamat_lengkap',
            'no_hp',
            'pekerjaan',
            'penghasilan',
            'email',
            'password',
            'no_anggota', // Kosongkan untuk auto-generate atau isi manual dengan format YYMM.00001
            'nik', // Wajib, 16 digit angka
            'no_npwp', // Opsional
            'status_keanggotaan', // aktif/tidak_aktif/keluar
            'tanggal_gabung', // YYYY-MM-DD (wajib)
            'tanggal_keluar', // YYYY-MM-DD (opsional, isi jika status keluar)
            'alasan_keluar' // Opsional (isi jika status keluar)
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
                    'startColor' => ['rgb' => '4472C4']
                ]
            ],
            // Auto width for columns
            'A:Q' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ]
            ]
        ];
    }

    public function title(): string
    {
        return 'Template Data Anggota';
    }
}