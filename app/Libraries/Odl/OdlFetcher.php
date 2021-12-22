<?php

namespace App\Libraries\Odl;

use App\Enums\LocationStatus;
use App\Jobs\StoreDailyMeasurement;
use App\Jobs\StoreHourlyMeasurement;
use App\Libraries\Odl\Features\FeatureCollection;
use App\Libraries\Odl\Features\LocationFeature;
use App\Models\Location;
use App\Models\Statistic;
use Arr;
use Http;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

    public function processData()
    {
        // TODO: remove function

//        $this->updateStatistic($statistic);
//        $this->updateMeasurements($archiveDataContainer->getMeasurementSiteFilePaths(), $archiveDataContainer->isWithCosmicAndTerrestrialRate());
    }

    public function fetchLocationFeatureCollection(): FeatureCollection
    {
        return FeatureCollection::fromJson(LocationFeature::class, $this->fetchData('opendata:odlinfo_odl_1h_latest'));
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

        Log::channel('odl')->info("{$numberOfNewEntries} new  and {$numberOfUpdatedEntries} updated locations", ['method' => __METHOD__]);
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
                StoreDailyMeasurement::dispatch($location, $measurementSiteFilePath);
                StoreHourlyMeasurement::dispatch($location, $measurementSiteFilePath);
            } else {
                Log::channel('odl')->warning("No JSON file found for location with UUID {$location->uuid}");
            }
        });
    }

    private function fetchData(string $typeName): array
    {
        $queryStr = Arr::query([
            'service' => 'WFS',
            'version' => '1.1.0',
            'request' => 'GetFeature',
            'outputFormat' => 'application/json',
            'typeName' => $typeName,
        ]);

        return Http::get("{$this->baseUrl}?{$queryStr}")->json();
    }
}
