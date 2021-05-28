<?php

namespace App\Jobs;

use App\Libraries\Odl\Models\MeasurementSite;
use App\Models\DailyMeasurement;
use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class StoreDailyMeasurement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var string
     */
    protected $signature = 'odl:fetch_daily_measurements';

    /**
     * Create a new job instance.
     *
     * @param Location $location
     * @param string $filePath
     */
    public function __construct(Location $location, string $filePath)
    {
        $this->location = $location;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $numberOfNewEntries = 0;

        try {
            $measurementSite = MeasurementSite::fromJson(json_decode(Storage::disk('odl_archives')->get($this->filePath), true));

            foreach ($measurementSite->getDailyMeasurements() as $measurement) {
                // only add the value if it doesn't exist yet
                $existingDailyMeasurements = $this->location->dailyMeasurements()->whereDate('date', $measurement->getDate());

                if ($existingDailyMeasurements->count() === 0) {
                    $dailyMeasurement = new DailyMeasurement();
                    $dailyMeasurement->value = $measurement->getValue() == 0 ? null : $measurement->getValue();
                    $dailyMeasurement->date = $measurement->getDate();

                    $this->location->dailyMeasurements()->save($dailyMeasurement);

                    $numberOfNewEntries = $numberOfNewEntries + 1;
                }
            }

            Log::channel('odl')->info("{$numberOfNewEntries} new daily values for the location \"{$this->location->postal_code} {$this->location->name}\"");
        } catch (Throwable $e) {
            Log::channel('odl')->error("{$e->getMessage()}\n{$e->getTraceAsString()}");
        }
    }
}
