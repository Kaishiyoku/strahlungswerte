<?php

namespace App\Console\Commands;

use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class OdlUpdateHourlyMeasurements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odl:hourly_measurements {hours=7}';

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

        $hours = filter_var($this->argument('hours'), FILTER_VALIDATE_INT);

        if (!$hours || $hours < 0) {
            $this->error('invalid number of hours entered');

            return;
        }

        $odlFetcher->updateHourlyMeasurements(CarbonPeriod::create(now()->subHours($hours)->seconds(0), now()));
    }
}
