<?php

namespace App\Console\Commands;

use App\Console\HasCustomCommandExtensions;
use App\Libraries\Odl\OdlFetcher;
use App\Models\HourlyMeasurement;
use App\Models\Location;
use App\Models\UpdateLog;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class OdlFetchHourlyMeasurements extends Command
{
    use HasCustomCommandExtensions;

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
    protected $description = 'Fetches the Odl hourly measurement data';

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

                $measurements = $measurementSite->getHourlyMeasurements();

                foreach ($measurements as $measurement) {
                    // only add the value if it doesn't exist yet
                    $existingHourlyMeasurements = $location->hourlyMeasurements()->where('date', $measurement->getDate());

                    if ($existingHourlyMeasurements->count() == 0) {
                        $hourlyMeasurement = new HourlyMeasurement();
                        $hourlyMeasurement->value = $measurement->getValue() == 0 ? null : $measurement->getValue();
                        $hourlyMeasurement->date = $measurement->getDate();
                        $hourlyMeasurement->inspection_status = $measurement->getInspectionStatus();
                        $hourlyMeasurement->precipitation_probability = $measurement->getPrecipitationProbabilityValue();

                        $location->hourlyMeasurements()->save($hourlyMeasurement);

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
