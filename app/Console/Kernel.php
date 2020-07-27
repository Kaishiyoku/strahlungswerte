<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\OdlFetchLocations::class,
        Commands\OdlFetchDailyMeasurements::class,
        Commands\OdlFetchHourlyMeasurements::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        $schedule->command('odl:fetch_locations')->hourlyAt(0);
        $schedule->command('odl:fetch_statistic')->dailyAt('17:00');
        $schedule->command('odl:fetch_daily_measurements')->dailyAt('02:00');
        $schedule->command('odl:fetch_hourly_measurements')->hourlyAt(0);
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
