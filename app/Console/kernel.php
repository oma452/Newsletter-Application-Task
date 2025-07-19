<?php

namespace App\Console;
use App\Console\Commands\SendDailyNewsletter;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
class Kernel extends ConsoleKernel
{
    protected $commands = [
  \App\Console\Commands\SendDailyNewsletter::class,
    \App\Console\Commands\FetchEgyptNews::class,
  
];



    protected function schedule(Schedule $schedule): void
    {Log::info('✅ schedule() method was called'); 
        file_put_contents(storage_path('logs/schedule-test.log'), now() . " - schedule() ran\n", FILE_APPEND);
 
         $schedule->command('newsletter:send')
             ->everyMinute()
               ->withoutOverlapping()
             ->appendOutputTo(storage_path('logs/schedule.log'));
             
//$schedule->command('newsletter:send')->everyMinute();

   // $schedule->command('newsletter:send')->dailyAt('9:00');
    // This tells Laravel to run php artisan newsletter:send every day at 9 AM.
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
