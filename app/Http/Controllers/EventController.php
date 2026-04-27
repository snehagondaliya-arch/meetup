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

            ->when(
                $request->category && $request->category !== 'all-events',
                fn($q) => $q->whereHas(
                    'category',
                    fn($q) =>
                    $q->where('slug', $request->category)
                )
            )

            ->when($request->search, function ($q) use ($request) {
                $search = '%' . $request->search . '%';

                $q->where(function ($q) use ($search) {
                    $q->where('title', 'like', $search)
                        ->orWhere('venue_name', 'like', $search);
                });
            })

            ->when($request->date_filter, fn($q) => $this->dateFilter($q, $request))

            ->when($request->event_type, function ($q) use ($request) {
                        $q->where('is_online', $request->event_type === 'online');
                    })

            ->when(
                $request->distance && $request->latitude && $request->longitude,
                fn($q) => $this->distanceFilter($q, $request)
            )

            ->whereIn('id', function ($query) {
                $query->selectRaw('MIN(id)')
                    ->from('events')
                    ->groupBy('slug');
            })

            ->when(
                !($request->distance && $request->latitude && $request->longitude),
                fn($q) => $q->orderBy('id', 'desc')
            )

            ->paginate(8);

        return $request->ajax()
            ? view('web.partials.events', compact('events'))->render()
            : view('web.index', compact('events'));
    }

    private function dateFilter($q, $request)
    {
        $now = now();

        return match ($request->date_filter) {
            'starting_soon' =>$q->whereBetween('start_time', [ now(),now()->addDays(7)]),
            'today' => $q->whereBetween('start_time', [
                $now->copy()->startOfDay()->utc(),
                $now->copy()->endOfDay()->utc()
            ]),
            'tomorrow' => $q->whereBetween('start_time', [
                $now->copy()->addDay()->startOfDay(),
                $now->copy()->addDay()->endOfDay()
            ]),
            'this_week' => $q->whereBetween('start_time', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]),
            'this_weekend' => $q->whereBetween('start_time', [
                $now->copy()->startOfWeek()->addDays(5)->startOfDay(),
                $now->copy()->startOfWeek()->addDays(6)->endOfDay()
            ]),
            'next_week' => $q->whereBetween('start_time', [
                $now->copy()->addWeek()->startOfWeek(),
                $now->copy()->addWeek()->endOfWeek()
            ]),
            default => $q
        };
    }


    private function distanceFilter($q, $request)
    {
        $lat = $request->latitude ?? 0;
        $lng = $request->longitude ?? 0;
        $distanceInMiles = $request->distance * 0.621371;
        if (!$request->latitude || !$request->longitude) {
            return $q;
        }
        return $q
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
            )) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $distanceInMiles)
            ->orderBy('distance');
    }
    public function faq()
    {
        return view('web.faq');
    }

    public function about()
    {
        return view('web.about-us');
    }

    public function contact()
    {
        return view('web.contact-us');
    }

    public function eventDetail($eventSlug)
    {
        $event = Event::with('event_photos')
            ->where('slug', $eventSlug)
            ->first();
        $cateID = $event->category_id;
        $events = Event::where('category_id', $cateID)
            ->where('id', '!=', $event->id)->paginate(8);
        return view('web.event-detail', compact('event', 'events'));
    }

    public function eventList(Request $request)
    {
        $category = $request->category;

        $query = Event::query();

        if ($category && $category !== 'all-events') {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }
        $events = $query->whereIn('id', function ($q) {
            $q->selectRaw('MIN(id)')
                ->from('events')->groupBy('slug');
        })->paginate(8);

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
}
