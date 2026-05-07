<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;


class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::query()
            ->byCategory($request->category)
            ->search($request->search)
            ->when($request->date_filter, fn($q) => $q->dateFilter($request->date_filter))
            ->eventType($request->event_type)
            ->distanceFrom($request->latitude, $request->longitude, $request->distance)
            ->latestBySlug()
            ->paginate(8);

        return $request->ajax()
            ? view('web.partials.events', compact('events'))->render()
            : view('web.index', compact('events'));
    }

    public function faq()
    {
        return view('web.faq');
    }

    public function about()
    {
        return view('web.about-us');
    }

    public function eventDetail($eventSlug)
    {
        $event = Event::with('event_photos')
            ->where('slug', $eventSlug)
            ->firstOrFail();

        $events = Event::where('category_id', $event->category_id)
            ->where('id', '!=', $event->id)
            ->with('event_photos')
            ->paginate(8);

        return view('web.event-detail', compact('event', 'events'));
    }

    public function eventList(Request $request)
    {
        $events = Event::query()
            ->byCategory($request->category)
            ->latestBySlug()
            ->paginate(8);

        return ($request->ajax())
            ? view('web.partials.event-list', compact('events'))->render()
            : view('web.event-list', compact('events'));

    }

    public function privacyPolicy()
    {
        return view('web.privacy-policy');
    }

    public function termCondition()
    {
        return view('web.term-condition');
    }

    public function disclaimer()
    {
        return view('web.disclaimer');
    }
    public function map(Request $request){
       $query = Event::select([
                    'id',
                    'title',
                    'slug',
                    'latitude',
                    'longitude',
                    'start_time',
                    'image_url',
                    'venue_name',
                ]);

        if ($request->search) {
            $query->search($request->search);
        }

        if ($request->month && $request->year) {
            $query->whereMonth('start_time', $request->month)
                ->whereYear('start_time', $request->year);
        }

        $events = $query->latestBySlug()->paginate(50);
        $months = Event::selectRaw('
                    MONTH(start_time) as month,
                    YEAR(start_time) as year
                ')
                ->groupBy('month', 'year')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        return ($request->ajax())
            ? view('web.partials.map-events', compact('events'))->render()
            : view('web.map', compact('events', 'months'));
    }

    public function byBounds(Request $request)
    {
        $minLat = $request->minLat ?? $request->south;
        $maxLat = $request->maxLat ?? $request->north;
        $minLng = $request->minLng ?? $request->west;
        $maxLng = $request->maxLng ?? $request->east;

        $query = Event::select([
                    'id',
                    'title',
                    'slug',
                    'latitude',
                    'longitude',
                    'start_time',
                    'image_url',
                    'venue_name',
                ]);

        if ($request->month && $request->year) {
            $query->whereMonth('start_time', $request->month)
                ->whereYear('start_time', $request->year);
        }

        return $query
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereBetween('latitude', [$minLat, $maxLat])
            ->whereBetween('longitude', [$minLng, $maxLng])
            ->limit(200)
            ->latestBySlug()
            ->get();
    }
}
