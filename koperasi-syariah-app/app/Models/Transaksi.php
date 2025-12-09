<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'pengajuan_pembiayaan_id',
        'anggota_id',
        'jenis_transaksi',
        'jumlah',
        'keterangan',
        'status',
        'created_by'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2'
    ];

    // Relationships
    public function pengajuanPembiayaan()
    {
        return $this->belongsTo(PengajuanPembiayaan::class);
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors
    public function getJumlahFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'completed' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>',
            'failed' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Gagal</span>'
        ];

        return $statusLabels[$this->status] ?? $statusLabels['pending'];
    }

    public function getJenisTransaksiLabelAttribute()
    {
        $jenisLabels = [
            'simpanan' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Simpanan</span>',
            'angsuran' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">Angsuran</span>',
            'pencairan' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Pencairan</span>'
        ];

        return $jenisLabels[$this->jenis_transaksi] ?? $this->jenis_transaksi;
    }
}
