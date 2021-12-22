<?php

namespace App\Jobs;

use App\Libraries\Odl\Features\MeasurementFeature;
use App\Models\DailyMeasurement;
use App\Models\HourlyMeasurement;
use App\Models\Location;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class StoreHourlyMeasurementsForLocation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     */
    public function handle()
    {
        $numberOfNewEntries = 0;

        $odlFetcher = getOdlFetcher();

        $hourlyMeasurementFeatureCollection = $odlFetcher->fetchHourlyMeasurementFeatureCollection($this->location->uuid, $odlFetcher->getFilterXml($this->datePeriod));

        $hourlyMeasurementFeatureCollection->features->each(function (MeasurementFeature $measurementFeature) use (&$numberOfNewEntries) {
            // only add the value if it doesn't exist yet
            $existingHourlyMeasurements = $this->location->hourlyMeasurements()->where('date', $measurementFeature->properties->endMeasure->seconds(0));

            if ($existingHourlyMeasurements->count() === 0) {
                $this->location->hourlyMeasurements()->save(HourlyMeasurement::fromMeasurementFeature($measurementFeature));

                $numberOfNewEntries = $numberOfNewEntries + 1;
            }
        });

        Log::channel('odl')->info("{$numberOfNewEntries} new hourly values for location \"{$this->location->postal_code} {$this->location->name}\"");
    }
}
