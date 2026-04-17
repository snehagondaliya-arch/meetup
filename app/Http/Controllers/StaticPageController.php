<?php

namespace App\Http\Controllers;

use App\Models\Event;

class StaticPageController extends Controller
{
    public function index($category = null)
    {
        if ($category) {
            $events = Event::whereHas('category', function ($query) use ($category) {
                $query->where('slug', $category);
            })->paginate(100);
        } else {
            $events = Event::paginate(100);
        }
        return view('web.index',compact('events'));

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
        $events = Event::where('category_id',$cateID)
            ->where('id','!=',$event->id)->paginate(10);
        return view('web.event-detail',compact('event','events'));
    }

    public function eventList()
    {
        $events = Event::latest()->paginate(100);
        return view('web.event-list',compact('events'));
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
