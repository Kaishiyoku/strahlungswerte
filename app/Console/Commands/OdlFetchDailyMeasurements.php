<?php

namespace App\Console\Commands;

use App\Jobs\ProcessDailyMeasurement;
use App\Models\Location;
use Illuminate\Console\Command;

class OdlFetchDailyMeasurements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odl:fetch_daily_measurements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the ODL daily measurement data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $locations = Location::orderBy('name');

        $locations->get()->each(function ($location) {
            ProcessDailyMeasurement::dispatch($location);
        });
    }
}
