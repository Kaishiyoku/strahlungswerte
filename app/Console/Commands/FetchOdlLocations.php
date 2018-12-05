<?php

namespace App\Console\Commands;

use App\Libraries\Odl\OdlFetcher;
use Illuminate\Console\Command;

class FetchOdlLocations extends Command
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

        foreach ($locations as $location) {
            $location->save();
        }
    }
}
