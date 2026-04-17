<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class MarkFileProcessedJob implements ShouldQueue
{
    use Dispatchable,Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $file)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $newPath = str_replace('.json', '_done.json', $this->file);

        if (file_exists($this->file)) {
            rename($this->file, $newPath);
        }

        Log::info('File marked as processed', [
            'file' => $this->file
        ]);
    }
}
