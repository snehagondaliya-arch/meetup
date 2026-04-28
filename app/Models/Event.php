<?php

namespace App\Models;

use App\Models\Category;
use App\Models\EventPhotos;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;


class Event extends Model
{
    protected $table = 'events';
    public const STATUS_UPCOMING = 'Upcoming';
    public const STATUS_LIVE_NOW = 'Live Now';
    public const STATUS_EXPIRED = 'Expired';

    protected $fillable = [
        'category_id',
        'group_id',
        'location_id',
        'title',
        'slug',
        'host_name',
        'host_image',
        'date_list_view',
        'datetime_text',
        'start_time',
        'end_time',
        'timezone',
        'venue_name',
        'full_address',
        'latitude',
        'longitude',
        'image_url',
        'group_image',
        'description',
        'attendees',
        'price',
        'is_online',
        'event_url',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function event_photos()
    {
        return $this->hasMany(EventPhotos::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $path = public_path(EVENT_IMAGES . $value);

                if ($value && File::exists($path)) {
                    return asset(EVENT_IMAGES . $value);
                }

                return asset(PLACEHOLDER_IMAGE);
            }
        );
    }
    protected function hostImage(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $path = public_path(HOST_IMAGES . $value);

                if ($value && File::exists($path)) {
                    return asset(HOST_IMAGES . $value);
                }

                return asset(PLACEHOLDER_IMAGE);
            }
        );

    }

    public function getStatusAttribute()
    {
        $now = Carbon::now();
        // logger()->info('current time',['time: ', $now]);
        // dd($now);

        // logger()->info('STATUS CHECK', [
        //     'id' => $this->id,
        //     'title' => $this->title,
        //     'now' => $now,
        //     'start' => $this->start_time,
        //     'end' => $this->end_time,
        // ]);

        if ($this->start_time > $now) {
            return self::STATUS_UPCOMING;
        }

        if ($this->start_time <= $now && $this->end_time !== null && $this->end_time >= $now) {
            return self::STATUS_LIVE_NOW;
        }

        return self::STATUS_EXPIRED;
    }

    public function getStartLocalAttribute()
    {
        // date: 2026-04-18 08:30:00.0 UTC (+00:00)
        // dd($this->start_time);
        //   date: 2026-04-18 14:00:00.0 Asia/Kolkata (+05:30)
        // dd($this->start_time
        //     ? Carbon::parse($this->start_time)->setTimezone($this->timezone ?? 'UTC')
        //     : null);
        return $this->start_time
            ? Carbon::parse($this->start_time)->setTimezone($this->timezone ?? 'UTC')
            : null;
    }

    public function getEndLocalAttribute()
    {
        return $this->end_time
            ? Carbon::parse($this->end_time)->setTimezone($this->timezone ?? 'UTC')
            : null;

    }
    public function getFormattedDateAttribute()
    {
        if (!$this->start_local) {
            return null;
        }

        return $this->start_local->format('d/m/y');
    }
    public function getFormattedTimeAttribute()
    {
        if (!$this->start_local) {
            return null;
        }

        return $this->start_local->format('h:i A');
    }

    public function getFormattedDateTimeAttribute()
    {
        if (!$this->start_local || !$this->end_local) {
            return null;
        }
        return $this->start_local->format('l, M d, g:i A') . ' to ' .
            $this->end_local->format('g:i A') . ' ' .
            $this->start_local->format('T');
    }
}
