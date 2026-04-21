<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DownloadImageJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels,Batchable;

    public $url;
    public $folder;
    public $filename;

    public function __construct($url, $folder, $filename)
    {
        $this->url = $url;
        $this->folder = $folder;
        $this->filename = $filename;
    }

    public function handle(): void
    {
        try {
            $destinationPath = public_path("uploads/{$this->folder}");

            File::ensureDirectoryExists($destinationPath);

            $filePath = "{$destinationPath}/{$this->filename}";

            if (file_exists($filePath)) return;

            $response = Http::timeout(20)
                ->retry(3, 500)
                ->get($this->url);

            if ($response->successful()) {
                file_put_contents($filePath, $response->body());
            } else {
                Log::warning('Image failed', ['url' => $this->url]);
            }

        } catch (\Throwable $e) {
            Log::error('Image download failed', [
                'url' => $this->url,
                'error' => $e->getMessage()
            ]);
        }
    }
}