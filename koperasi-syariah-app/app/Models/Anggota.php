<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anggota extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anggota';

    protected $fillable = [
        'no_anggota',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_hp',
        'email',
        'foto',
        'alamat_lengkap',
        'pekerjaan',
        'penghasilan',
        'no_npwp',
        'status_keanggotaan',
        'is_blacklisted',
        'blacklisted_at',
        'blacklist_reason',
        'tanggal_gabung',
        'tanggal_keluar',
        'alasan_keluar',
        'jenis_anggota',
        'user_id'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_gabung' => 'date',
        'tanggal_keluar' => 'date',
        'status_keanggotaan' => 'string',
        'jenis_anggota' => 'string',
        'is_blacklisted' => 'boolean',
        'blacklisted_at' => 'datetime',
    ];

    /**
     * Get the user associated with the anggota.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk anggota yang di-blacklist
     */
    public function scopeBlacklisted($query)
    {
        return $query->where('is_blacklisted', true);
    }

    /**
     * Scope untuk anggota yang TIDAK di-blacklist
     */
    public function scopeNotBlacklisted($query)
    {
        return $query->where('is_blacklisted', false);
    }

    /**
     * Cek apakah anggota punya tunggakan angsuran
     */
    public function hasTunggakan(): bool
    {
        return Angsuran::where('anggota_id', $this->id)
            ->whereIn('status', ['partial_bayar', 'pending'])
            ->where('tanggal_jatuh_tempo', '<', now()->subMonth())
            ->exists();
    }

    /**
     * Hitung total tunggakan
     */
    public function totalTunggakan()
    {
        return Angsuran::where('anggota_id', $this->id)
            ->whereIn('status', ['partial_bayar', 'pending'])
            ->where('tanggal_jatuh_tempo', '<', now()->subMonth())
            ->sum(\DB::raw('jumlah_angsuran - jumlah_dibayar'));
    }

    /**
     * Blacklist anggota
     */
    public function blacklist($reason = 'Tunggakan pembayaran')
    {
        $this->update([
            'is_blacklisted' => true,
            'blacklisted_at' => now(),
            'blacklist_reason' => $reason
        ]);
    }

    /**
     * Unblacklist anggota
     */
    public function unblacklist()
    {
        $this->update([
            'is_blacklisted' => false,
            'blacklisted_at' => null,
            'blacklist_reason' => null
        ]);
    }

    /**
     * Generate nomor anggota otomatis dengan format YYMM.5digit (reset setiap bulan)
     */
    public static function generateNoAnggota()
    {
        // Get current month and year in YYMM format
        $currentMonth = date('ym');

        // Find the latest anggota for current month
        $latestThisMonth = self::where('no_anggota', 'like', $currentMonth . '%')
            ->orderBy('no_anggota', 'desc')
            ->first();

        if ($latestThisMonth) {
            // Extract last 5 digits and increment
            $lastNumber = (int)substr($latestThisMonth->no_anggota, -5);
            $newNumber = $lastNumber + 1;
        } else {
            // Start with 1 if no anggota this month
            $newNumber = 1;
        }

        // Format: YYMM . 5 digit number
        return $currentMonth . '.' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Scope untuk anggota aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_keanggotaan', 'aktif');
    }

    /**
     * Scope untuk jenis anggota
     */
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_anggota', $jenis);
    }

    /**
     * Get usia anggota
     */
    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }

    /**
     * Get status keanggotaan label
     */
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Non-aktif',
            'keluar' => 'Keluar',
            'meninggal' => 'Meninggal',
        ];

        return $statuses[$this->status_keanggotaan] ?? $this->status_keanggotaan;
    }

    /**
     * Get jenis anggota label
     */
    public function getJenisLabelAttribute()
    {
        $types = [
            'biasa' => 'Anggota Biasa',
            'luar_biasa' => 'Anggota Luar Biasa',
            'kehormatan' => 'Anggota Kehormatan',
        ];

        return $types[$this->jenis_anggota] ?? $this->jenis_anggota;
    }

    /**
     * Get jenis kelamin label
     */
    public function getJenisKelaminLabelAttribute()
    {
        return $this->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Get formatted nomor anggota (sama dengan database format)
     */
    public function getNoAnggotaFormattedAttribute()
    {
        // Sama dengan database format, tidak ada formatting khusus
        return $this->no_anggota;
    }

    /**
     * Check if anggota is active
     */
    public function isActive()
    {
        return $this->status_keanggotaan === 'aktif';
    }

    /**
     * Check if anggota has left
     */
    public function hasLeft()
    {
        return $this->status_keanggotaan === 'keluar';
    }

    /**
     * Update anggota status to keluar
     */
    public function setKeluar($alasan = null)
    {
        $this->status_keanggotaan = 'keluar';
        $this->tanggal_keluar = now();
        $this->alasan_keluar = $alasan;
        $this->save();
    }

    /**
     * Reactivate anggota
     */
    public function reactivate()
    {
        $this->status_keanggotaan = 'aktif';
        $this->tanggal_keluar = null;
        $this->alasan_keluar = null;
        $this->save();
    }

    /**
     * Get periode pendaftaran dari nomor anggota
     */
    public function getPeriodePendaftaranAttribute()
    {
        if (strlen($this->no_anggota) >= 4) {
            $yearMonth = substr($this->no_anggota, 0, 4); // YYMM
            $year = '20' . substr($yearMonth, 0, 2);
            $month = substr($yearMonth, 2, 2);

            // Convert month number to month name in Indonesian
            $monthNames = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];

            return $monthNames[$month] . ' ' . $year;
        }

        return 'Tidak diketahui';
    }
}
