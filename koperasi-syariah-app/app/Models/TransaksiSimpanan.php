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
        $lastTransaksi = self::where('anggota_id', $anggota_id)
                            ->where('jenis_simpanan_id', $jenis_simpanan_id)
                            ->where('status', 'verified')
                            ->orderBy('created_at', 'desc')
                            ->first();

        $saldoSebelumnya = $lastTransaksi ? $lastTransaksi->saldo_setelahnya : 0;

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
}
