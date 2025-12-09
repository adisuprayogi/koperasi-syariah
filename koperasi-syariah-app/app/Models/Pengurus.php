<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengurus extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengurus';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'no_telepon',
        'alamat',
        'posisi',
        'tanggal_menjabat',
        'status',
        'keterangan',
        'user_id',
        // Additional fields for new implementation
        'nik',
        'jabatan',
        'telepon',
        'tanggal_lahir',
        'tempat_lahir',
        'tanggal_bergabung'
    ];

    protected $casts = [
        'tanggal_menjabat' => 'date',
        'status' => 'string',
        'tanggal_lahir' => 'date',
        'tanggal_bergabung' => 'date',
    ];

    /**
     * Get the user that owns the pengurus.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get position label
     */
    public function getJabatanLabelAttribute()
    {
        $labels = [
            'ketua' => 'Ketua',
            'sekretaris' => 'Sekretaris',
            'bendahara' => 'Bendahara',
            'pengurus_lainnya' => 'Pengurus Lainnya'
        ];

        return $labels[$this->posisi] ?? $this->posisi;
    }

    /**
     * Get position label (alias for jabatan_label)
     */
    public function getPosisiLabelAttribute()
    {
        return $this->getJabatanLabelAttribute();
    }

    /**
     * Scope untuk pengurus aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk posisi
     */
    public function scopePosisi($query, $posisi)
    {
        return $query->where('posisi', $posisi);
    }

    /**
     * Scope untuk status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
