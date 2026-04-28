<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait HasImageUrl
{
    protected function resolveImageUrl(?string $value, string $folder)
    {
        $path = public_path($folder . $value);

        if ($value && File::exists($path)) {
            return asset($folder . $value);
        }

        return asset(PLACEHOLDER_IMAGE);
    }
}