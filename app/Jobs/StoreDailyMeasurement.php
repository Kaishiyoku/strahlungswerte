<?php

namespace App\Jobs;

use App\Models\DailyMeasurement;
use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreDailyMeasurement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var Collection<DailyMeasurement>
     */
    protected $dailyMeasurements;

    /**
     * @var string
     */
    protected $signature = 'odl:fetch_daily_measurements';

    /**
     * Create a new job instance.
     *
     * @param Location $location
     * @param Collection<DailyMeasurement> $dailyMeasurements
     */
    public function __construct(Location $location, Collection $dailyMeasurements)
    {
        $this->location = $location;
        $this->dailyMeasurements = $dailyMeasurements;
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
            foreach ($this->dailyMeasurements as $measurement) {
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
