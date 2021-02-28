<?php

namespace App\Libraries\Odl;

use App\Jobs\StoreDailyMeasurement;
use App\Jobs\StoreHourlyMeasurement;
use App\Libraries\Odl\Models\ArchiveDataContainer;
use App\Libraries\Odl\Models\MeasurementSite;
use App\Models\Location;
use App\Models\Statistic;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PharData;
use Psr\Http\Message\ResponseInterface;
use Storage;
use Throwable;

class OdlFetcher
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @param string $baseUrl
     * @param string $username
     * @param string $password
     */
    public function __construct(string $baseUrl, string $username, string $password)
    {
        $this->baseUrl = $baseUrl;

        $this->httpClient = new Client(['defaults' => ['verify' => false], 'auth' => [$username, $password]]);
    }

    /**
     * @param ArchiveDataContainer $archiveDataContainer
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function processArchiveData(ArchiveDataContainer $archiveDataContainer)
    {
        $odlArchivesStorage = Storage::disk('odl_archives');

        $locations = collect(json_decode($odlArchivesStorage->get($archiveDataContainer->getLocationFilePath()), true))->map(function ($data) {
            return Location::createFromJson($data);
        });

        $statistic = Statistic::createFromJson(json_decode($odlArchivesStorage->get($archiveDataContainer->getStatisticsFilePath()), true));

        $measurementSites = $archiveDataContainer->getMeasurementSiteFilePaths()->map((function ($measurementSiteFilePath) use ($odlArchivesStorage) {
            return MeasurementSite::fromJson(json_decode($odlArchivesStorage->get($measurementSiteFilePath), true));
        }));

        $this->updateLocations($locations);
        $this->updateStatistic($statistic);
        $this->updateMeasurements($measurementSites);
    }

    /**
     * @param bool $withCosmicAndTerrestrialRate
     * @return ArchiveDataContainer
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function downloadArchiveData($withCosmicAndTerrestrialRate = false)
    {
        $odlArchivesStorage = Storage::disk('odl_archives');

        $response = $this->getJsonArchive();

        $lastModified = Carbon::parse($response->getHeaderLine('Last-Modified'));
        $directoryName = $this->getArchiveDirectoryName($lastModified, $withCosmicAndTerrestrialRate);
        $fileName = "{$directoryName}.tgz";

        if ($odlArchivesStorage->exists($fileName)) {
            Log::channel('odl')->info("Archive {$fileName} already exists.");
        } else {
            Log::channel('odl')->info("Archive {$fileName} doesn't exist yet and will be stored.");

            $odlArchivesStorage->put($fileName, $response->getBody()->getContents());
        }

        if (!$odlArchivesStorage->exists("{$directoryName}.tar")) {
            $pharData = new PharData($odlArchivesStorage->path($fileName));
            $pharData->decompress();
            $pharData->extractTo($odlArchivesStorage->path($directoryName));
        }

        $filePaths = collect($odlArchivesStorage->allFiles($directoryName));
        $measurementSiteFilePaths = $filePaths
            ->filter(function ($filePath) {
                return !Str::endsWith($filePath, ['/stamm.json', '/stat.json']);
            });
        $locationFilePath = $filePaths
            ->filter(function ($filePath) {
                return Str::endsWith($filePath, '/stamm.json');
            })
            ->first();
        $statisticsFilePath = $filePaths
            ->filter(function ($filePath) {
                return Str::endsWith($filePath, '/stat.json');
            })
            ->first();

        return new ArchiveDataContainer($directoryName, $locationFilePath, $statisticsFilePath, $measurementSiteFilePaths, $withCosmicAndTerrestrialRate);
    }

    /**
     * @param Collection $locations
     */
    private function updateLocations(Collection $locations)
    {
        try {
            $numberOfNewEntries = 0;
            $numberOfUpdatedEntries = 0;

            foreach ($locations as $location) {
                $existingLocation = Location::find($location->uuid);

                if ($existingLocation === null) {
                    $location->save();

                    $numberOfNewEntries = $numberOfNewEntries + 1;
                } else {
                    $existingLocation->height = $location->height;
                    $existingLocation->longitude = $location->longitude;
                    $existingLocation->latitude = $location->latitude;
                    $existingLocation->last_measured_one_hour_value = $location->last_measured_one_hour_value;

                    $existingLocation->save();

                    $numberOfUpdatedEntries = $numberOfUpdatedEntries + 1;
                }
            }

            Log::channel('odl')->info("{$numberOfNewEntries} new  and {$numberOfUpdatedEntries} updated locations");
        } catch (Throwable $e) {
            Log::channel('odl')->error("{$e->getMessage()}\n{$e->getTraceAsString()}");
        }
    }

    /**
     * @param Statistic $statistic
     */
    private function updateStatistic(Statistic $statistic)
    {
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
     * @param Collection<MeasurementSite> $measurementSites
     */
    private function updateMeasurements(Collection $measurementSites)
    {
        Location::select(['uuid', 'name'])->orderBy('name')->get()->each(function ($location) use ($measurementSites) {
            /*** @var MeasurementSite $measurementSite */
            $measurementSite = $measurementSites
                ->filter(function (MeasurementSite $measurementSite) use ($location) {
                    return $measurementSite->getLocation()->uuid === $location->uuid;
                })
                ->first();

            if ($measurementSite) {
                StoreDailyMeasurement::dispatch($location, $measurementSite->getDailyMeasurements());
                StoreHourlyMeasurement::dispatch($location, $measurementSite->getHourlyMeasurements());
            } else {
                Log::channel('odl')->warning("No JSON file found for location with UUID {$location->uuid}");
            }
        });
    }

    /**
     * @param bool $withCosmicAndTerrestrialRate
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getJsonArchive($withCosmicAndTerrestrialRate = false): ResponseInterface
    {
        $archiveFileName = $withCosmicAndTerrestrialRate ? 'json-ct.tgz' : 'json.tgz';

        return $this->httpClient->get("{$this->baseUrl}/{$archiveFileName}");
    }

    /**
     * @param Carbon $lastModified
     * @param bool $withCosmicAndTerrestrialRate
     * @return string
     */
    private function getArchiveDirectoryName($lastModified, $withCosmicAndTerrestrialRate = false)
    {
        $baseFileName = $withCosmicAndTerrestrialRate ? 'json' : 'json-ct';

        return "{$baseFileName}_{$lastModified->format('Y-m-d_H_i_s')}";
    }
}
