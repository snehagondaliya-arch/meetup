<?php

namespace App\Models;

use App\Traits\HasImageUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class EventPhotos extends Model
{
    use HasImageUrl;
    protected $table = 'event_photos';
    protected $fillable = [
        'event_id',
        'event_photos'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

     protected function eventPhotos(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->resolveImageUrl($value, EVENT_PHOTOS),
        );
    }
}
