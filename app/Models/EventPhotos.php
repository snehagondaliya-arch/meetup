<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class EventPhotos extends Model
{
    protected $table = 'event_photos';
    protected $fillable = [
        'event_id',
        'event_photos'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    protected function eventphotos(): Attribute
    {
         return Attribute::make(
            get: function ($value) {
                $path = public_path(EVENT_PHOTOS . $value);

                if ($value && File::exists($path)) {
                    return asset(EVENT_PHOTOS . $value);
                }

                return asset(PLACEHOLDER_IMAGE);
            }
        );
    }
}
