<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPembiayaan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jenis_pembiayaans';

    protected $fillable = [
        'kode_jenis',
        'nama_pembiayaan',
        'tipe_pembiayaan',
        'margin',
        'bagi_hasil',
        'periode_hitung',
        'minimal_pembiayaan',
        'maksimal_pembiayaan',
        'jangka_waktu_min',
        'jangka_waktu_max',
        'syarat_dukung',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'margin' => 'decimal:2',
        'bagi_hasil' => 'decimal:2',
        'minimal_pembiayaan' => 'decimal:2',
        'maksimal_pembiayaan' => 'decimal:2',
        'status' => 'boolean',
        'tipe_pembiayaan' => 'string',
        'jangka_waktu_min' => 'integer',
        'jangka_waktu_max' => 'integer',
    ];

    /**
     * Scope untuk pembiayaan aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope berdasarkan tipe pembiayaan
     */
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe_pembiayaan', $tipe);
    }

    /**
     * Get label tipe pembiayaan
     */
    public function getTipeLabelAttribute()
    {
        $types = [
            'murabahah' => 'Murabahah (Jual Beli)',
            'mudharabah' => 'Mudharabah (Bagi Hasil)',
            'musyarakah' => 'Musyarakah (Kerja Sama)',
            'qardh' => 'Qardh (Pinjaman Baik)',
            'ijarah' => 'Ijarah (Sewa Barang/Jasa)'
        ];

        return $types[$this->tipe_pembiayaan] ?? $this->tipe_pembiayaan;
    }

    /**
     * Get persentase margin dalam format yang lebih mudah dibaca
     */
    public function getMarginFormattedAttribute()
    {
        return number_format($this->margin, 2) . '%';
    }

    /**
     * Get persentase bagi hasil dalam format yang lebih mudah dibaca
     */
    public function getBagiHasilFormattedAttribute()
    {
        return number_format($this->bagi_hasil, 2) . '%';
    }

    /**
     * Check apakah pembiayaan ini syariah
     */
    public function isSyariah()
    {
        return true; // Semua tipe adalah syariah
    }

    /**
     * Get deskripsi tipe pembiayaan
     */
    public function getDeskripsiTipeAttribute()
    {
        $descriptions = [
            'murabahah' => 'Jual beli barang dengan tambahan margin yang disepakati',
            'mudharabah' => 'Pembiayaan bagi hasil dengan nisbah yang disepakati',
            'musyarakah' => 'Kerja sama dengan pembagian keuntungan sesuai kesepakatan',
            'qardh' => 'Pinjaman baik tanpa tambahan keuntungan'
        ];

        return $descriptions[$this->tipe_pembiayaan] ?? '';
    }
}
