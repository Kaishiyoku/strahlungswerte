<?php

namespace App\Console;

use App\Models\UpdateLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

trait HasCustomCommandExtensions
{
    /**
     * @var Carbon
     */
    protected $commandStartedAt;

    /**
     * @var Carbon
     */
    protected $commandEndedAt;

    /**
     * @var UpdateLog
     */
    protected $updateLog;

    public function start()
    {
        $this->commandStartedAt = Carbon::now();

        $this->updateLog = new UpdateLog();
        $this->updateLog->command_name = $this->signature;
    }

    public function end()
    {
        $this->commandEndedAt = Carbon::now();

        $this->updateLog->duration_in_seconds = $this->commandStartedAt->diffInSeconds($this->commandEndedAt);

        $this->updateLog->save();
    }

    /**
     * @param \Exception $e
     */
    protected function addExceptionToUpdateLog(\Exception $e)
    {
        $this->updateLog->is_successful = false;
        $this->updateLog->json_content = json_encode([
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'trace' => app()->isLocal() ? $e->getTraceAsString() : null,
        ]);

        Log::channel('queue')->error($e->getMessage() . "\n" . $e->getTraceAsString());
    }
}
