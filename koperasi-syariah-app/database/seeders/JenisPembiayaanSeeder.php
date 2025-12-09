<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPembiayaan;

class JenisPembiayaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenisPembiayaan = [
            [
                'kode_jenis' => 'PM001',
                'nama_pembiayaan' => 'Pembiayaan Murabahah Motor',
                'tipe_pembiayaan' => 'murabahah',
                'margin' => 15.00,
                'bagi_hasil' => 0.00,
                'periode_hitung' => 'bulanan',
                'minimal_pembiayaan' => 1000000,
                'maksimal_pembiayaan' => 15000000,
                'jangka_waktu_min' => 6,
                'jangka_waktu_max' => 24,
                'syarat_dukung' => 'KTP, KK, Slip Gaji, Surat Keterangan Usaha',
                'keterangan' => 'Pembiayaan pembelian motor baru/bekas dengan margin 15%',
                'status' => 1,
            ],
            [
                'kode_jenis' => 'PM002',
                'nama_pembiayaan' => 'Pembiayaan Murabahah Elektronik',
                'tipe_pembiayaan' => 'murabahah',
                'margin' => 12.50,
                'bagi_hasil' => 0.00,
                'periode_hitung' => 'bulanan',
                'minimal_pembiayaan' => 500000,
                'maksimal_pembiayaan' => 10000000,
                'jangka_waktu_min' => 3,
                'jangka_waktu_max' => 12,
                'syarat_dukung' => 'KTP, KK, Slip Gaji',
                'keterangan' => 'Pembiayaan pembelian elektronik dengan margin 12.5%',
                'status' => 1,
            ],
            [
                'kode_jenis' => 'PB001',
                'nama_pembiayaan' => 'Modal Kerja Mudharabah',
                'tipe_pembiayaan' => 'mudharabah',
                'margin' => 0.00,
                'bagi_hasil' => 60.00,
                'periode_hitung' => 'tahunan',
                'minimal_pembiayaan' => 5000000,
                'maksimal_pembiayaan' => 100000000,
                'jangka_waktu_min' => 12,
                'jangka_waktu_max' => 36,
                'syarat_dukung' => 'KTP, KK, Proposal Usaha, Laporan Keuangan',
                'keterangan' => 'Pembiayaan modal kerja dengan sistem bagi hasil 60:40',
                'status' => 1,
            ],
            [
                'kode_jenis' => 'PK001',
                'nama_pembiayaan' => 'Investasi Musyarakah',
                'tipe_pembiayaan' => 'musyarakah',
                'margin' => 0.00,
                'bagi_hasil' => 70.00,
                'periode_hitung' => 'tahunan',
                'minimal_pembiayaan' => 10000000,
                'maksimal_pembiayaan' => 500000000,
                'jangka_waktu_min' => 24,
                'jangka_waktu_max' => 60,
                'syarat_dukung' => 'KTP, KK, Proposal Investasi, Feasibility Study',
                'keterangan' => 'Pembiayaan investasi dengan sistem bagi hasil 70:30',
                'status' => 1,
            ],
            [
                'kode_jenis' => 'PQ001',
                'nama_pembiayaan' => 'Pinjaman Qardhul Hasan',
                'tipe_pembiayaan' => 'qardh',
                'margin' => 0.00,
                'bagi_hasil' => 0.00,
                'periode_hitung' => 'jtempo',
                'minimal_pembiayaan' => 100000,
                'maksimal_pembiayaan' => 5000000,
                'jangka_waktu_min' => 1,
                'jangka_waktu_max' => 12,
                'syarat_dukung' => 'KTP, KK, Surat Keterangan Darurat',
                'keterangan' => 'Pinjaman sosial tanpa margin/bagi hasil untuk keperluan darurat',
                'status' => 1,
            ],
        ];

        foreach ($jenisPembiayaan as $data) {
            JenisPembiayaan::create($data);
        }
    }
}
