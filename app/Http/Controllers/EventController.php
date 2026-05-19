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
            ->uniqueTitle()
            ->paginate(8);
        // $events_map = [];
        $events_map = Event::uniqueTitle()->limit(50)->get();
        $months = Event::selectRaw('
                MONTH(start_time) as month,
                YEAR(start_time) as year
            ')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        return view('web.index', compact('months','events_map','events'));
        // return $request->ajax()
        //     ? view('web.partials.events', compact('events'))->render()
        //     : view('web.index', compact('months','events_map'));
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
            ->uniqueTitle()
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

    // public function map()
    // {
    //     $events = Event::uniqueTitle()->limit(50)->get();
    //     $months = Event::selectRaw('
    //             MONTH(start_time) as month,
    //             YEAR(start_time) as year
    //         ')
    //         ->groupBy('month', 'year')
    //         ->orderBy('year')
    //         ->orderBy('month')
    //         ->get();

    //     return view('web.map', compact('events', 'months'));
    // }
    public function mapData(Request $request)
    {   
        $events = Event::query()
            ->byCategory($request->category)
            ->uniqueTitle()
            ->paginate(8);
         $query = Event::with('category')
            ->select([
                'id',
                'title',
                'category_id',
                'slug',
                'latitude',
                'longitude',
                'start_time',
                'image_url',
                'venue_name',
            ])
            ->uniqueTitle()
            ->byCategory($request->category);

        // Search
        if ($request->search) {
            $query->search($request->search);
        }

        // Month filter
        if ($request->month && $request->year) {
            $query->whereMonth('start_time', $request->month)
                ->whereYear('start_time', $request->year);
        }

        // Bounds filter
        if (
            $request->minLat &&
            $request->maxLat &&
            $request->minLng &&
            $request->maxLng
        ) {
            $query->whereBetween('latitude', [$request->minLat, $request->maxLat])
                ->whereBetween('longitude', [$request->minLng, $request->maxLng]);
        }
        $events_map = [];
        $query
            // ->whereNotNull('latitude')
            // ->whereNotNull('longitude')
            ->chunk(500, function ($events) use (&$events_map) {
                foreach ($events as $event) {
                    $events_map[] = $event;
                }
            });

        return response()->json([
            'events_map' => $events_map,
            'events' => view('web.partials.events',compact('events'))->render(),
            'sidebar' => view('web.partials.map-events',compact('events_map'))->render(),
        ]);
    }
  
}
