<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Recalculate saldo simpanan daily at 2 AM
        $schedule->command('simpanan:recalculate-saldo')
                 ->dailyAt('02:00')
                 ->description('Recalculate saldo simpanan for all anggota')
                 ->onSuccess(function () {
                     \Log::info('Saldo simpanan recalculation completed successfully');
                 })
                 ->onFailure(function () {
                     \Log::error('Saldo simpanan recalculation failed');
                 });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
