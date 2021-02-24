<?php

namespace App\Jobs;

use App\Libraries\Odl\Models\MeasurementSite;
use App\Models\HourlyMeasurement;
use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class StoreHourlyMeasurement implements ShouldQueue
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
    protected $signature = 'odl:fetch_hourly_measurements';

    /**
     * Create a new job instance.
     *
     * @param Location $location
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
        try {
            $numberOfNewEntries = 0;

            $measurementSite = MeasurementSite::fromJson(json_decode(Storage::disk('odl_archives')->get($this->filePath), true));

            foreach ($measurementSite->getHourlyMeasurements() as $measurement) {
                // only add the value if it doesn't exist yet
                $existingHourlyMeasurements = $this->location->hourlyMeasurements()->where('date', $measurement->getDate());

                if ($existingHourlyMeasurements->count() == 0) {
                    $hourlyMeasurement = new HourlyMeasurement();
                    $hourlyMeasurement->value = $measurement->getValue() == 0 ? null : $measurement->getValue();
                    $hourlyMeasurement->date = $measurement->getDate();
                    $hourlyMeasurement->inspection_status = $measurement->getInspectionStatus();
                    $hourlyMeasurement->precipitation_probability = $measurement->getPrecipitationProbabilityValue();

                    $this->location->hourlyMeasurements()->save($hourlyMeasurement);

                    $numberOfNewEntries = $numberOfNewEntries + 1;
                }
            }

            Log::channel('odl')->info("Fetched and stored {$numberOfNewEntries} values for the location \"{$this->location->postal_code} {$this->location->name}\"");
        } catch (Throwable $e) {
            Log::channel('odl')->error("{$e->getMessage()}\n{$e->getTraceAsString()}");
        }
    }
}
