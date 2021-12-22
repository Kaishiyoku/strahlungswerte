<?php

namespace App\Libraries\Odl;

use App\Jobs\StoreDailyMeasurementsForLocation;
use App\Jobs\StoreHourlyMeasurement;
use App\Libraries\Odl\Features\FeatureCollection;
use App\Libraries\Odl\Features\LocationFeature;
use App\Libraries\Odl\Features\MeasurementFeature;
use App\Models\Location;
use App\Models\Statistic;
use Arr;
use Carbon\CarbonPeriod;
use Http;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\ArrayToXml\ArrayToXml;
use Throwable;

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

    /**
     * @param Statistic $statistic
     */
    private function updateStatistic(Statistic $statistic)
    {
        // TODO
        try {
            $existingStatistic = Statistic::where('date', $statistic->date);

            if ($existingStatistic->count() === 0) {
                $statistic->save();

                Log::channel('odl')->info("Stored statistic for {$statistic->date->toDateString()}");
            }
        } catch (Throwable $e) {
            Log::channel('odl')->error("{$e->getMessage()}\n{$e->getTraceAsString()}");
        }
    }

    /**
     * @param Collection $measurementSiteFilePaths
     * @param bool $withCosmicAndTerrestrialRate
     */
    private function updateMeasurements(Collection $measurementSiteFilePaths, bool $withCosmicAndTerrestrialRate = false)
    {
        // TODO
        $fileNameSuffix = $withCosmicAndTerrestrialRate ? 'ct' : '';

        Location::orderBy('name')->get()->each(function ($location) use ($measurementSiteFilePaths, $fileNameSuffix) {
            $measurementSiteFilePath = $measurementSiteFilePaths
                ->filter(function ($path) use ($location, $fileNameSuffix) {
                    return Str::endsWith($path, "{$location->uuid}{$fileNameSuffix}.json");
                })
                ->first();

            if ($measurementSiteFilePath) {
                StoreDailyMeasurementsForLocation::dispatch($location, $measurementSiteFilePath);
                StoreHourlyMeasurement::dispatch($location, $measurementSiteFilePath);
            } else {
                Log::channel('odl')->warning("No JSON file found for location with UUID {$location->uuid}");
            }
        });
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
