<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OdlUpdateLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odl:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the ODL archive data and processes them';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $odlFetcher = getOdlFetcher();

        $odlFetcher->updateLocations();
    }
}
