<?php

namespace App\Console\Commands;

use App\Models\DailyMeasurement;
use App\Models\Location;
use App\Models\Statistic;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OdlGenerateStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odl:statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily ODL statistics';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // get latest statistic
        $latestStatistic = Statistic::orderByDesc('date')->first();
        $oldestDailyMeasurement = DailyMeasurement::orderBy('date')->first();

        if (!$oldestDailyMeasurement) {
            $this->logInfo('No daily measurements found, so no statistic generation needed.');

            return Command::SUCCESS;
        }

        $startDate = ($latestStatistic ?? $oldestDailyMeasurement)->date->addDay()->startOfDay();

        $datePeriod = CarbonPeriod::create($startDate, now()->subDay()->startOfDay());

        $numberOfNewStatistics = 0;

        $datePeriod->forEach(function (Carbon $date) use (&$numberOfNewStatistics) {
            $baseQuery = DailyMeasurement::whereDate('date', '>=', $date)->whereDate('date', '<=', $date);

            $minDailyMeasurement = $baseQuery->clone()->orderBy('value')->first();
            $maxDailyMeasurement = $baseQuery->clone()->orderByDesc('value')->first();
            $averageValue = $baseQuery->clone()->average('value');

            // no data for this date
            if (!$minDailyMeasurement || !$maxDailyMeasurement) {
                $this->logInfo("Couldn't generate statistic because of insufficient data for date {$date}");

                return;
            }

            // check how many locations have data for the given date
            $numberOfOperationalLocations = Location::query()
                ->whereHas('dailyMeasurements', function ($query) use ($date) {
                    $query->whereDate('date', '>=', $date)->whereDate('date', '<=', $date);
                })
                ->count();

            Statistic::create([
                'date' => $date,
                'number_of_operational_locations' => $numberOfOperationalLocations,
                'average_value' => $averageValue,
                'min_location_uuid' => $minDailyMeasurement->location_uuid,
                'min_location_uuid_new' => $minDailyMeasurement->location_uuid_new,
                'min_value' => $minDailyMeasurement->value,
                'max_location_uuid' => $maxDailyMeasurement->location_uuid,
                'max_location_uuid_new' => $maxDailyMeasurement->location_uuid_new,
                'max_value' => $maxDailyMeasurement->value,
            ]);

            $numberOfNewStatistics = $numberOfNewStatistics + 1;

            $this->logInfo("Statistic generated for date {$date}");
        });

        $this->logInfo("{$numberOfNewStatistics} statistic(s) generated");

        return Command::SUCCESS;
    }

    private function logInfo($message)
    {
        $this->info($message);
        Log::channel('odl')->info($message);
    }
}
