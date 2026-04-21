<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessSingleFileJob;
use Illuminate\Support\Facades\Bus;

class ImportJsonController extends Controller
{
   public function start()
{
    $files = glob(storage_path('app/json_files/*.json'));

    $jobs = [];

    foreach ($files as $file) {
        if (str_contains($file, '_done.json')) continue;

        $jobs[] = new ProcessSingleFileJob($file);
    }

    //THIS ensures strict order
    Bus::chain($jobs)->dispatch();

    return response()->json([
        'message' => 'Sequential processing started',
    ]);
}
}