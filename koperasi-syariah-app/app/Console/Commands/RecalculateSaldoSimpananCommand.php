<?php

namespace App\Console\Commands;

use App\Services\SaldoSimpananService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RecalculateSaldoSimpananCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simpanan:recalculate-saldo
                            {--anggota-id= : ID Anggota specific (optional)}
                            {--jenis-simpanan-id= : ID Jenis Simpanan specific (optional)}
                            {--fix : Fix incorrect saldo calculations}
                            {--limit= : Limit number of records to check (for fix mode)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate saldo simpanan for all anggota or specific anggota/jenis simpanan';

    /**
     * The saldo service instance.
     *
     * @var \App\Services\SaldoSimpananService
     */
    protected $saldoService;

    /**
     * Create a new command instance.
     *
     * @param  \App\Services\SaldoSimpananService  $saldoService
     * @return void
     */
    public function __construct(SaldoSimpananService $saldoService)
    {
        parent::__construct();
        $this->saldoService = $saldoService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('  Recalculate Saldo Simpanan');
        $this->info('========================================');
        $this->newLine();

        $startTime = microtime(true);

        try {
            // Check if fix mode
            if ($this->option('fix')) {
                return $this->fixIncorrectSaldo();
            }

            // Normal recalculate mode
            return $this->recalculateSaldo();

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            Log::error('Recalculate saldo command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Recalculate saldo for transactions
     *
     * @return int
     */
    protected function recalculateSaldo()
    {
        $startTime = microtime(true);
        $anggotaId = $this->option('anggota-id');
        $jenisSimpananId = $this->option('jenis-simpanan-id');

        // Display options
        $this->info('Options:');
        if ($anggotaId) {
            $this->line("  - Anggota ID: {$anggotaId}");
        } else {
            $this->line('  - Anggota ID: All');
        }
        if ($jenisSimpananId) {
            $this->line("  - Jenis Simpanan ID: {$jenisSimpananId}");
        } else {
            $this->line('  - Jenis Simpanan ID: All');
        }
        $this->newLine();

        // Confirm (skip if no-interaction)
        if (!$this->input->isInteractive()) {
            $this->info('Running in non-interactive mode...');
        } elseif (!$this->confirm('Do you wish to continue?')) {
            $this->warn('Operation cancelled.');
            return 0;
        }

        // Start progress bar
        $this->info('Recalculating saldo...');

        // Execute recalculation
        $result = $this->saldoService->recalculateSaldo($anggotaId, $jenisSimpananId);

        // Display results
        $this->newLine();
        $this->info('========================================');
        $this->info('  Result');
        $this->info('========================================');

        if ($result['success']) {
            $this->line("  Total Anggota Processed: {$result['anggota_processed']}");
            $this->line("  Total Records Updated: {$result['total_updated']}");

            // Display details if verbose
            if ($this->output->isVerbose() && !empty($result['details'])) {
                $this->newLine();
                $this->info('Details:');
                foreach ($result['details'] as $detail) {
                    $this->line("  - [{$detail['no_anggota']}] {$detail['nama_anggota']}");
                    foreach ($detail['updates'] as $update) {
                        $this->line("      {$update['jenis_simpanan']}: {$update['updated']} records updated");
                    }
                }
            }

            $duration = round(microtime(true) - $startTime, 2);
            $this->newLine();
            $this->info("Completed in {$duration} seconds");

            Log::info('Saldo recalculation command completed', $result);

            return 0;
        } else {
            $this->error('  Failed: ' . $result['message']);
            return 1;
        }
    }

    /**
     * Fix incorrect saldo calculations
     *
     * @return int
     */
    protected function fixIncorrectSaldo()
    {
        $startTime = microtime(true);
        $limit = $this->option('limit');

        $this->warn('Running in FIX mode...');
        $this->warn('This will check and fix incorrect saldo calculations.');
        $this->newLine();

        if ($limit) {
            $this->line("  Limit: {$limit} records");
        }
        $this->newLine();

        // Confirm (skip if no-interaction)
        if (!$this->input->isInteractive()) {
            $this->info('Running in non-interactive mode...');
        } elseif (!$this->confirm('Do you wish to continue?')) {
            $this->warn('Operation cancelled.');
            return 0;
        }

        $this->info('Checking and fixing saldo...');

        // Execute fix
        $result = $this->saldoService->fixIncorrectSaldo($limit);

        // Display results
        $this->newLine();
        $this->info('========================================');
        $this->info('  Fix Result');
        $this->info('========================================');
        $this->line("  Total Checked: {$result['total_checked']}");
        $this->line("  Total Fixed: {$result['total_fixed']}");

        if (!empty($result['errors'])) {
            $this->newLine();
            $this->error('Errors:');
            foreach ($result['errors'] as $error) {
                $this->line("  - {$error}");
            }
        }

        $duration = round(microtime(true) - $startTime, 2);
        $this->newLine();
        $this->info("Completed in {$duration} seconds");

        Log::info('Saldo fix command completed', $result);

        return 0;
    }
}
