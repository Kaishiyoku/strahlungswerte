<?php

namespace App\Console\Commands;

use App\Models\DailyMeasurement;
use App\Libraries\Odl\OdlFetcher;
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
    protected $description = 'Fetches the Odl daily measurement data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $odlFetcher = new OdlFetcher(env('ODL_BASE_URL'), env('ODL_USER'), env('ODL_PASSWORD'));

        $locations = Location::orderBy('name');

        foreach ($locations->get() as $location) {
            $numberOfNewValues = 0;
            $measurementSite = $odlFetcher->getMeasurementSite($location->uuid);

            $measurements = $measurementSite->getDailyMeasurements();

            foreach ($measurements as $measurement) {
                // only add the value if it doesn't exist yet
                $existingDailyMeasurements = $location->dailyMeasurements()->where('date', $measurement->getDate());

                if ($existingDailyMeasurements->count() == 0) {
                    $numberOfNewValues = $numberOfNewValues + 1;

                    $dailyMeasurement = new DailyMeasurement();
                    $dailyMeasurement->value = $measurement->getValue();
                    $dailyMeasurement->date = $measurement->getDate();

                    $location->dailyMeasurements()->save($dailyMeasurement);
                }
            }

            $this->info('Fetched and stored ' . $numberOfNewValues . ' values for the location "' . $location->postal_code . ' ' . $location->name . '"');
        }
    }
}
