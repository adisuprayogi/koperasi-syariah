<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PengajuanPembiayaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_pengajuan',
        'anggota_id',
        'jenis_pembiayaan_id',
        'jumlah_pengajuan',
        'tenor',
        'margin_percent',
        'jumlah_margin',
        'angsuran_pokok',
        'angsuran_margin',
        'total_angsuran',
        'tujuan_pembiayaan',
        'deskripsi',
        'status',
        'verified_by',
        'verified_at',
        'catatan_verifikasi',
        'approved_by',
        'approved_at',
        'catatan_approval',
        'keputusan',
        'disbursed_by',
        'disbursed_at',
        'jumlah_cair',
        'no_rekening',
        'atas_nama',
        'bukti_transfer',
        'bukti_pencairan',
        'bukti_pencairan_original',
        'tanggal_jatuh_tempo_pertama',
        'keterangan_jatuh_tempo',
        'ktp_file',
        'kk_file',
        'slip_gaji_file',
        'proposal_file',
        'jaminan_file',
        'dokumen_lainnya',
        'alasan_penolakan',
        'tanggal_jatuh_tempo'
    ];

    protected $casts = [
        'jumlah_pengajuan' => 'decimal:2',
        'margin_percent' => 'decimal:2',
        'jumlah_margin' => 'decimal:2',
        'angsuran_pokok' => 'decimal:2',
        'angsuran_margin' => 'decimal:2',
        'total_angsuran' => 'decimal:2',
        'jumlah_cair' => 'decimal:2',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'tanggal_jatuh_tempo' => 'date',
        'dokumen_lainnya' => 'json'
    ];

    // Static method untuk generate kode pengajuan
    public static function generateKodePengajuan()
    {
        $date = now()->format('ym');
        $lastPengajuan = self::whereMonth('created_at', now()->month)
                             ->whereYear('created_at', now()->year)
                             ->orderBy('created_at', 'desc')
                             ->first();

        if ($lastPengajuan) {
            $lastNumber = intval(substr($lastPengajuan->kode_pengajuan, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'PFM' . $date . '.' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'draft' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>',
            'diajukan' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Diajukan</span>',
            'verifikasi' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Verifikasi</span>',
            'approved' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>',
            'cair' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Cair</span>',
            'lunas' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">Lunas</span>',
            'batal' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Batal</span>',
        ];

        return $statusLabels[$this->status] ?? $statusLabels['draft'];
    }

    public function getTujuanPembiayaanLabelAttribute()
    {
        $labels = [
            'modal_kerja' => 'Modal Kerja',
            'investasi' => 'Investasi',
            'konsumtif' => 'Konsumtif',
            'pendidikan' => 'Pendidikan',
            'renovasi' => 'Renovasi',
            'lainnya' => 'Lainnya'
        ];

        return $labels[$this->tujuan_pembiayaan] ?? $this->tujuan_pembiayaan;
    }

    public function getJumlahPengajuanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_pengajuan, 0, ',', '.');
    }

    public function getTotalAngsuranFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_angsuran, 0, ',', '.');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    // Relationships
    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function jenisPembiayaan()
    {
        return $this->belongsTo(JenisPembiayaan::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Pengurus::class, 'verified_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Pengurus::class, 'approved_by');
    }

    public function disbursedBy()
    {
        return $this->belongsTo(Pengurus::class, 'disbursed_by');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByAnggota($query, $anggotaId)
    {
        return $query->where('anggota_id', $anggotaId);
    }

    public function scopePendingVerification($query)
    {
        return $query->whereIn('status', ['diajukan', 'verifikasi']);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'verifikasi');
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['approved', 'cair', 'lunas']);
    }

    public function scopeForDisbursement($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Accessors untuk format tanggal
     */
    public function getTanggalJatuhTempoPertamaFormattedAttribute()
    {
        return $this->tanggal_jatuh_tempo_pertama
            ? \Carbon\Carbon::parse($this->tanggal_jatuh_tempo_pertama)->format('d/m/Y')
            : '-';
    }

    /**
     * Hitung tanggal jatuh tempo berikutnya
     */
    public function getJatuhTempoBerikutnyaAttribute()
    {
        if (!$this->tanggal_jatuh_tempo_pertama) {
            return null;
        }

        // Jika sudah dicairkan, hitung dari tanggal cair
        $startDate = $this->tanggal_cair ?? $this->tanggal_jatuh_tempo_pertama;

        // Tambahkan 1 bulan
        return \Carbon\Carbon::parse($startDate)->addMonth();
    }
}
