<?php

namespace App\Libraries\Odl;

use App\Jobs\StoreDailyMeasurementsForLocation;
use App\Jobs\StoreHourlyMeasurementsForLocation;
use App\Libraries\Odl\Features\FeatureCollection;
use App\Libraries\Odl\Features\LocationFeature;
use App\Libraries\Odl\Features\MeasurementFeature;
use App\Models\Location;
use Arr;
use Carbon\CarbonPeriod;
use Http;
use Illuminate\Support\Facades\Log;
use Spatie\ArrayToXml\ArrayToXml;

class OdlFetcher
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function fetchLocationFeatureCollection(): FeatureCollection
    {
        return FeatureCollection::fromJson(LocationFeature::class, $this->fetchData('opendata:odlinfo_odl_1h_latest'));
    }

    public function fetchDailyMeasurementFeatureCollection(string $measurementSiteUuid, ArrayToXml $filter): FeatureCollection
    {
        $additionalParams = [
            'viewparams' => "kenn:{$measurementSiteUuid}",
            'sortBy' => 'end_measure',
            'filter' => $filter->toXml(),
        ];

        return FeatureCollection::fromJson(MeasurementFeature::class, $this->fetchData('opendata:odlinfo_timeseries_odl_24h', $additionalParams));
    }

    public function fetchHourlyMeasurementFeatureCollection(string $measurementSiteUuid, ArrayToXml $filter): FeatureCollection
    {
        $additionalParams = [
            'viewparams' => "kenn:{$measurementSiteUuid}",
            'sortBy' => 'end_measure',
            'filter' => $filter->toXml(),
        ];

        return FeatureCollection::fromJson(MeasurementFeature::class, $this->fetchData('opendata:odlinfo_timeseries_odl_1h', $additionalParams));
    }

    public function updateLocations()
    {
        $locationFeatureCollection = $this->fetchLocationFeatureCollection();

        $numberOfNewEntries = 0;
        $numberOfUpdatedEntries = 0;

        $locationFeatureCollection->features->each(function (LocationFeature $locationFeature) use (&$numberOfNewEntries, &$numberOfUpdatedEntries) {
            $existingLocation = Location::find($locationFeature->properties->kenn);

            if (!$existingLocation) {
                Location::fromLocationFeature($locationFeature)->save();

                $numberOfNewEntries = $numberOfNewEntries + 1;
            } else {
                $existingLocation->status = $locationFeature->properties->siteStatus;
                $existingLocation->height = $locationFeature->properties->heightAboveSea;
                $existingLocation->longitude = $locationFeature->geometry->coordinates->longitude;
                $existingLocation->latitude = $locationFeature->geometry->coordinates->latitude;
                $existingLocation->last_measured_one_hour_value = $locationFeature->properties->value;

                $existingLocation->save();

                $numberOfUpdatedEntries = $numberOfUpdatedEntries + 1;
            }
        });

        Log::channel('odl')->info("{$numberOfNewEntries} new  and {$numberOfUpdatedEntries} updated locations");
    }

    public function updateDailyMeasurements(CarbonPeriod $datePeriod)
    {
        Location::query()
            ->orderBy('name')
            ->get()
            ->each(function ($location) use ($datePeriod) {
                StoreDailyMeasurementsForLocation::dispatch($location, $datePeriod);
            });
    }

    public function updateHourlyMeasurements(CarbonPeriod $datePeriod)
    {
        Location::query()
            ->orderBy('name')
            ->get()
            ->each(function ($location) use ($datePeriod) {
                StoreHourlyMeasurementsForLocation::dispatch($location, $datePeriod);
            });
    }

    /**
     * @throws \DOMException
     */
    public function getFilterXml(CarbonPeriod $datePeriod): ArrayToXml
    {
        $filter = [
            'PropertyIsBetween' => [
                'PropertyName' => 'end_measure',
                'LowerBoundary' => [
                    'Literal' => $datePeriod->getStartDate()->toIso8601ZuluString('millisecond'),
                ],
                'UpperBoundary' => [
                    'Literal' => $datePeriod->getEndDate()->toIso8601ZuluString('millisecond'),
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

    private function fetchData(string $typeName, array $additionalParams = []): array
    {
        $queryStr = Arr::query(array_merge([
            'service' => 'WFS',
            'version' => '1.1.0',
            'request' => 'GetFeature',
            'outputFormat' => 'application/json',
            'typeName' => $typeName,
        ], $additionalParams));

        return Http::get("{$this->baseUrl}?{$queryStr}")->json();
    }
}
