<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\EventDateParser;

class ProcessEventDateJson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function handle()
    {
        try {
            if (!$this->text) {
                return;
            }

            $parsed = EventDateParser::parse($this->text);

            DB::table('event_datetime_logs')->insert([
                'original_text' => $this->text,
                'start_datetime' => $parsed['start'] ?? null,
                'end_datetime' => $parsed['end'] ?? null,
                'timezone' => $parsed['timezone'] ?? 'UTC',
                'created_at' => now(),
            ]);

        } catch (\Exception $e) {
            Log::error('Parsing failed', [
                'text' => $this->text,
                'error' => $e->getMessage()
            ]);
        }
    }
}