<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OdlUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odl:update';

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
     */
    public function handle()
    {
        $odlFetcher = getOdlFetcher();

        $archiveData = $odlFetcher->downloadArchiveData();

        $odlFetcher->processArchiveData($archiveData);
    }
}
