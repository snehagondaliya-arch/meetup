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
    
function uploadImage($file, $folder)
{   
    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

    $file->move(public_path($folder), $filename);

    return $filename;
}