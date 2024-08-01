<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\AutoRenewSubscriptionCron;
use App\Console\Commands\GoogleCalendarToken;
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
         $schedule->command('subscription:cron')->dailyAt('00:01');
         $schedule->command('googleCalenderToken:cron')->everyThirtyMinutes();
        // $schedule->command('appointment:cron')->everyFiveMinutes();


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
