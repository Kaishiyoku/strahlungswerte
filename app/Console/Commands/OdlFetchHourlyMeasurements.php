<?php

namespace App\Console\Commands;

use App\Jobs\ProcessHourlyMeasurement;
use App\Models\Location;
use Illuminate\Console\Command;

class OdlFetchHourlyMeasurements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odl:fetch_hourly_measurements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the ODL hourly measurement data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $locations = Location::orderBy('name');

        $locations->get()->each(function ($location) {
            ProcessHourlyMeasurement::dispatch($location);
        });
    }
}
