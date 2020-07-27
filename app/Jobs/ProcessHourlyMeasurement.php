<?php

namespace App\Jobs;

use App\Console\HasCustomCommandExtensions;
use App\Libraries\Odl\OdlFetcher;
use App\Models\HourlyMeasurement;
use App\Models\Location;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessHourlyMeasurement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, HasCustomCommandExtensions;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var OdlFetcher
     */
    protected $odlFetcher;

    /**
     * @var string
     */
    protected $signature = 'odl:fetch_hourly_measurements';

    /**
     * Create a new job instance.
     *
     * @param Location $location
     */
    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->start();

        $odlFetcher = new OdlFetcher(env('ODL_BASE_URL'), env('ODL_USER'), env('ODL_PASSWORD'));

        try {
            $numberOfNewEntries = 0;
            $measurementSite = $odlFetcher->getMeasurementSite($this->location->uuid);

            $measurements = $measurementSite->getHourlyMeasurements();

            foreach ($measurements as $measurement) {
                // only add the value if it doesn't exist yet
                $existingHourlyMeasurements = $this->location->hourlyMeasurements()->where('date', $measurement->getDate());

                if ($existingHourlyMeasurements->count() == 0) {
                    $hourlyMeasurement = new HourlyMeasurement();
                    $hourlyMeasurement->value = $measurement->getValue() == 0 ? null : $measurement->getValue();
                    $hourlyMeasurement->date = $measurement->getDate();
                    $hourlyMeasurement->inspection_status = $measurement->getInspectionStatus();
                    $hourlyMeasurement->precipitation_probability = $measurement->getPrecipitationProbabilityValue();

                    $this->location->hourlyMeasurements()->save($hourlyMeasurement);

                    $numberOfNewEntries = $numberOfNewEntries + 1;
                }
            }

            $this->updateLog->is_successful = true;
            $this->updateLog->number_of_new_entries = $numberOfNewEntries;

            Log::channel('queue')->info('Fetched and stored ' . $numberOfNewEntries . ' values for the location "' . $this->location->postal_code . ' ' . $this->location->name . '"');
        } catch (GuzzleException $e) {
            $this->addExceptionToUpdateLog($e);
        }

        $this->end();
    }
}
