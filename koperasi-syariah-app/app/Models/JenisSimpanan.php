<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSimpanan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jenis_simpanan';

    protected $fillable = [
        'kode_jenis',
        'nama_simpanan',
        'tipe_simpanan',
        'nisbah',
        'minimal_setor',
        'maksimal_setor',
        'bisa_ditarik',
        'aturan_penarikan',
        'periode_hitung_bunga',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'nisbah' => 'decimal:2',
        'minimal_setor' => 'decimal:2',
        'maksimal_setor' => 'decimal:2',
        'bisa_ditarik' => 'boolean',
        'status' => 'boolean',
        'tipe_simpanan' => 'string',
        'periode_hitung_bunga' => 'string',
    ];

    /**
     * Scope untuk simpanan aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope berdasarkan tipe simpanan
     */
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe_simpanan', $tipe);
    }

    /**
     * Scope untuk simpanan yang bisa ditarik
     */
    public function scopeBisaDitarik($query)
    {
        return $query->where('bisa_ditarik', true);
    }

    /**
     * Scope untuk simpanan yang dapat nisbah
     */
    public function scopeDapatNisbah($query)
    {
        return $query->where('tipe_simpanan', 'sukarela');
    }

    /**
     * Get label tipe simpanan
     */
    public function getTipeLabelAttribute()
    {
        $types = [
            'modal' => 'Simpanan Modal',
            'pokok' => 'Simpanan Pokok',
            'wajib' => 'Simpanan Wajib',
            'sukarela' => 'Simpanan Sukarela'
        ];

        return $types[$this->tipe_simpanan] ?? $this->tipe_simpanan;
    }

    /**
     * Check apakah simpanan ini untuk syariah
     */
    public function isSyariah()
    {
        return true; // Semua simpanan menggunakan nisbah (syariah)
    }

    /**
     * Get nama attribute (alias for nama_simpanan)
     */
    public function getNamaAttribute()
    {
        return $this->nama_simpanan;
    }

    /**
     * Get nama attribute (alias for nama_simpanan)
     */
    public function getKodeAttribute()
    {
        return $this->kode_jenis;
    }
}
