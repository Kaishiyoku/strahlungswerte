<?php

namespace App\Console\Commands;

use Artisan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

class Upgrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upgrade {--F|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrades the application';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The database instance.
     *
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $db;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \Illuminate\Database\DatabaseManager $db
     * @return void
     */
    public function __construct(Filesystem $files, DatabaseManager $db)
    {
        parent::__construct();

        $this->files = $files;
        $this->db = $db;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        if (!$this->confirm('Are you sure? Please disable all scheduled commands and run all migrations before you continue.')) {
            return Command::SUCCESS;
        }

        $upgradeFileNames = $this->getUpgradeFileNames(array_diff(scandir($this->getUpgradePath()), ['.', '..']));

        if ($upgradeFileNames->isEmpty()) {
            $this->info('No new upgrades available.');
        }

        $upgradeFileNames->each(function ($file, $key) {
            $filePath = $this->getUpgradePath() . DIRECTORY_SEPARATOR . $file;

            $this->files->requireOnce($filePath);

            $upgrade = $this->resolveClass($filePath);
            $upgrade->run();

            $this->db->table('upgrades')->insert([
                'upgrade' => $key,
                'created_at' => now(),
            ]);

            $this->info("Upgraded {$key}");
        });

        return Command::SUCCESS;
    }

    /**
     * @param array<string> $paths
     * @return Collection<string>
     */
    private function getUpgradeFileNames($paths)
    {
        $alreadyUpgradedFiles = $this->db->table('upgrades')->select('upgrade')->pluck('upgrade');

        return collect($paths)
            ->flatMap(function ($path) {
                return Str::endsWith($path, '.php') ? [$path] : $this->files->glob($path . '/*_*.php');
            })
            ->filter()->values()->keyBy(function ($file) {
                return $this->getUpgradeName($file);
            })
            ->sortBy(function ($file, $key) {
                return $key;
            })
            ->filter(function ($file, $key) use ($alreadyUpgradedFiles) {
                return !$alreadyUpgradedFiles->contains($key) || $this->option('force');
            });
    }

    /**
     * @param string $path
     * @return string
     */
    private function getUpgradeName($path)
    {
        return Str::replace('.php', '', basename($path));
    }

    /**
     * @return string
     */
    private function getUpgradePath()
    {
        return base_path() . DIRECTORY_SEPARATOR . 'upgrades';
    }

    /**
     * @param string $path
     * @return object
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function resolveClass($path)
    {
        $class = $this->getUpgradeClassName($this->getUpgradeName($path));

        if (class_exists($class) && realpath($path) == (new ReflectionClass($class))->getFileName()) {
            return new $class;
        }

        $migration = $this->files->getRequire($path);

        return is_object($migration) ? $migration : new $class;
    }

    /**
     * @param string $upgradeName
     * @return string
     */
    private function getUpgradeClassName($upgradeName)
    {
        return Str::studly(implode('_', array_slice(explode('_', $upgradeName), 1)));
    }
}
