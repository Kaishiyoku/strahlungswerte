<?php

namespace App\Console\Commands;

use App\Console\HasCustomCommandExtensions;
use App\Libraries\Odl\OdlFetcher;
use App\Models\Statistic;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class OdlFetchStatistic extends Command
{
    use HasCustomCommandExtensions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odl:fetch_statistic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the ODL statistic of the day';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->start();

        $odlFetcher = new OdlFetcher(env('ODL_BASE_URL'), env('ODL_USER'), env('ODL_PASSWORD'));

        try {
            $statistic = $odlFetcher->fetchStatistic();

            $existingStatistic = Statistic::where('date', $statistic->date);

            if ($existingStatistic->count() === 0) {
                $statistic->save();

                Log::channel('queue')->info('Fetched and stored statistic for ' . $statistic->date->toDateString());
            }
        } catch (Throwable $e) {
            $this->addExceptionToUpdateLog($e);
        }
    }
}
