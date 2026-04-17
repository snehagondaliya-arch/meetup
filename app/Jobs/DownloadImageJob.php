<?php

namespace App\Jobs;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class DownloadImageJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public $url;
    public $folder;

    public $filename;

    public function __construct($url, $folder,$filename)
    {
        $this->url = $url;
        $this->folder = $folder;
        $this->filename = $filename;
    }

    public function handle(): void
    {
        try {
            Log::info('Downloading image', ['url' => $this->url]);

            $destinationPath = public_path("uploads/{$this->folder}");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $filePath = "{$destinationPath}/{$this->filename}";

            if (file_exists($filePath)) return;

            $response = Http::timeout(15)->retry(2, 300)->get($this->url);

            if ($response->successful()) {
                file_put_contents($filePath, $response->body());
            } else {
                throw new Exception("Failed status: " . $response->status());
            }

        } catch (Throwable $e) {
            Log::error('Image download failed', [
                'url' => $this->url,
                'error' => $e->getMessage()
            ]);

            throw $e; 
        }
    }
}