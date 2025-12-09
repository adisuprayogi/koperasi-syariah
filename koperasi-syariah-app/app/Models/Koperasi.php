<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Koperasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'koperasi';

    protected $fillable = [
        'nama_koperasi',
        'alamat',
        'telepon',
        'email',
        'website',
        'no_koperasi',
        'tanggal_berdiri',
        'no_akta_notaris',
        'tanggal_akta',
        'nama_notaris',
        'ketua_nama',
        'ketua_nik',
        'sekretaris_nama',
        'sekretaris_nik',
        'bendahara_nama',
        'bendahara_nik',
        'logo',
        'status'
    ];

    protected $casts = [
        'tanggal_berdiri' => 'date',
        'tanggal_akta' => 'date',
        'status' => 'string',
    ];

    /**
     * Scope untuk koperasi aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Get umur koperasi dalam tahun
     */
    public function getUsiaKoperasiAttribute()
    {
        return $this->tanggal_berdiri->age;
    }

    /**
     * Format tanggal berdiri
     */
    public function getTanggalBerdiriFormattedAttribute()
    {
        return $this->tanggal_berdiri->format('d F Y');
    }
}
