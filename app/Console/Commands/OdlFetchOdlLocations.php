<?php

namespace App\Console\Commands;

use App\Libraries\Odl\OdlFetcher;
use App\Models\Location;
use Illuminate\Console\Command;

class OdlFetchOdlLocations extends Command
{
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
        $odlFetcher = new OdlFetcher(env('ODL_BASE_URL'), env('ODL_USER'), env('ODL_PASSWORD'));
        $locations = $odlFetcher->fetchLocations();

        $numberOfNewValues = 0;

        foreach ($locations as $location) {
            if (Location::find($location->uuid)->count() == 0) {
                $location->save();

                $numberOfNewValues = $numberOfNewValues + 1;
            }
        }

        $this->info('Fetched and stored ' . $numberOfNewValues . ' locations');
    }
}
