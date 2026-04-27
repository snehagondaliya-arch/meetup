<?php
use App\Models\Event;
use Illuminate\Support\Str;

function createUniqueSlug($title)
{
    $base = Str::slug($title);
    $slug = $base;
    $i = 1;

    while (Event::where('slug', $slug)->exists()) {
        $slug = "{$base}-{$i}";
        $i++;
    }

    return $slug;
}