<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiSimpanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi_simpanans';

    protected $fillable = [
        'kode_transaksi',
        'anggota_id',
        'jenis_simpanan_id',
        'pengurus_id',
        'jenis_transaksi',
        'jumlah',
        'tanggal_transaksi',
        'bulan',
        'tahun',
        'keterangan',
        'saldo_sebelumnya',
        'saldo_setelahnya',
        'bukti_transaksi',
        'status',
        'verified_at',
        'verified_by',
        'catatan_verifikasi',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'saldo_sebelumnya' => 'decimal:2',
        'saldo_setelahnya' => 'decimal:2',
        'tanggal_transaksi' => 'date',
        'verified_at' => 'datetime',
        'status' => 'string',
        'jenis_transaksi' => 'string',
    ];

    protected $dates = [
        'tanggal_transaksi',
        'verified_at',
    ];

    /**
     * Get the anggota that owns the transaksi
     */
    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    /**
     * Get the jenis_simpanan that owns the transaksi
     */
    public function jenisSimpanan()
    {
        return $this->belongsTo(JenisSimpanan::class);
    }

    /**
     * Get the pengurus that processes the transaksi
     */
    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class);
    }

    /**
     * Get the pengurus that verified the transaksi
     */
    public function verifiedByPengurus()
    {
        return $this->belongsTo(Pengurus::class, 'verified_by');
    }

    /**
     * Scope untuk transaksi setor
     */
    public function scopeSetor($query)
    {
        return $query->where('jenis_transaksi', 'setor');
    }

    /**
     * Scope untuk transaksi tarik
     */
    public function scopeTarik($query)
    {
        return $query->where('jenis_transaksi', 'tarik');
    }

    /**
     * Scope untuk transaksi yang sudah verified
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope untuk transaksi pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get label jenis transaksi
     */
    public function getJenisTransaksiLabelAttribute()
    {
        return $this->jenis_transaksi == 'setor' ? 'Setoran' : 'Penarikan';
    }

    /**
     * Get label status
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get badge color for status
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'verified' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800'
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get formatted amount
     */
    public function getJumlahFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Get formatted saldo_sebelumnya
     */
    public function getSaldoSebelumnyaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->saldo_sebelumnya, 0, ',', '.');
    }

    /**
     * Get formatted saldo_setelahnya
     */
    public function getSaldoSetelahnyaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->saldo_setelahnya, 0, ',', '.');
    }

    /**
     * Generate kode transaksi otomatis
     */
    public static function generateKodeTransaksi($jenis)
    {
        $prefix = $jenis == 'setor' ? 'STS' : 'TRK';
        $date = date('ymd');
        $lastTransaksi = self::whereDate('created_at', today())
                            ->where('jenis_transaksi', $jenis)
                            ->orderBy('id', 'desc')
                            ->first();

        $sequence = $lastTransaksi ? ((int) substr($lastTransaksi->kode_transaksi, -3)) + 1 : 1;

        return $prefix . $date . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate saldo after transaksi
     */
    public static function calculateSaldo($anggota_id, $jenis_simpanan_id, $jenis_transaksi, $jumlah)
    {
        // Hitung saldo_sebelumnya dari total semua transaksi: total setoran - total penarikan
        $totalSetor = self::where('anggota_id', $anggota_id)
            ->where('jenis_simpanan_id', $jenis_simpanan_id)
            ->where('jenis_transaksi', 'setor')
            ->where('status', 'verified')
            ->sum('jumlah');

        $totalTarik = self::where('anggota_id', $anggota_id)
            ->where('jenis_simpanan_id', $jenis_simpanan_id)
            ->where('jenis_transaksi', 'tarik')
            ->where('status', 'verified')
            ->sum('jumlah');

        $saldoSebelumnya = $totalSetor - $totalTarik;

        if ($jenis_transaksi == 'setor') {
            $saldoSetelahnya = $saldoSebelumnya + $jumlah;
        } else {
            $saldoSetelahnya = $saldoSebelumnya - $jumlah;
        }

        return [
            'saldo_sebelumnya' => $saldoSebelumnya,
            'saldo_setelahnya' => $saldoSetelahnya
        ];
    }

    /**
     * Hitung tunggakan simpanan wajib untuk semua anggota
     * Simpanan wajib ditagihkan setiap tanggal 1, jatuh tempo di akhir bulan
     */
    public static function hitungTunggakanSimpananWajib($anggotaId = null)
    {
        // Ambil jenis simpanan wajib
        $jenisSimpananWajib = JenisSimpanan::where('tipe_simpanan', 'wajib')->where('status', 1)->get();

        if ($jenisSimpananWajib->isEmpty()) {
            return [
                'total_tunggakan' => 0,
                'jumlah_anggota_nunggak' => 0,
                'detail_tunggakan' => collect()
            ];
        }

        $jenisSimpananWajibIds = $jenisSimpananWajib->pluck('id')->toArray();
        $nominalWajib = $jenisSimpananWajib->sum('minimal_setor'); // Asumsi semua jenis wajib punya nominal sama

        // Ambil anggota aktif
        $anggotaQuery = \App\Models\Anggota::where('status_keanggotaan', 'aktif');
        if ($anggotaId) {
            $anggotaQuery->where('id', $anggotaId);
        }
        $anggotas = $anggotaQuery->get();

        $detailTunggakan = collect();
        $totalTunggakan = 0;
        $jumlahAnggotaNunggak = 0;

        $now = now();
        $currentYear = $now->year;
        $currentMonth = $now->month;

        foreach ($anggotas as $anggota) {
            // Lewati anggota yang belum punya tanggal_gabung
            if (!$anggota->tanggal_gabung) {
                continue;
            }

            $tanggalGabung = $anggota->tanggal_gabung;
            $joinYear = $tanggalGabung->year;
            $joinMonth = $tanggalGabung->month;

            // Hitung jumlah bulan yang seharusnya dibayar dari tanggal gabung sampai bulan sekarang
            $bulanYangHarusDibayar = 0;

            // Iterasi dari tanggal gabung sampai bulan sekarang
            $year = $joinYear;
            $month = $joinMonth;

            while ($year < $currentYear || ($year == $currentYear && $month <= $currentMonth)) {
                // Hitung sampai bulan sekarang (bulan berjalan juga dihitung karena jatuh tempo di akhir bulan)
                $bulanYangHarusDibayar++;
                $month++;
                if ($month > 12) {
                    $month = 1;
                    $year++;
                }
            }

            // Ambil transaksi simpanan wajib yang sudah dibayar oleh anggota ini
            // Setiap transaksi dihitung sebagai 1 bulan bayar
            $transaksiDibayar = self::where('anggota_id', $anggota->id)
                ->whereIn('jenis_simpanan_id', $jenisSimpananWajibIds)
                ->where('jenis_transaksi', 'setor')
                ->where('status', 'verified')
                ->get();

            // Hitung jumlah bulan yang sudah dibayar (setiap transaksi = 1 bulan)
            $bulanSudahDibayar = $transaksiDibayar->count();

            // Hitung jumlah bulan yang nunggak
            $bulanNunggak = max(0, $bulanYangHarusDibayar - $bulanSudahDibayar);

            if ($bulanNunggak > 0) {
                $totalTunggakanAnggota = $bulanNunggak * $nominalWajib;
                $totalTunggakan += $totalTunggakanAnggota;
                $jumlahAnggotaNunggak++;

                $detailTunggakan->push((object)[
                    'anggota_id' => $anggota->id,
                    'nama_anggota' => $anggota->nama_lengkap,
                    'no_anggota' => $anggota->no_anggota,
                    'bulan_nunggak' => $bulanNunggak,
                    'total_tunggakan' => $totalTunggakanAnggota,
                    'tanggal_gabung' => $anggota->tanggal_gabung->format('d M Y'),
                ]);
            }
        }

        return [
            'total_tunggakan' => $totalTunggakan,
            'jumlah_anggota_nunggak' => $jumlahAnggotaNunggak,
            'detail_tunggakan' => $detailTunggakan,
            'nominal_wajib' => $nominalWajib
        ];
    }

    /**
     * Hitung tunggakan simpanan wajib untuk satu anggota
     */
    public static function hitungTunggakanPerAnggota($anggotaId)
    {
        // Ambil jenis simpanan wajib
        $jenisSimpananWajib = JenisSimpanan::where('tipe_simpanan', 'wajib')->where('status', 1)->get();

        if ($jenisSimpananWajib->isEmpty()) {
            return [
                'bulan_nunggak' => 0,
                'total_tunggakan' => 0,
                'detail_bulan_nunggak' => []
            ];
        }

        $jenisSimpananWajibIds = $jenisSimpananWajib->pluck('id')->toArray();
        $nominalWajib = $jenisSimpananWajib->sum('minimal_setor');

        $anggota = \App\Models\Anggota::find($anggotaId);

        if (!$anggota || !$anggota->tanggal_gabung) {
            return [
                'bulan_nunggak' => 0,
                'total_tunggakan' => 0,
                'detail_bulan_nunggak' => []
            ];
        }

        $now = now();
        $currentYear = $now->year;
        $currentMonth = $now->month;

        $tanggalGabung = $anggota->tanggal_gabung;
        $joinYear = $tanggalGabung->year;
        $joinMonth = $tanggalGabung->month;

        // Hitung jumlah bulan yang seharusnya dibayar
        $bulanYangHarusDibayar = 0;
        $year = $joinYear;
        $month = $joinMonth;

        while ($year < $currentYear || ($year == $currentYear && $month <= $currentMonth)) {
            $bulanYangHarusDibayar++;
            $month++;
            if ($month > 12) {
                $month = 1;
                $year++;
            }
        }

        // Ambil transaksi simpanan wajib yang sudah dibayar
        // Setiap transaksi dihitung sebagai 1 bulan bayar
        $transaksiDibayar = self::where('anggota_id', $anggotaId)
            ->whereIn('jenis_simpanan_id', $jenisSimpananWajibIds)
            ->where('jenis_transaksi', 'setor')
            ->where('status', 'verified')
            ->get();

        // Hitung jumlah transaksi (setiap transaksi = 1 bulan bayar)
        $bulanSudahDibayar = $transaksiDibayar->count();
        $bulanNunggak = max(0, $bulanYangHarusDibayar - $bulanSudahDibayar);

        // Generate detail bulan yang nunggak
        // Hitung dari bulan terakhir yang dibayar
        $detailBulanNunggak = [];

        // Jika belum ada transaksi sama sekali, semua bulan dari tanggal_gabung dianggap nunggak
        if ($transaksiDibayar->isEmpty()) {
            $year = $joinYear;
            $month = $joinMonth;

            while ($year < $currentYear || ($year == $currentYear && $month <= $currentMonth)) {
                $namaBulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $detailBulanNunggak[] = [
                    'bulan' => $month,
                    'tahun' => $year,
                    'nama_bulan' => $namaBulanIndo[$month] ?? date('F', mktime(0, 0, 0, $month, 1, $year)),
                    'nominal' => $nominalWajib
                ];

                $month++;
                if ($month > 12) {
                    $month = 1;
                    $year++;
                }
            }
        } else {
            // Ada transaksi, hitung bulan yang sudah terbayar secara berurutan
            // Setiap transaksi mewakili 1 bulan mulai dari tanggal_gabung
            $bulanTerbayar = [];
            $year = $joinYear;
            $month = $joinMonth;

            foreach ($transaksiDibayar as $transaksi) {
                $bulanKey = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                $bulanTerbayar[] = $bulanKey;

                // Increment ke bulan berikutnya untuk transaksi berikutnya
                $month++;
                if ($month > 12) {
                    $month = 1;
                    $year++;
                }
            }

            // Hitung detail bulan yang nunggak
            // Mulai dari bulan setelah bulan terakhir yang terbayar
            $count = 0;
            while ($count < $bulanNunggak) {
                // Pastikan tidak melebihi bulan sekarang
                if ($year > $currentYear || ($year == $currentYear && $month > $currentMonth)) {
                    break;
                }

                $namaBulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $detailBulanNunggak[] = [
                    'bulan' => $month,
                    'tahun' => $year,
                    'nama_bulan' => $namaBulanIndo[$month] ?? date('F', mktime(0, 0, 0, $month, 1, $year)),
                    'nominal' => $nominalWajib
                ];

                // Increment ke bulan berikutnya
                $month++;
                if ($month > 12) {
                    $month = 1;
                    $year++;
                }

                $count++;
            }
        }

        return [
            'bulan_nunggak' => $bulanNunggak,
            'total_tunggakan' => $bulanNunggak * $nominalWajib,
            'detail_bulan_nunggak' => $detailBulanNunggak
        ];
    }
}
