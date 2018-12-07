<?php

namespace App\Console\Commands;

use App\Console\HasCustomCommandExtensions;
use App\Models\DailyMeasurement;
use App\Libraries\Odl\OdlFetcher;
use App\Models\Location;
use App\Models\UpdateLog;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class OdlFetchDailyMeasurements extends Command
{
    use HasCustomCommandExtensions;

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
        $this->start();

        $odlFetcher = new OdlFetcher(env('ODL_BASE_URL'), env('ODL_USER'), env('ODL_PASSWORD'));
        $locations = Location::orderBy('name');

        try {
            foreach ($locations->get() as $location) {
                $numberOfNewEntries = 0;

                $measurementSite = $odlFetcher->getMeasurementSite($location->uuid);

                $measurements = $measurementSite->getDailyMeasurements();

                foreach ($measurements as $measurement) {
                    // only add the value if it doesn't exist yet
                    $existingDailyMeasurements = $location->dailyMeasurements()->where('date', $measurement->getDate());

                    if ($existingDailyMeasurements->count() == 0) {
                        $dailyMeasurement = new DailyMeasurement();
                        $dailyMeasurement->value = $measurement->getValue() == 0 ? null : $measurement->getValue();
                        $dailyMeasurement->date = $measurement->getDate();

                        $location->dailyMeasurements()->save($dailyMeasurement);

                        $numberOfNewEntries = $numberOfNewEntries + 1;
                    }
                }

                $this->updateLog->is_successful = true;
                $this->updateLog->number_of_new_entries = $numberOfNewEntries;

                $this->info('Fetched and stored ' . $numberOfNewEntries . ' values for the location "' . $location->postal_code . ' ' . $location->name . '"');
            }
        } catch (GuzzleException $e) {
            $this->addExceptionToUpdateLog($e);
        }

        $this->end();
    }
}
