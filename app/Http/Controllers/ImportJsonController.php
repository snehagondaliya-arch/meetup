<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessSingleFileJob;

class ImportJsonController extends Controller
{
    public function start()
    {
        $files = glob(storage_path('app/json_files1/*.json'));
        foreach($files as $file){
            ProcessSingleFileJob::dispatch($file);
        }
        return response()->json([
            'message' => 'Processing started in background',
        ]);
    }
}
