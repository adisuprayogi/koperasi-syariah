<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Angsuran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_angsuran',
        'pengajuan_pembiayaan_id',
        'anggota_id',
        'angsuran_ke',
        'jumlah_pokok',
        'jumlah_margin',
        'jumlah_angsuran',
        'jumlah_dibayar',
        'sisa_dibawa',
        'status',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'tanggal_jatuh_tempo_akhir',
        'denda',
        'persentase_denda',
        'hari_terlambat',
        'keterangan',
        'catatan',
        'is_perpanjangan',
        'bukti_pembayaran',
        'bukti_pembayaran_original',
        'dibayar_oleh'
    ];

    protected $casts = [
        'jumlah_pokok' => 'decimal:2',
        'jumlah_margin' => 'decimal:2',
        'jumlah_angsuran' => 'decimal:2',
        'jumlah_dibayar' => 'decimal:2',
        'sisa_dibawa' => 'decimal:2',
        'denda' => 'decimal:2',
        'persentase_denda' => 'decimal:2',
        'is_perpanjangan' => 'boolean',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
        'tanggal_jatuh_tempo_akhir' => 'date',
        'hari_terlambat' => 'integer',
        'tanggal_bayar' => 'date'
    ];

    // Static method untuk generate kode angsuran
    public static function generateKodeAngsuran()
    {
        $date = now()->format('ym');
        $lastAngsuran = self::whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year)
                              ->orderBy('created_at', 'desc')
                              ->first();

        if ($lastAngsuran) {
            $lastNumber = intval(substr($lastAngsuran->kode_angsuran, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'AGS' . $date . '.' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>',
            'terbayar' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Terbayar</span>',
            'terlambat' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Terlambat</span>',
            'lunas_lebih_cepat' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Lunas Lebih Cepat</span>',
            'partial_bayar' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Sebagian</span>'
        ];

        return $statusLabels[$this->status] ?? $statusLabels['pending'];
    }

    public function getJumlahPokokFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_pokok, 0, ',', '.');
    }

    public function getJumlahMarginFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_margin, 0, ',', '.');
    }

    public function getJumlahAngsuranFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_angsuran, 0, ',', '.');
    }

    public function getDendaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->denda, 0, ',', '.');
    }

    public function getTotalTerbayarFormattedAttribute()
    {
        $total = $this->jumlah_angsuran + $this->denda;
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    public function getTanggalJatuhTempoFormattedAttribute()
    {
        return $this->tanggal_jatuh_tempo ? Carbon::parse($this->tanggal_jatuh_tempo)->format('d M Y') : '-';
    }

    public function getTanggalBayarFormattedAttribute()
    {
        return $this->tanggal_bayar ? Carbon::parse($this->tanggal_bayar)->format('d M Y') : '-';
    }

    public function getJumlahDibayarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_dibayar, 0, ',', '.');
    }

    public function getSisaDibawaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->sisa_dibawa, 0, ',', '.');
    }

    public function getHariTersisaAttribute()
    {
        if ($this->status == 'terbayar') {
            return 0;
        }

        $today = now()->startOfDay();
        $dueDate = Carbon::parse($this->tanggal_jatuh_tempo)->startOfDay();

        if ($today->gt($dueDate)) {
            // Terlambat
            return $today->diffInDays($dueDate);
        } else {
            // Masuk tempo
            return $dueDate->diffInDays($today);
        }
    }

    public function getKeterlambatAttribute()
    {
        if ($this->status == 'terbayar') {
            return '-';
        }

        $today = now()->startOfDay();
        $dueDate = Carbon::parse($this->tanggal_jatuh_tempo)->startOfDay();

        if ($today->gt($dueDate)) {
            return $today->diffInDays($dueDate) . ' hari';
        } else {
            return 'Tidak ada';
        }
    }

    // Relationships
    public function pengajuanPembiayaan()
    {
        return $this->belongsTo(PengajuanPembiayaan::class);
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function dibayarOleh()
    {
        return $this->belongsTo(User::class, 'dibayar_oleh');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'pengajuan_pembiayaan_id', 'pengajuan_pembiayaan_id')
                    ->where('jenis_transaksi', 'angsuran');
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

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeTerbayar($query)
    {
        return $query->where('status', 'terbayar');
    }

    public function scopeTerlambat($query)
    {
        return $query->where('status', 'terlambat');
    }

    public function scopeOverdue($query)
    {
        return $query->where('tanggal_jatuh_tempo', '<', now()->format('Y-m-d'))
                     ->where('status', '!=', 'terbayar');
    }

    public function scopeNotTerbayar($query)
    {
        return $query->where('status', '!=', 'terbayar');
    }

    public function scopeLunasLebihCepat($query)
    {
        return $query->where('status', 'lunas_lebih_cepat');
    }

    public function scopePartialBayar($query)
    {
        return $query->where('status', 'partial_bayar');
    }

    public function scopePerpanjangan($query)
    {
        return $query->where('is_perpanjangan', true);
    }

    /**
     * Helper: Cek apakah angsuran ini bisa di-partial payment
     */
    public function canPartialPayment(): bool
    {
        return $this->status == 'pending' && $this->jumlah_dibayar == 0;
    }

    /**
     * Helper: Hitung sisa yang harus dibayar
     */
    public function getSisaHarusDibayarAttribute()
    {
        return $this->jumlah_angsuran - $this->jumlah_dibayar;
    }

    /**
     * Helper: Proses partial payment
     * Returns true if fully paid, false if partial
     */
    public function processPartialPayment($jumlahBayar): bool
    {
        $totalHarusBayar = $this->jumlah_angsuran;
        $sisaSetelahBayar = $totalHarusBayar - $jumlahBayar;

        if ($sisaSetelahBayar <= 0) {
            // Lunas penuh
            $this->update([
                'status' => 'terbayar',
                'jumlah_dibayar' => $totalHarusBayar,
                'sisa_dibawa' => 0,
                'tanggal_bayar' => now()
            ]);
            return true;
        } else {
            // Partial payment
            $this->update([
                'status' => 'partial_bayar',
                'jumlah_dibayar' => $jumlahBayar,
                'sisa_dibawa' => $sisaSetelahBayar,
                'tanggal_bayar' => now()
            ]);
            return false;
        }
    }

    /**
     * Generate jadwal angsuran untuk pengajuan pembiayaan
     * Supports: flat, menurun (declining), menaik (stepped)
     */
    public static function generateJadwalAngsuran($pengajuan)
    {
        // Check if angsuran already exists for this pengajuan
        $existingCount = self::where('pengajuan_pembiayaan_id', $pengajuan->id)->count();
        if ($existingCount > 0) {
            // Return true if angsuran already exists, skip generation
            return true;
        }

        $angsurans = [];
        $tanggalJatuhPertama = $pengajuan->tanggal_jatuh_tempo_pertama ?: now()->addMonth();
        $tipeAngsuran = $pengajuan->tipe_angsuran ?? 'flat';
        $tenor = (int)$pengajuan->tenor;
        $jumlahPengajuan = (float)$pengajuan->jumlah_pengajuan;

        // Hitung berdasarkan tipe angsuran
        if ($tipeAngsuran == 'flat') {
            // FLAT: Tetap setiap bulan
            $pokok = $jumlahPengajuan / $tenor;
            $margin = (float)$pengajuan->angsuran_margin;
            for ($i = 1; $i <= $tenor; $i++) {
                $angsurans[] = [
                    'pokok' => $pokok,
                    'margin' => $margin,
                    'total' => $pokok + $margin
                ];
            }
        } elseif ($tipeAngsuran == 'menurun') {
            // MENURUN (Declining): Pokok tetap, margin berkurang
            $pokok = $jumlahPengajuan / $tenor;
            $totalMargin = (float)$pengajuan->jumlah_margin;
            // Margin per bulan = total margin / tenor
            $marginPerBulan = $totalMargin / $tenor;

            $sisaPinjaman = $jumlahPengajuan;
            $marginPercent = (float)$pengajuan->margin_percent;

            for ($i = 1; $i <= $tenor; $i++) {
                // Margin = sisa pinjaman × (margin% / 12) untuk bulan ini
                $margin = $sisaPinjaman * ($marginPercent / 100 / 12);
                $angsurans[] = [
                    'pokok' => $pokok,
                    'margin' => $margin,
                    'total' => $pokok + $margin
                ];
                $sisaPinjaman -= $pokok;
            }
        } elseif ($tipeAngsuran == 'menaik') {
            // MENAIK (Stepped): Mulai kecil, makin besar
            // Divide tenor into 3 phases
            $phase1 = ceil($tenor / 3); // 1/3 awal
            $phase2 = ceil($tenor / 3); // 1/3 tengah
            $phase3 = $tenor - $phase1 - $phase2; // sisa

            $pokok = $jumlahPengajuan / $tenor;
            $totalMargin = (float)$pengajuan->jumlah_margin;

            // Phase 1: 50% dari normal margin
            $margin1 = ($totalMargin / $tenor) * 0.5;
            // Phase 2: 100% dari normal margin
            $margin2 = ($totalMargin / $tenor) * 1.0;
            // Phase 3: 150% dari normal margin (untuk menutup deficit)
            $margin3 = ($totalMargin / $tenor) * 1.5;

            for ($i = 1; $i <= $tenor; $i++) {
                if ($i <= $phase1) {
                    $margin = $margin1;
                } elseif ($i <= $phase1 + $phase2) {
                    $margin = $margin2;
                } else {
                    $margin = $margin3;
                }
                $angsurans[] = [
                    'pokok' => $pokok,
                    'margin' => $margin,
                    'total' => $pokok + $margin
                ];
            }
        }

        // Get the starting number for this month
        $date = now()->format('ym');

        // Get all existing angsuran codes for this month to find the highest number
        $existingCodes = self::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->pluck('kode_angsuran')
                            ->toArray();

        $maxNumber = 0;
        foreach ($existingCodes as $code) {
            // Extract number from kode like 'AGS2512.0024'
            $parts = explode('.', $code);
            if (isset($parts[1])) {
                $number = intval($parts[1]);
                if ($number > $maxNumber) {
                    $maxNumber = $number;
                }
            }
        }

        $startNumber = $maxNumber + 1;
        $insertData = [];

        for ($i = 1; $i <= $tenor; $i++) {
            $tanggalJatuhTempo = Carbon::parse($tanggalJatuhPertama)->addMonths($i - 1);

            // Generate unique kode untuk setiap angsuran
            $currentNumber = $startNumber + $i - 1;
            $kodeAngsuran = 'AGS' . $date . '.' . str_pad($currentNumber, 4, '0', STR_PAD_LEFT);

            // Triple-check if this kode already exists (extra safety for concurrent imports)
            $maxAttempts = 100;
            $attempts = 0;
            while (self::where('kode_angsuran', $kodeAngsuran)->exists() && $attempts < $maxAttempts) {
                $currentNumber++;
                $kodeAngsuran = 'AGS' . $date . '.' . str_pad($currentNumber, 4, '0', STR_PAD_LEFT);
                $attempts++;
            }

            $data = [
                'kode_angsuran' => $kodeAngsuran,
                'pengajuan_pembiayaan_id' => $pengajuan->id,
                'anggota_id' => $pengajuan->anggota_id,
                'angsuran_ke' => $i,
                'jumlah_pokok' => $angsurans[$i - 1]['pokok'],
                'jumlah_margin' => $angsurans[$i - 1]['margin'],
                'jumlah_angsuran' => $angsurans[$i - 1]['total'],
                'status' => 'pending',
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $insertData[] = $data;
        }

        // Insert one by one to handle potential duplicates better
        $successCount = 0;
        $skipCount = 0;

        foreach ($insertData as $angsuranData) {
            try {
                // Double-check again before insert
                if (!self::where('kode_angsuran', $angsuranData['kode_angsuran'])->exists()) {
                    self::insert($angsuranData);
                    $successCount++;
                } else {
                    $skipCount++;
                    \Log::warning("Angsuran dengan kode {$angsuranData['kode_angsuran']} sudah ada, dilewati.");
                }
            } catch (\Illuminate\Database\QueryException $e) {
                // If duplicate, log and continue
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $skipCount++;
                    \Log::warning("Duplicate angsuran {$angsuranData['kode_angsuran']}, dilewati.");
                } else {
                    // Re-throw other exceptions
                    throw $e;
                }
            }
        }

        // Return true if at least one angsuran was created, or all were skipped (already exists)
        return $successCount > 0 || $skipCount === count($insertData);
    }
}
