<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register your Artisan commands here.
     */
    protected $commands = [
        \App\Console\Commands\TryPlannedProduction::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('production:try-planned')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('generate:forecasts')->monthly();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
