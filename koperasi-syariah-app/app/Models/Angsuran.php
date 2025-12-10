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
        'status',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'tanggal_jatuh_tempo_akhir',
        'denda',
        'persentase_denda',
        'hari_terlambat',
        'keterangan',
        'bukti_pembayaran',
        'bukti_pembayaran_original',
        'dibayar_oleh'
    ];

    protected $casts = [
        'jumlah_pokok' => 'decimal:2',
        'jumlah_margin' => 'decimal:2',
        'jumlah_angsuran' => 'decimal:2',
        'denda' => 'decimal:2',
        'persentase_denda' => 'decimal:2',
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
            'terlambat' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Terlambat</span>'
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

    /**
     * Generate jadwal angsuran untuk pengajuan pembiayaan
     */
    public static function generateJadwalAngsuran($pengajuan)
    {
        $angsurans = [];
        $tanggalJatuhPertama = $pengajuan->tanggal_jatuh_tempo_pertama ?: now()->addMonth();

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

        for ($i = 1; $i <= $pengajuan->tenor; $i++) {
            $tanggalJatuhTempo = Carbon::parse($tanggalJatuhPertama)->addMonths($i - 1);

            // Generate unique kode untuk setiap angsuran
            $currentNumber = $startNumber + $i - 1;
            $kodeAngsuran = 'AGS' . $date . '.' . str_pad($currentNumber, 4, '0', STR_PAD_LEFT);

            // Double-check if this kode already exists (extra safety)
            if (self::where('kode_angsuran', $kodeAngsuran)->exists()) {
                // If exists, find the next available number
                do {
                    $currentNumber++;
                    $kodeAngsuran = 'AGS' . $date . '.' . str_pad($currentNumber, 4, '0', STR_PAD_LEFT);
                } while (self::where('kode_angsuran', $kodeAngsuran)->exists());
            }

            $angsuran = [
                'kode_angsuran' => $kodeAngsuran,
                'pengajuan_pembiayaan_id' => $pengajuan->id,
                'anggota_id' => $pengajuan->anggota_id,
                'angsuran_ke' => $i,
                'jumlah_pokok' => $pengajuan->angsuran_pokok,
                'jumlah_margin' => $pengajuan->angsuran_margin,
                'jumlah_angsuran' => $pengajuan->total_angsuran,
                'status' => 'pending',
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $angsurans[] = $angsuran;
        }

        return self::insert($angsurans);
    }
}
