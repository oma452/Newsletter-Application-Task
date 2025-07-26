<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\SendNewsletter::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        Log::info('✅ schedule() method was called'); 
        file_put_contents(storage_path('logs/schedule-test.log'), now() . " - schedule() ran\n", FILE_APPEND);
 
        // Send newsletter every day at 9 AM
        $schedule->command('newsletter:send')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/newsletter.log'));
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
