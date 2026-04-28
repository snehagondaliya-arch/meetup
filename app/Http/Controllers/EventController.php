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
            ->when($request->date_filter, fn($q) => $this->dateFilter($q, $request))
            ->eventType($request->event_type)
            ->distanceFrom($request->latitude, $request->longitude, $request->distance)
            ->latestBySlug()
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
}
