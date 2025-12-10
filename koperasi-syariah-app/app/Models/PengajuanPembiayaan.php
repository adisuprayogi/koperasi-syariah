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
        'pencair_id',
        'tanggal_cair',
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
        'jaminan_file_2',
        'jaminan_file_3',
        'dokumen_lainnya',
        'dokumen_lainnya_1',
        'dokumen_lainnya_2',
        'dokumen_lainnya_3',
        'dokumen_lainnya_4',
        'dokumen_lainnya_5',
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
        'tanggal_cair' => 'datetime',
        'tanggal_jatuh_tempo' => 'date',
        'dokumen_lainnya' => 'json'
    ];

    // Static method untuk generate kode pengajuan
    public static function generateKodePengajuan($jenisPembiayaanId = null)
    {
        // Format: YY + MM + KODEJENIS + . + 4digit
        // Example: 2512PM.0001 for December 2025, Pembiayaan Murabahah

        // Get kode jenis if provided
        $kodeJenis = '';
        if ($jenisPembiayaanId) {
            $jenis = JenisPembiayaan::find($jenisPembiayaanId);
            if ($jenis) {
                // Extract first 2 characters from kodejenis (PM from PM001)
                $kodeJenis = substr($jenis->kode_jenis, 0, 2);
            }
        }

        // Default to PM if no jenis found
        if (empty($kodeJenis)) {
            $kodeJenis = 'PM';
        }

        // Create date format: YYMM (Year+Month)
        $dateMonth = now()->format('ym');

        // Get last pengajuan for this jenis in this month
        // Pattern: 2512PM.% (YYMM + KODEJENIS + . + anything)
        $pattern = $dateMonth . $kodeJenis . '.%';
        $lastPengajuan = self::where('kode_pengajuan', 'like', $pattern)
                             ->whereMonth('created_at', now()->month)
                             ->whereYear('created_at', now()->year)
                             ->orderBy('created_at', 'desc')
                             ->first();

        if ($lastPengajuan) {
            // Extract last 4 digits for sequence number
            $parts = explode('.', $lastPengajuan->kode_pengajuan);
            $lastNumber = intval(end($parts));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: YYMMKODEJENIS.4digit
        // Example: 2512PM.0001 for Pembiayaan Murabahah in December 2025
        return $dateMonth . $kodeJenis . '.' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
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

    public function getJumlahCairFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_cair, 0, ',', '.');
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

    public function pencair()
    {
        return $this->belongsTo(User::class, 'pencair_id');
    }

    public function angsurans()
    {
        return $this->hasMany(Angsuran::class);
    }

    public function totalDibayar()
    {
        return $this->angsurans()->where('status', 'terbayar')->sum('jumlah_angsuran');
    }

    public function totalPokokDibayar()
    {
        return $this->angsurans()->where('status', 'terbayar')->sum('jumlah_pokok');
    }

    public function totalMarginDibayar()
    {
        return $this->angsurans()->where('status', 'terbayar')->sum('jumlah_margin');
    }

    public function totalDenda()
    {
        return $this->angsurans()->sum('denda');
    }

    public function sisaPokok()
    {
        return max(0, $this->jumlah_pengajuan - $this->totalPokokDibayar());
    }

    public function sisaMargin()
    {
        return max(0, $this->jumlah_margin - $this->totalMarginDibayar());
    }

    public function sisaTotal()
    {
        return $this->sisaPokok() + $this->sisaMargin();
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

    /**
     * Get all jaminan files
     */
    public function getAllJaminanFilesAttribute()
    {
        $files = [];

        if ($this->jaminan_file) {
            $files[] = $this->jaminan_file;
        }
        if ($this->jaminan_file_2) {
            $files[] = $this->jaminan_file_2;
        }
        if ($this->jaminan_file_3) {
            $files[] = $this->jaminan_file_3;
        }

        return $files;
    }

    /**
     * Get all dokumen lainnya files
     */
    public function getAllDokumenLainnyaFilesAttribute()
    {
        $files = [];

        for ($i = 1; $i <= 5; $i++) {
            $field = "dokumen_lainnya_{$i}";
            if ($this->$field) {
                $files[] = $this->$field;
            }
        }

        // Also check legacy dokumen_lainnya (JSON format)
        if ($this->dokumen_lainnya) {
            if (is_string($this->dokumen_lainnya)) {
                $decoded = json_decode($this->dokumen_lainnya, true);
                if (is_array($decoded)) {
                    $files = array_merge($files, $decoded);
                } else {
                    $files[] = $this->dokumen_lainnya;
                }
            }
        }

        return array_unique($files);
    }

    /**
     * Get all uploaded files
     */
    public function getAllFilesAttribute()
    {
        $allFiles = [];

        // Required documents
        if ($this->ktp_file) {
            $allFiles[] = ['file' => $this->ktp_file, 'label' => 'Scan KTP', 'type' => 'required'];
        }

        // Optional documents
        $optionalDocs = [
            'kk_file' => 'Scan KK',
            'slip_gaji_file' => 'Slip Gaji',
            'proposal_file' => 'Proposal Bisnis'
        ];

        foreach ($optionalDocs as $field => $label) {
            if ($this->$field) {
                $allFiles[] = ['file' => $this->$field, 'label' => $label, 'type' => 'optional'];
            }
        }

        // Jaminan files
        foreach ($this->all_jaminan_files as $index => $file) {
            $allFiles[] = [
                'file' => $file,
                'label' => 'Dokumen Jaminan ' . ($index + 1),
                'type' => 'jaminan'
            ];
        }

        // Other documents
        foreach ($this->all_dokumen_lainnya_files as $index => $file) {
            $allFiles[] = [
                'file' => $file,
                'label' => 'Dokumen Lainnya ' . ($index + 1),
                'type' => 'lainnya'
            ];
        }

        return $allFiles;
    }
}
