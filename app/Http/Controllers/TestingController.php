<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEventDateJson;

class TestingController extends Controller
{
   public function datetimeTest()
    {
          $files = glob(storage_path('app/json_files/*.json'));

            foreach ($files as $file) {
                    $data = json_decode(file_get_contents($file), true);
                    if (!is_array($data)) {
                        continue;
                    }
                    foreach ($data as $categorydata) {
                        if (empty($categorydata['events'])) continue;

                        foreach ($categorydata['events'] as $eventData) {
                            if (empty($eventData['datetime_text'] )) continue;
                            ProcessEventDateJson::dispatch($eventData['datetime_text']);
                        }
                    }
                }

            return response()->json([
                'message' => 'Sequential processing started',
            ]);
    }
}