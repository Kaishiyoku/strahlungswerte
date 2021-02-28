<?php

namespace App\Jobs;

use App\Models\HourlyMeasurement;
use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreHourlyMeasurement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var Collection<HourlyMeasurement>
     */
    protected $hourlyMeasurements;

    /**
     * @var string
     */
    protected $signature = 'odl:fetch_hourly_measurements';

    /**
     * Create a new job instance.
     *
     * @param Location $location
     * @param Collection<HourlyMeasurement> $hourlyMeasurements
     */
    public function __construct(Location $location, Collection $hourlyMeasurements)
    {
        $this->location = $location;
        $this->hourlyMeasurements = $hourlyMeasurements;
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

            foreach ($this->hourlyMeasurements as $measurement) {
                // only add the value if it doesn't exist yet
                $existingHourlyMeasurements = $this->location->hourlyMeasurements()->where('date', $measurement->getDate()->seconds(0));

                if ($existingHourlyMeasurements->count() === 0) {
                    $hourlyMeasurement = new HourlyMeasurement();
                    $hourlyMeasurement->value = $measurement->getValue() == 0 ? null : $measurement->getValue();
                    $hourlyMeasurement->date = $measurement->getDate();
                    $hourlyMeasurement->inspection_status = $measurement->getInspectionStatus();
                    $hourlyMeasurement->precipitation_probability = $measurement->getPrecipitationProbabilityValue();

                    $this->location->hourlyMeasurements()->save($hourlyMeasurement);

                    $numberOfNewEntries = $numberOfNewEntries + 1;
                }
            }

            Log::channel('odl')->info("{$numberOfNewEntries} new hourly values for the location \"{$this->location->postal_code} {$this->location->name}\"");
        } catch (Throwable $e) {
            Log::channel('odl')->error("{$e->getMessage()}\n{$e->getTraceAsString()}");
        }
    }
}
