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
        'title',
        'slug',
        'host_name',
        'host_image',
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
        'price',
        'is_online',
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

    public function scopeByCategory($query, $categorySlug)
    {
        return $query->when($categorySlug && $categorySlug !== 'all-events', function ($query) use ($categorySlug) {
            $query->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query) use ($search) {
            $like = '%' . $search . '%';

            $query->where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
                    ->orWhere('venue_name', 'like', $like);
            });
        });
    }

    public function scopeEventType($query, $eventType)
    {
        return $query->when($eventType, function ($query) use ($eventType) {
            $query->where('is_online', $eventType === 'online');
        });
    }

    public function scopeDistanceFrom($query, $latitude, $longitude, $distance)
    {
        if (!$latitude || !$longitude || !$distance) {
            return $query;
        }

        $distanceInMiles = $distance * 0.621371;

        return $query
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0)
            ->selectRaw("events.*,
            (3959 * acos(
                cos(radians(?)) *
                cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) *
                sin(radians(latitude))
            )) AS distance", [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $distanceInMiles)
            ->orderBy('distance');
    }

    public function scopeLatestBySlug($query)
    {
        return $query->whereIn('id', function ($query) {
            $query->selectRaw('MIN(id)')
                ->from('events')
                ->groupBy('slug');
        });
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
