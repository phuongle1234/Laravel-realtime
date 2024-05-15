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
    protected $commands = [
        Commands\SendNotification::class,
        Commands\Request::class
    ];

    protected function scheduleTimezone()
    {
        return env('APP_TIMEZONE');
    }

    protected function schedule(Schedule $schedule)
    {

        $schedule->command('send:notification')->everyMinute();
        $schedule->command('request:action')->everyMinute();

        $schedule->command('request:urgent')->everyMinute();
        // $schedule->command('request:complete')->everyMinute();
        // $schedule->command('request:is_late')->everyMinute();

        $schedule->command('transcode:video')->everyMinute();
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
