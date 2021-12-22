<?php

namespace App\Jobs;

use App\Libraries\Odl\Features\MeasurementFeature;
use App\Models\DailyMeasurement;
use App\Models\Location;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StoreDailyMeasurementsForLocation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var CarbonPeriod
     */
    protected $datePeriod;

    /**
     * Create a new job instance.
     *
     * @param Location $location
     * @param CarbonPeriod $datePeriod
     */
    public function __construct(Location $location, CarbonPeriod $datePeriod)
    {
        $this->location = $location;
        $this->datePeriod = $datePeriod;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \DOMException
     */
    public function handle()
    {
        $numberOfNewEntries = 0;

        $odlFetcher = getOdlFetcher();

        $dailyMeasurementFeatureCollection = $odlFetcher->fetchDailyMeasurementFeatureCollection($this->location->uuid, $odlFetcher->getFilterXml($this->datePeriod));

        $dailyMeasurementFeatureCollection->features->each(function (MeasurementFeature $measurementFeature) use (&$numberOfNewEntries) {
            // only add the value if it doesn't exist yet
            $existingDailyMeasurements = $this->location->dailyMeasurements()->whereDate('date', $measurementFeature->properties->endMeasure);

            if ($existingDailyMeasurements->count() === 0) {
                $this->location->dailyMeasurements()->save(DailyMeasurement::fromMeasurementFeature($measurementFeature));

                $numberOfNewEntries = $numberOfNewEntries + 1;
            }
        });

        Log::channel('odl')->info("{$numberOfNewEntries} new daily value(s) for location \"{$this->location->postal_code} {$this->location->name}\"");
    }
}
