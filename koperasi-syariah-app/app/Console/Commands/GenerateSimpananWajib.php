<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\TransaksiSimpanan;
use App\Models\Pengurus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GenerateSimpananWajib extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:simpanan-wajib {--month=} {--year=} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate simpanan wajib bulanan untuk semua anggota aktif';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $month = $this->option('month') ?? now()->month;
        $year = $this->option('year') ?? now()->year;
        $force = $this->option('force');

        $this->info("🚀 Memulai generate simpanan wajib untuk bulan: {$month}-{$year}");
        $this->info("Force mode: " . ($force ? 'YES' : 'NO'));

        try {
            // Get jenis simpanan wajib
            $jenisWajib = JenisSimpanan::where('tipe_simpanan', 'wajib')
                                       ->where('status', 1)
                                       ->first();

            if (!$jenisWajib) {
                $this->error('❌ Jenis simpanan wajib tidak ditemukan!');
                return 1;
            }

            $this->info("✅ Jenis simpanan wajib: {$jenisWajib->nama_simpanan}");
            $this->info("💰 Jumlah wajib: Rp " . number_format($jenisWajib->minimal_setor, 0, ',', '.'));

            // Get current pengurus for verification
            $pengurus = Pengurus::where('status', 'aktif')
                                 ->where('user_id', auth()->id() ?? 1)
                                 ->first() ?? Pengurus::where('status', 'aktif')->first();

            if (!$pengurus) {
                $this->error('❌ Tidak ada pengurus aktif!');
                return 1;
            }

            // Get anggota aktif
            $anggotaAktif = Anggota::where('status_keanggotaan', 'aktif')
                                   ->where('tanggal_gabung', '<=', now())
                                   ->get();

            $totalAnggota = $anggotaAktif->count();
            $this->info("👥 Total anggota aktif: {$totalAnggota}");

            if ($totalAnggota === 0) {
                $this->warn('⚠️  Tidak ada anggota aktif untuk dibuatkan simpanan wajib.');
                return 0;
            }

            $processedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            foreach ($anggotaAktif as $anggota) {
                try {
                    // Check if simpanan wajib sudah ada untuk bulan ini
                    $existingTransaction = TransaksiSimpanan::where('anggota_id', $anggota->id)
                                                            ->where('jenis_simpanan_id', $jenisWajib->id)
                                                            ->whereMonth('tanggal_transaksi', $month)
                                                            ->whereYear('tanggal_transaksi', $year)
                                                            ->first();

                    if ($existingTransaction && !$force) {
                        $this->line("⏭  Skip: {$anggota->nama_lengkap} ({$anggota->no_anggota}) - Sudah ada");
                        $skippedCount++;
                        continue;
                    }

                    // Create atau update transaksi
                    DB::beginTransaction();

                    $tanggalTransaksi = Carbon::create($year, $month, 1);

                    // Jika sudah ada, update. Jika belum, create baru
                    if ($existingTransaction) {
                        $existingTransaction->update([
                            'jumlah' => $jenisWajib->minimal_setor,
                            'keterangan' => 'Update otomatis simpanan wajib bulanan',
                            'pengurus_id' => $pengurus->id,
                            'saldo_sebelumnya' => $existingTransaction->saldo_sebelumnya,
                            'saldo_setelahnya' => $existingTransaction->saldo_sebelumnya + $jenisWajib->minimal_setor,
                            'catatan_verifikasi' => 'Diupdate otomatis pada ' . now()->format('d M Y H:i'),
                            'verified_at' => now(),
                            'verified_by' => $pengurus->id,
                        ]);
                    } else {
                        // Get saldo terakhir
                        $lastSaldo = TransaksiSimpanan::where('anggota_id', $anggota->id)
                                                     ->where('jenis_simpanan_id', $jenisWajib->id)
                                                     ->where('status', 'verified')
                                                     ->orderBy('created_at', 'desc')
                                                     ->first();

                        $saldoBefore = $lastSaldo ? $lastSaldo->saldo_setelahnya : 0;

                        TransaksiSimpanan::create([
                            'kode_transaksi' => TransaksiSimpanan::generateKodeTransaksi('setor'),
                            'anggota_id' => $anggota->id,
                            'jenis_simpanan_id' => $jenisWajib->id,
                            'pengurus_id' => $pengurus->id,
                            'jenis_transaksi' => 'setor',
                            'jumlah' => $jenisWajib->minimal_setor,
                            'tanggal_transaksi' => $tanggalTransaksi,
                            'keterangan' => 'Auto-generate simpanan wajib bulanan (' . now()->format('F Y') . ')',
                            'saldo_sebelumnya' => $saldoBefore,
                            'saldo_setelahnya' => $saldoBefore + $jenisWajib->minimal_setor,
                            'status' => 'verified',
                            'verified_at' => now(),
                            'verified_by' => $pengurus->id,
                        ]);
                    }

                    DB::commit();
                    $this->line("✅ {$anggota->nama_lengkap} ({$anggota->no_anggota}) - Rp " . number_format($jenisWajib->minimal_setor, 0, ',', '.'));
                    $processedCount++;

                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->error("❌ Error dengan {$anggota->nama_lengkap}: " . $e->getMessage());
                    $errorCount++;
                }
            }

            // Summary
            $this->newLine();
            $this->info("📊 Summary Generate Simpanan Wajib:");
            $this->info("✅ Berhasil: {$processedCount} anggota");
            if ($skippedCount > 0) {
                $this->info("⏭  Dilewati: {$skippedCount} anggota (sudah ada)");
            }
            if ($errorCount > 0) {
                $this->error("❌ Error: {$errorCount} anggota");
            }

            $totalAmount = $processedCount * $jenisWajib->minimal_setor;
            $this->newLine();
            $this->info("💰 Total simpanan yang digenerate: Rp " . number_format($totalAmount, 0, ',', '.'));

            // Log to database log
            Log::info('Simpanan Wajib Generated', [
                'month' => $month,
                'year' => $year,
                'processed_count' => $processedCount,
                'total_amount' => $totalAmount,
                'skipped_count' => $skippedCount,
                'error_count' => $errorCount,
                'executed_by' => auth()->user()->name ?? 'System'
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Fatal error: ' . $e->getMessage());
            Log::error('Generate Simpanan Wajib Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}
