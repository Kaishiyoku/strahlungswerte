<?php

namespace App\Jobs;

use App\Libraries\Odl\Features\MeasurementFeature;
use App\Libraries\Odl\Models\MeasurementSite;
use App\Models\DailyMeasurement;
use App\Models\Location;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\ArrayToXml\ArrayToXml;

class StoreDailyMeasurementsForLocation implements ShouldQueue
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

        $dailyMeasurementFeatureCollection = getOdlFetcher()->fetchDailyMeasurementFeatureCollection($this->location->uuid, $this->getFilterXml());

        $dailyMeasurementFeatureCollection->features->each(function (MeasurementFeature $measurementFeature) use (&$numberOfNewEntries) {
            // only add the value if it doesn't exist yet
            $existingDailyMeasurements = $this->location->dailyMeasurements()->whereDate('date', $measurementFeature->properties->startMeasure);

            if ($existingDailyMeasurements->count() === 0) {
                $this->location->dailyMeasurements()->save(DailyMeasurement::fromMeasurementFeature($measurementFeature));

                $numberOfNewEntries = $numberOfNewEntries + 1;
            }
        });

        Log::channel('odl')->info("{$numberOfNewEntries} new daily value(s) for location \"{$this->location->postal_code} {$this->location->name}\"");
    }

    /**
     * @return ArrayToXml
     * @throws \DOMException
     */
    protected function getFilterXml()
    {
        $filter = [
            'PropertyIsBetween' => [
                'PropertyName' => 'end_measure',
                'LowerBoundary' => [
                    'Literal' => $this->datePeriod->getStartDate()->toIso8601ZuluString('millisecond'),
                ],
                'UpperBoundary' => [
                    'Literal' => $this->datePeriod->getEndDate()->toIso8601ZuluString('millisecond'),
                ],
            ],
        ];

        return (new ArrayToXml($filter, [
            'rootElementName' => 'Filter',
            '_attributes' => [
                'xmlns' => 'http://www.opengis.net/ogc',
                'xmlns:ogc' => 'http://www.opengis.net',
                'xmlns:gml' => 'http://www.opengis.net/gml',
            ],
        ]))->dropXmlDeclaration();
    }
}
