<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

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
            get: fn($value) => $value
            ? asset(EVENT_PHOTOS . $value)
            : asset(PLACEHOLDER_IMAGE),
        );
    }
}
