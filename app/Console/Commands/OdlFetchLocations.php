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

            foreach ($locations as $location) {
                if (!Location::find($location->uuid)) {
                    $location->save();

                    $numberOfNewEntries = $numberOfNewEntries + 1;
                }
            }

            $this->updateLog->is_successful = true;
            $this->updateLog->number_of_new_entries = $numberOfNewEntries;

            $this->info('Fetched and stored ' . $numberOfNewEntries . ' locations');
        } catch (GuzzleException $e) {
            $this->addExceptionToUpdateLog($e);
        }

        $this->end();
    }
}
