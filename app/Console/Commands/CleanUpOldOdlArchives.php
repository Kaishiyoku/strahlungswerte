<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CleanUpOldOdlArchives extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odl:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old ODL archives';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $odlArchivesStorage = Storage::disk('odl_archives');

        // delete old tar archives
        $fileNames = collect($odlArchivesStorage->files())
            ->filter(function ($fileName) {
                return Str::endsWith($fileName, '.tar');
            })
            ->each(function ($fileName) use ($odlArchivesStorage) {
                $odlArchivesStorage->delete($fileName);
            });

        $this->info($fileNames->count() . ' ' . Str::plural('archive', $fileNames->count()) . ' deleted.');

        // delete all folders except the latest
        $archiveDirectories = collect($odlArchivesStorage->directories())
            ->map(function ($directoryName) use ($odlArchivesStorage) {
                return [
                    'name' => $directoryName,
                    'lastModifiedAt' => $odlArchivesStorage->lastModified($directoryName),
                ];
            })
            ->sortBy('lastModifiedAt', SORT_REGULAR, true)
            ->slice(1)
            ->each(function ($directoryInfo) use ($odlArchivesStorage) {
                $odlArchivesStorage->deleteDirectory($directoryInfo['name']);
            });

        $this->info($archiveDirectories->count() . ' ' . Str::plural('directory', $archiveDirectories->count()) . ' will be deleted.');

        return 0;
    }
}
