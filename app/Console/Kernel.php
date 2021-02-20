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
        $schedule->command('backup:clean')->dailyAt('02:30');
        $schedule->command('backup:run')->dailyAt('02:40');
        $schedule->command('backup:monitor')->dailyAt('05:00');

        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        $schedule->command('odl:fetch_locations')->weeklyOn(1, '04:00'); // every week on Monday at 04:00
        $schedule->command('odl:fetch_statistic')->dailyAt('17:00');
        $schedule->command('odl:fetch_daily_measurements')->dailyAt('17:30');
        $schedule->command('odl:fetch_hourly_measurements')->everyThreeHours();
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
