<?php

namespace App\Console\Commands;

use App\Console\HasCustomCommandExtensions;
use App\Libraries\Odl\OdlFetcher;
use App\Models\Location;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class OdlFetchLocations extends Command
{
    use HasCustomCommandExtensions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odl:fetch_locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the Odl location data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->start();

        $odlFetcher = new OdlFetcher(env('ODL_BASE_URL'), env('ODL_USER'), env('ODL_PASSWORD'));

        try {
            $locations = $odlFetcher->fetchLocations();

            $numberOfNewEntries = 0;
            $numberOfUpdatedEntries = 0;

            foreach ($locations as $location) {
                $existingLocation = Location::find($location->uuid);

                if ($existingLocation == null) {
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

            $this->updateLog->is_successful = true;
            $this->updateLog->number_of_new_entries = $numberOfNewEntries;

            $this->info('Fetched and stored ' . $numberOfNewEntries . ' and updated ' . $numberOfUpdatedEntries . ' locations');
        } catch (GuzzleException $e) {
            $this->addExceptionToUpdateLog($e);
        }

        $this->end();
    }
}
