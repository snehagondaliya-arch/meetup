<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Event;
// use Carbon\Carbon;
use Illuminate\Http\Request;


class EventController extends Controller
{
    private function baseQuery(Request $request)
    {
        return Event::query()
            ->with('category')
            ->byCategory($request->category)
            ->uniqueTitle();
    }
    public function index(Request $request)
    {
        $query = $this->baseQuery($request);

        $events = (clone $query)->paginate(8);

        $events_map = (clone $query)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->limit(50)
            ->get();

        $months = Event::selectRaw('MONTH(start_time) as month, YEAR(start_time) as year')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('web.index', compact('months','events_map','events'
        ));
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
        $events = $this->baseQuery($request)->paginate(8);
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

    public function mapData(Request $request)
    {
        $query = $this->baseQuery($request);
        // $events = (clone $query)->paginate(8);

        if ($request->search) {
            $query->search($request->search);
        }

        if ($request->month && $request->year) {
            $query->whereMonth('start_time', $request->month)
                ->whereYear('start_time', $request->year);
        }

        if (
            $request->filled(['minLat', 'maxLat', 'minLng', 'maxLng'])
        ) {
            $query->whereBetween('latitude', [$request->minLat,$request->maxLat
            ])->whereBetween('longitude', [ $request->minLng,$request->maxLng]);
        }

        $events_map = (clone $query)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
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
            ->get();

        return response()->json([
            'events_map' => $events_map,
            // 'events' => view('web.partials.events',compact('events'))->render(),
            'sidebar' => view('web.partials.map-events',compact('events_map'))->render(),
        ]);
    }
  
}
