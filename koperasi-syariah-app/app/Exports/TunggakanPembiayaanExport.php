<?php

namespace App\Exports;

use App\Models\PengajuanPembiayaan;
use App\Models\JenisPembiayaan;
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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TunggakanPembiayaanExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $jenisPembiayaanId;
    protected $anggotaId;
    protected $bulan;
    protected $tahun;
    protected $search;

    public function __construct($jenisPembiayaanId = null, $anggotaId = null, $bulan = null, $tahun = null, $search = null)
    {
        $this->jenisPembiayaanId = $jenisPembiayaanId;
        $this->anggotaId = $anggotaId;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->search = $search;
    }

    /**
     * Get tunggakan data
     */
    private function getTunggakanData()
    {
        $query = PengajuanPembiayaan::with(['anggota', 'jenisPembiayaan'])
            ->whereIn('status', ['cair', 'lunas'])
            ->whereHas('angsurans', function($q) {
                $q->where('tanggal_jatuh_tempo', '<', now())
                  ->whereNotIn('status', ['terbayar', 'lunas_lebih_cepat']);
            });

        // Filter by jenis pembiayaan
        if ($this->jenisPembiayaanId) {
            $query->where('jenis_pembiayaan_id', $this->jenisPembiayaanId);
        }

        // Filter by anggota
        if ($this->anggotaId) {
            $query->where('anggota_id', $this->anggotaId);
        }

        // Filter by periode (bulan jatuh tempo)
        if ($this->bulan && $this->tahun) {
            $query->whereHas('angsurans', function($q) {
                $q->whereMonth('tanggal_jatuh_tempo', $this->bulan)
                  ->whereYear('tanggal_jatuh_tempo', $this->tahun)
                  ->whereNotIn('status', ['terbayar', 'lunas_lebih_cepat']);
            });
        }

        // Search by nama atau no anggota
        if ($this->search) {
            $query->whereHas('anggota', function($q) {
                $q->where('nama_lengkap', 'like', "%{$this->search}%")
                  ->orWhere('no_anggota', 'like', "%{$this->search}%");
            });
        }

        // Get data order by anggota, then pembiayaan
        $pembiayaans = $query->orderBy('anggota_id')->orderBy('id')->get();

        // Process data to calculate tunggakan
        $tunggakanData = [];
        $totalSisaAngsuran = 0;
        $totalBulanMenunggak = 0;
        $totalJumlahMenunggak = 0;

        foreach ($pembiayaans as $pembiayaan) {
            // Get angsuran yang menunggak
            $angsuranMenunggak = $pembiayaan->angsurans()
                ->where('tanggal_jatuh_tempo', '<', now())
                ->whereNotIn('status', ['terbayar', 'lunas_lebih_cepat'])
                ->get();

            if ($angsuranMenunggak->isEmpty()) {
                continue;
            }

            // Calculate metrics
            $sisaAngsuran = $pembiayaan->angsurans()
                ->whereNotIn('status', ['terbayar', 'lunas_lebih_cepat'])
                ->count();

            $bulanMenunggak = $angsuranMenunggak->count();
            $jumlahMenunggak = $angsuranMenunggak->sum('jumlah_angsuran');

            $tunggakanData[] = (object) [
                'id' => $pembiayaan->id,
                'kode_pengajuan' => $pembiayaan->kode_pengajuan,
                'no_anggota' => $pembiayaan->anggota->no_anggota,
                'nama_anggota' => $pembiayaan->anggota->nama_lengkap,
                'jenis_pembiayaan' => $pembiayaan->jenisPembiayaan->nama_pembiayaan ?? '-',
                'tenor' => $pembiayaan->tenor,
                'sisa_angsuran' => $sisaAngsuran,
                'bulan_menunggak' => $bulanMenunggak,
                'jumlah_menunggak' => $jumlahMenunggak,
            ];

            $totalSisaAngsuran += $sisaAngsuran;
            $totalBulanMenunggak += $bulanMenunggak;
            $totalJumlahMenunggak += $jumlahMenunggak;
        }

        return [
            'tunggakanData' => $tunggakanData,
            'totals' => [
                'sisa_angsuran' => $totalSisaAngsuran,
                'bulan_menunggak' => $totalBulanMenunggak,
                'jumlah_menunggak' => $totalJumlahMenunggak,
            ]
        ];
    }

    public function view(): View
    {
        $koperasi = Koperasi::first();
        $data = $this->getTunggakanData();

        return view('exports.tunggakan_pembiayaan', [
            'koperasi' => $koperasi,
            'tunggakanData' => $data['tunggakanData'],
            'totals' => $data['totals']
        ]);
    }

    public function title(): string
    {
        return 'Tunggakan Pembiayaan';
    }

    public function styles($sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            3 => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 12,
            'C' => 15,
            'D' => 25,
            'E' => 20,
            'F' => 15,
            'G' => 18,
            'H' => 20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            },
        ];
    }
}
