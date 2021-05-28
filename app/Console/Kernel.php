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
        //
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

        collect(['05:00', '11:00', '17:00', '23:00'])->each(function ($time) use ($schedule) {
            $schedule->command('odl:update')->dailyAt($time);
        });

        if (config('odl.odl_cleanup_enabled')) {
            $schedule->command('odl:cleanup')->weeklyOn(0, '02:00');
        }
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
