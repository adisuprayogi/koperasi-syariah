<?php

namespace App\Exports;

use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\TransaksiSimpanan;
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

class RekapSimpananAnggotaExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    /**
     * Get rekap data
     */
    private function getRekapData()
    {
        $anggotaQuery = Anggota::where('status_keanggotaan', 'aktif');

        if ($this->search) {
            $anggotaQuery->where(function($q) {
                $q->where('nama_lengkap', 'like', "%{$this->search}%")
                  ->orWhere('no_anggota', 'like', "%{$this->search}%");
            });
        }

        $anggotas = $anggotaQuery->orderBy('no_anggota')->get();

        $jenisSimpanan = JenisSimpanan::where('status', 1)->get();
        $jenisPokok = $jenisSimpanan->where('tipe_simpanan', 'pokok')->first();
        $jenisWajib = $jenisSimpanan->where('tipe_simpanan', 'wajib')->first();
        $jenisModal = $jenisSimpanan->where('tipe_simpanan', 'modal')->first();
        $jenisSukarela = $jenisSimpanan->where('tipe_simpanan', 'sukarela')->first();

        $rekapData = [];
        $totalPokok = 0;
        $totalWajib = 0;
        $totalModal = 0;
        $totalSukarela = 0;
        $totalAllSimpanan = 0;
        $totalTagihanWajib = 0;

        foreach ($anggotas as $anggota) {
            $saldoPokok = $this->getSaldoSimpanan($anggota->id, $jenisPokok->id ?? null);
            $saldoWajib = $this->getSaldoSimpanan($anggota->id, $jenisWajib->id ?? null);
            $saldoModal = $this->getSaldoSimpanan($anggota->id, $jenisModal->id ?? null);
            $saldoSukarela = $this->getSaldoSimpanan($anggota->id, $jenisSukarela->id ?? null);

            $totalSimpanan = $saldoPokok + $saldoWajib + $saldoModal + $saldoSukarela;

            $tunggakan = TransaksiSimpanan::hitungTunggakanPerAnggota($anggota->id);
            $tagihanWajib = $tunggakan['total_tunggakan'];

            $rekapData[] = (object) [
                'no_anggota' => $anggota->no_anggota,
                'nama' => $anggota->nama_lengkap,
                'simpanan_pokok' => $saldoPokok,
                'simpanan_wajib' => $saldoWajib,
                'simpanan_modal' => $saldoModal,
                'simpanan_sukarela' => $saldoSukarela,
                'total_simpanan' => $totalSimpanan,
                'tagihan_wajib' => $tagihanWajib,
                'bulan_nunggak' => $tunggakan['bulan_nunggak'],
            ];

            $totalPokok += $saldoPokok;
            $totalWajib += $saldoWajib;
            $totalModal += $saldoModal;
            $totalSukarela += $saldoSukarela;
            $totalAllSimpanan += $totalSimpanan;
            $totalTagihanWajib += $tagihanWajib;
        }

        return [
            'rekapData' => $rekapData,
            'totals' => [
                'pokok' => $totalPokok,
                'wajib' => $totalWajib,
                'modal' => $totalModal,
                'sukarela' => $totalSukarela,
                'all' => $totalAllSimpanan,
                'tagihan' => $totalTagihanWajib,
            ]
        ];
    }

    private function getSaldoSimpanan($anggotaId, $jenisSimpananId)
    {
        if (!$jenisSimpananId) {
            return 0;
        }

        $totalSetor = TransaksiSimpanan::where('anggota_id', $anggotaId)
            ->where('jenis_simpanan_id', $jenisSimpananId)
            ->where('jenis_transaksi', 'setor')
            ->where('status', 'verified')
            ->sum('jumlah');

        $totalTarik = TransaksiSimpanan::where('anggota_id', $anggotaId)
            ->where('jenis_simpanan_id', $jenisSimpananId)
            ->where('jenis_transaksi', 'tarik')
            ->where('status', 'verified')
            ->sum('jumlah');

        return $totalSetor - $totalTarik;
    }

    public function view(): View
    {
        $koperasi = Koperasi::first();
        $data = $this->getRekapData();

        return view('exports.rekap_simpanan_anggota', [
            'koperasi' => $koperasi,
            'rekapData' => $data['rekapData'],
            'totals' => $data['totals']
        ]);
    }

    public function title(): string
    {
        return 'Rekap Simpanan Anggota';
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
            'A' => 8,
            'B' => 15,
            'C' => 30,
            'D' => 18,
            'E' => 18,
            'F' => 18,
            'G' => 18,
            'H' => 18,
            'I' => 18,
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
