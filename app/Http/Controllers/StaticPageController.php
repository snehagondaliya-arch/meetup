<?php

namespace App\Http\Controllers;

use App\Filters\EventFilter;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function index(Request $request, EventFilter $filter)
    {
        $events = $filter->apply(Event::query(), $request)
            ->latest()
            ->paginate(12);

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

    public function contact()
    {
        return view('web.contact-us');
    }

    public function eventDetail($eventID)
    {
        $event = Event::with('event_photos')->findOrFail($eventID);
        $cateID = $event->category_id;
        $events = Event::where('category_id', $cateID)
            ->where('id', '!=', $event->id)->paginate(10);
        return view('web.event-detail', compact('event', 'events'));
    }

    public function eventList(Request $request)
    {
        $category = $request->category;

        $query = Event::query();

        // Category filter
        if ($category && $category !== 'all-events') {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }
        $events = $query->latest()->paginate(100);
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
