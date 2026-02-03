<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // ✅ Auto-close expired job postings - Jalankan setiap hari jam 00:01 (1 menit setelah tengah malam)
        // Job akan ditutup jika sudah H+1 dari tanggal terakhir job dates
        $schedule->command('jobs:close-expired')
            ->dailyAt('00:01')
            ->withoutOverlapping()
            ->onSuccess(function () {
                Log::info('Auto-close job postings completed successfully');
            })
            ->onFailure(function () {
                Log::error('Auto-close job postings failed');
            });

        // ✅ Opsional: Jalankan juga setiap jam untuk memastikan
        // Uncomment jika ingin lebih responsif
        // $schedule->command('jobs:close-expired')
        //     ->hourly()
        //     ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
