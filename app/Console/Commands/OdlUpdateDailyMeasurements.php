<?php

namespace App\Console\Commands;

use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class OdlUpdateDailyMeasurements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odl:daily_measurements {days=2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $odlFetcher = getOdlFetcher();

        $days = filter_var($this->argument('days'), FILTER_VALIDATE_INT);

        if (!$days || $days < 0) {
            $this->error('invalid number of days entered');

            return;
        }

        $odlFetcher->updateDailyMeasurements(CarbonPeriod::create(now()->subDays($days - 1)->startOfDay(), now()));
    }
}
