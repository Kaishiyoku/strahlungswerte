<?php

namespace App\Jobs;

use App\Console\HasCustomCommandExtensions;
use App\Libraries\Odl\OdlFetcher;
use App\Models\DailyMeasurement;
use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreDailyMeasurement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, HasCustomCommandExtensions;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var string
     */
    protected $signature = 'odl:fetch_daily_measurements';

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

        $numberOfNewEntries = 0;

        try {
            $measurementSite = $odlFetcher->fetchMeasurementSite($this->location->uuid);

            $measurements = $measurementSite->getDailyMeasurements();

            foreach ($measurements as $measurement) {
                // only add the value if it doesn't exist yet
                $existingDailyMeasurements = $this->location->dailyMeasurements()->where('date', $measurement->getDate());

                if ($existingDailyMeasurements->count() == 0) {
                    $dailyMeasurement = new DailyMeasurement();
                    $dailyMeasurement->value = $measurement->getValue() == 0 ? null : $measurement->getValue();
                    $dailyMeasurement->date = $measurement->getDate();

                    $this->location->dailyMeasurements()->save($dailyMeasurement);

                    $numberOfNewEntries = $numberOfNewEntries + 1;
                }
            }

            $this->updateLog->is_successful = true;
            $this->updateLog->number_of_new_entries = $numberOfNewEntries;

            Log::channel('queue')->info('Fetched and stored ' . $numberOfNewEntries . ' values for the location "' . $this->location->postal_code . ' ' . $this->location->name . '"');
        } catch (Throwable $e) {
            $this->addExceptionToUpdateLog($e);
        }

        $this->end();
    }
}
