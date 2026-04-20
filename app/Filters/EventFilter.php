<?php

namespace App\Filters;

use Carbon\Carbon;

class EventFilter
{
    public function apply($query, $request)
    {
        return $query
            ->when(
                $request->category && $request->category !== 'all-events',
                fn($q) => $q->whereHas('category',fn($q) =>
                    $q->where('slug', $request->category)
                )
            )

            ->when(
                $request->search,
                fn($q) =>
                $q->where(function ($q) use ($request) {
                    $q->where('title', 'like', "%{$request->search}%")
                        ->orWhere('venue_name', 'like', "%{$request->search}%");
                })
            )
            ->when($request->date_filter, fn($q) => $this->dateFilter($q, $request))

            ->when(
                $request->event_type,
                fn($q) =>
                $q->where('is_online', $request->event_type === 'online')
            )

            ->when(
                $request->distance,
                fn($q) =>
                $this->distanceFilter($q, $request)
            );
    }

    private function dateFilter($q, $request)
    {
        $now = now();

        return match ($request->date_filter) {
            'starting_soon' => $q->where('start_time', '>', $now),
            'today' => $q->whereDate('start_time', $now),
            'tomorrow' => $q->whereDate('start_time', $now->copy()->addDay()),
            'this_week' => $q->whereBetween('start_time', [$now->startOfWeek(), $now->endOfWeek()]),
            'this_weekend' => $q->whereBetween('start_time', [
                $now->next(Carbon::SATURDAY)->startOfDay(),
                $now->next(Carbon::SUNDAY)->endOfDay()
            ]),
            'next_week' => $q->whereBetween('start_time', [
                $now->addWeek()->startOfWeek(),
                $now->addWeek()->endOfWeek()
            ]),
            default => $q
        };
    }

    private function distanceFilter($q, $request)
    {
        $lat = $request->latitude ?? 0;
        $lng = $request->longitude ?? 0;

        return $q->selectRaw("events.*,
            (6371 * acos(
                cos(radians(?)) *
                cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) *
                sin(radians(latitude))
            )) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $request->distance)
            ->orderBy('distance');
    }
}
