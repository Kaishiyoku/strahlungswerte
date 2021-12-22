<?php

use App\Libraries\Odl\Features\LocationFeature;
use App\Models\DailyMeasurement;
use App\Models\HourlyMeasurement;
use App\Models\Location;
use App\Models\Statistic;
use App\Upgrades\Upgrade;
use Symfony\Component\Console\Output\ConsoleOutput;

class UpgradeLocationUuids extends Upgrade
{
    public function run(): int
    {
        $consoleOutput = new ConsoleOutput();

        $locationFeatureCollection = getOdlFetcher()->fetchLocationFeatureCollection();
        $numberOfLocationFeatures = $locationFeatureCollection->features->count();

        $newLocationUuids = $locationFeatureCollection->features->map((function (LocationFeature $locationFeature) {
            return $locationFeature->properties->kenn;
        }));

        $locationUuidsWithoutNewData = Location::query()
            ->select(['uuid', 'status'])
            ->get()
            ->filter(function (Location $location) use ($newLocationUuids) {
                return !$newLocationUuids->contains($location->uuid) && $location->status !== 1;
            })
            ->map(function (Location $location) {
                return $location->uuid;
            });

        $locationFeatureCollection->features->each(function (LocationFeature $locationFeature, $i) use ($consoleOutput, $numberOfLocationFeatures) {
            $oldUuid = $locationFeature->properties->kenn;
            $newUuid = $locationFeature->properties->id;

            $location = Location::find($oldUuid);

            if (!$location) {
                $consoleOutput->writeln('No location found for UUID ' . $newUuid);

                return;
            }

            $location
                ->dailyMeasurements()
                ->whereNull('location_uuid_new')
                ->get()
                ->each(function (DailyMeasurement $dailyMeasurement) use ($newUuid) {
                    $dailyMeasurement->location_uuid_new = $newUuid;
                    $dailyMeasurement->save();
                });

            $location
                ->hourlyMeasurements()
                ->whereNull('location_uuid_new')
                ->get()
                ->each(function (HourlyMeasurement $hourlyMeasurement) use ($newUuid) {
                    $hourlyMeasurement->location_uuid_new = $newUuid;
                    $hourlyMeasurement->save();
                });

            $minStatistics = Statistic::whereMinLocationUuid($oldUuid)->whereNull('min_location_uuid_new')->get();
            $maxStatistics = Statistic::whereMaxLocationUuid($oldUuid)->whereNull('max_location_uuid_new')->get();

            $minStatistics->each(function (Statistic $minStatistic) use ($newUuid) {
                $minStatistic->min_location_uuid_new = $newUuid;
                $minStatistic->save();
            });

            $maxStatistics->each(function (Statistic $maxStatistic) use ($newUuid) {
                $maxStatistic->max_location_uuid_new = $newUuid;
                $maxStatistic->save();
            });

            $location->uuid_new = $newUuid;
            $location->save();

            $num = $i + 1;

            $consoleOutput->writeln("{$num} of {$numberOfLocationFeatures} | added new UUID for location \"{$location->name}\"");
        });

        // delete locations without new data because we won't get a new UUID for them
        $locationsWithoutNewData = Location::whereIn('uuid', $locationUuidsWithoutNewData)->get();
        $locationsWithoutNewData->each(function (Location $location) {
            // write data to json file as a backup
            $basePath = "0_upgrade_location_uuids_backups/{$location->uuid}";

            Storage::disk('local')->put($basePath . '/location.json', $location->toJson());
            Storage::disk('local')->put($basePath . '/daily_measurements.json', $location->dailyMeasurements->toJson());
            Storage::disk('local')->put($basePath . '/hourly_measurements.json', $location->hourlyMeasurements->toJson());

            $statisticsQuery = Statistic::query()
                ->where('min_location_uuid', $location->uuid)
                ->orWhere('max_location_uuid', $location->uuid);

            Storage::disk('local')->put($basePath . '/statistics.json', $statisticsQuery->get()->toJson());

            $location->dailyMeasurements()->delete();
            $location->hourlyMeasurements()->delete();
            $statisticsQuery->delete();
            $location->delete();
        });

        // run checks
        $numberOfLocationsWithoutNewUuid = Location::whereNull('uuid_new')->count();
        $numberOfDailyMeasurementsWithoutNewUuid = DailyMeasurement::whereNull('location_uuid_new')->count();
        $numberOfHourlyMeasurementsWithoutNewUuid = HourlyMeasurement::whereNull('location_uuid_new')->count();
        $numberOfStatisticsWithoutNewUuid = Statistic::whereNull('min_location_uuid_new')->orWhereNull('max_location_uuid_new')->count();

        $consoleOutput->writeln('');
        $consoleOutput->writeln('Running checks...everything should be 0');
        $consoleOutput->writeln("Number of locations without new UUID: {$numberOfLocationsWithoutNewUuid}");
        $consoleOutput->writeln("Number of daily measurements without new UUID: {$numberOfDailyMeasurementsWithoutNewUuid}");
        $consoleOutput->writeln("Number of hourly measurements without new UUID: {$numberOfHourlyMeasurementsWithoutNewUuid}");
        $consoleOutput->writeln("Number of statistics without new UUID: {$numberOfStatisticsWithoutNewUuid}");
        $consoleOutput->writeln("Number of deleted locations because they don't have any new data: {$locationUuidsWithoutNewData->count()}");

        return 0;
    }
}
