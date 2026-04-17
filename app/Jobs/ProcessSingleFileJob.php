<?php

namespace App\Jobs;

use App\Helpers\Helpers;    
use App\Jobs\DownloadImageJob;
use App\Jobs\MarkFileProcessedJob;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventPhotos;
use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessSingleFileJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function handle()
    {
        try {

            DB::disableQueryLog();

            Log::info('Processing file started', ['file' => $this->file]);

            $data = json_decode(file_get_contents($this->file), true);

            if (!is_array($data)) {
                Log::warning('Invalid JSON file', ['file' => $this->file]);
                return;
            }

            $categoryCache = [];
            $groupCache = [];
            $imageJobs = [];

            foreach ($data as $categorydata) {

                if (empty($categorydata['events'])) {
                    Log::warning('No events found', $categorydata);
                    continue;
                }

                $categoryName = $categorydata['category_name'] ?? 'Unknown';
                $categorySlug = Str::slug($categoryName);

                $category = $categoryCache[$categorySlug]??= Category::firstOrCreate(
                        ['slug' => $categorySlug],
                        ['name' => $categoryName]
                    );

                foreach ($categorydata['events'] as $eventData) {

                    $group = null;

                    if (!empty($eventData['group'])) {
                        $group = $groupCache[$eventData['group']]
                            ??= Group::firstOrCreate([
                                'name' => $eventData['group']
                            ]);
                    }

                    $imageFields = [
                        'image_url'   => 'events',
                        'group_image' => 'groups',
                        'host_image'  => 'hosts',
                    ];

                    foreach ($imageFields as $key => $folder) {

                        if (!empty($eventData[$key])) {

                            $url = $eventData[$key];

                            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                            $extension = in_array($extension, ['jpg', 'jpeg', 'png', 'webp']) ? $extension : 'jpg';

                            $filename = Str::uuid() . '.' . $extension;
    
                            $imageJobs[] = new DownloadImageJob($url, $folder, $filename);

                            $eventData[$key] = $filename;
                        }
                    }

                    $parsedDateTime = Helpers::parseDatetimeText($eventData['datetime_text'] ?? null);
                    $startDateTime = Helpers::getStartDateTime($eventData['datetime_text'] ?? null);
                    $endDateTime = Helpers::getEndDateTime($eventData['datetime_text'] ?? null);

                    $event = Event::updateOrCreate(
                        [
                            'event_url' => $eventData['event_url'] ?? Str::random(10),
                        ],
                        [
                            'category_id'   => $category->id,
                            'group_id'      => $group->id ?? null,
                            'title'         => $eventData['title'] ?? 'N/A',
                            'slug'          => Str::slug($eventData['title'] ?? 'event') . '-' . Str::random(5),
                            'date_list_view' => $eventData['date_list_view'] ?? null,
                            'datetime_text'  => $eventData['datetime_text'] ?? null,
                            'start_time'    => $startDateTime,
                            'end_time'      => $endDateTime,
                            'timezone'      => $parsedDateTime['timezone'] ?? null,
                            'venue_name'    => $eventData['location']['venue_name'] ?? null,
                            'full_address'  => $eventData['location']['full_address'] ?? null,
                            'latitude'      => $eventData['latitude'] ?? null,
                            'longitude'     => $eventData['longitude'] ?? null,
                            'image_url'     => $eventData['image_url'] ?? null,
                            'group_image'   => $eventData['group_image'] ?? null,
                            'host_image'    => $eventData['host_image'] ?? null,
                            'host_name'     => $eventData['host'] ?? null,
                            'description'   => $eventData['description'] ?? null,
                            'attendees'     => $eventData['attendees'] ?? 0,
                            'price'         => (int) ($eventData['price'] ?? 0),
                            'is_online'     => (int) ($eventData['is_online'] ?? 0),
                        ]
                    );

                    if (!empty($eventData['event_photos'])) {

                        foreach ($eventData['event_photos'] as $photo) {

                            if (!$photo) continue;

                            $extension = pathinfo(parse_url($photo, PHP_URL_PATH), PATHINFO_EXTENSION);
                            $extension = in_array($extension, ['jpg', 'jpeg', 'png', 'webp']) ? $extension : 'jpg';

                            $filename = Str::uuid() . '.' . $extension;
                             $imageJobs[] = new DownloadImageJob($url, 'event_photos', $filename);

                            EventPhotos::firstOrCreate([
                                'event_id' => $event->id,
                                'event_photos' => $filename,
                            ]);
                        }
                    }
                }
            }

            $imageJobs[] = new MarkFileProcessedJob($this->file);

            Bus::chain($imageJobs)
            ->onQueue('images')
            ->dispatch();

        } catch (\Exception $e) {

            Log::error('ProcessSingleFileJob FAILED', [
                'file' => $this->file,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }
    }
}