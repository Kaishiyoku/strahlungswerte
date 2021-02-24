<?php

namespace App\Libraries\Odl;

use App\Jobs\StoreDailyMeasurement;
use App\Jobs\StoreHourlyMeasurement;
use App\Libraries\Odl\Models\ArchiveDataContainer;
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

        $this->updateLocations($locations);
        $this->updateStatistic($statistic);
        $this->updateMeasurements($archiveDataContainer->getMeasurementSiteFilePaths());
    }

    /**
     * @return ArchiveDataContainer
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function downloadArchiveData()
    {
        $odlArchivesStorage = Storage::disk('odl_archives');

        $response = $this->getJsonCtArchive();
        $lastModified = Carbon::parse($response->getHeaderLine('Last-Modified'));
        $directoryName = "json-ct_{$lastModified->format('Y-m-d_H_i_s')}";
        $fileName = "{$directoryName}.tgz";

        if (!$odlArchivesStorage->exists($fileName)) {
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

        return new ArchiveDataContainer($directoryName, $locationFilePath, $statisticsFilePath, $measurementSiteFilePaths);
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

            Log::channel('odl')->info("Fetched and stored {$numberOfNewEntries} and updated {$numberOfUpdatedEntries} locations");
        } catch (Throwable $e) {
            Log::channel('odl')->error("{$e->getMessage()}\n{$e->getTraceAsString()}");
        }
    }

    /**
     * @param Collection $measurementSiteFilePaths
     */
    private function updateMeasurements(Collection $measurementSiteFilePaths)
    {
        Location::orderBy('name')->get()->each(function ($location) use ($measurementSiteFilePaths) {
            $measurementSiteFilePath = $measurementSiteFilePaths
                ->filter(function ($path) use ($location) {
                    return Str::endsWith($path, $location->uuid . 'ct.json');
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

    /**
     * @param Statistic $statistic
     */
    private function updateStatistic(Statistic $statistic)
    {
        try {
            $existingStatistic = Statistic::where('date', $statistic->date);

            if ($existingStatistic->count() === 0) {
                $statistic->save();

                Log::channel('odl')->info("Fetched and stored statistic for {$statistic->date->toDateString()}");
            }
        } catch (Throwable $e) {
            Log::channel('odl')->error("{$e->getMessage()}\n{$e->getTraceAsString()}");
        }
    }

    /**
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getJsonCtArchive(): ResponseInterface
    {
        return $this->httpClient->get($this->baseUrl . '/json-ct.tgz');
    }
}
