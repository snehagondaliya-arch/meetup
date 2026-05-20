<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Models\{Category, Event, EventPhotos};
use Carbon\Carbon;
use Illuminate\Http\Request;


class EventDataController extends Controller
{   
    private array $imagepath = [
        'events' => 'uploads/events',
        'groups' => 'uploads/groups',
        'hosts' => 'uploads/hosts',
        'event_photos' => 'uploads/event_photos',
    ];

    private function updateImage($request, $event, $field, $constant, $pathKey)
    {
        if ($request->hasFile($field)) {

            $this->deleteFile($constant, $event->getRawOriginal($field));

            $event->$field = uploadImage(
                $request->file($field),
                $this->imagepath[$pathKey]
            );
        }
    }

    private function deleteFile($path, $file)
    {
        if ($file && file_exists(public_path($path . $file))) {
            unlink(public_path($path . $file));
        }
    }

    public function index(Request $request)
    {
        $events = Event::with(['category', 'organization'])
            ->forOrganization(auth('organization')->id())
            ->uniqueTitle()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->paginate(8);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('events.partials.organization-events', compact('events'))->render(),
                'next_page_url' => $events->nextPageUrl(),
            ]);
        }

        return view('events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::whereNotIn('slug', ['all-events'])->get();
        return view('events.create', compact('categories'));
    }

    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = createUniqueSlug($request->title);

        if ($request->hasFile('image')) {
            $validated['image_url'] = uploadImage($request->file('image'), $this->imagepath['events']);
        }

        if ($request->hasFile('group_image')) {
            $validated['group_image'] = uploadImage($request->file('group_image'), $this->imagepath['groups']);

        }
        if ($request->hasFile('host_image')) {
            $validated['host_image'] = uploadImage($request->file('host_image'), $this->imagepath['hosts']);
        }

        foreach (['start_time', 'end_time'] as $time) {
            if ($request->$time) {
                $validated[$time] = Carbon::parse(
                    $request->$time,
                    $request->timezone ?? config('app.timezone')
                )->utc();
            }
        }

        $event = Event::create($validated);

        if ($request->hasFile('event_photos')) {
            foreach ($request->file('event_photos') as $photo) {
                EventPhotos::create([
                    'event_id' => $event->id,
                    'event_photos' => uploadImage($photo, $this->imagepath['event_photos'])
                ]);
            }
        }

        return redirect()
            ->route('events.index')
            ->with('success', 'Event created successfully.');

    }

    public function edit(string $slug)
    {
        $event = Event::with('event_photos')
            ->forOrganization(auth('organization')->id())
            ->where('slug', $slug)
            ->firstOrFail();
        $categories = Category::whereNotIn('slug', ['all-events'])->get();

        return view('events.edit', compact('event', 'categories'));
    }

    public function update(StoreEventRequest $request, $slug)
    {
        $event = Event::with('event_photos')->where('slug', $slug)->firstOrFail();

        // Validation
        $event->fill($request->validated());

        foreach (['start_time', 'end_time'] as $time) {
            if ($request->$time) {
                $event->$time = Carbon::parse($request->$time,$request->timezone ?? config('app.timezone'))->utc();
            }
        }
        $this->updateImage($request, $event, 'image_url', EVENT_IMAGES, 'events');

        $this->updateImage($request, $event, 'group_image', GROUP_IMAGES, 'groups');

        $this->updateImage($request, $event, 'host_image', HOST_IMAGES, 'hosts');
      
        // SAVE EVENT
        $event->save();

        // EVENT PHOTOS
        if ($request->hasFile('event_photos')) {
            // delete old
            foreach ($event->event_photos as $photo) {
                $this->deleteFile(EVENT_PHOTOS, $photo->getRawOriginal('event_photos'));
                $photo->delete();
            }
            // add new
            foreach ($request->file('event_photos') as $file) {
                EventPhotos::create([
                    'event_id' => $event->id,
                    'event_photos' => uploadImage($file, $this->imagepath['event_photos'])
                ]);
            }
        }

        return redirect()
            ->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::with('event_photos')->findOrFail($id);

        $this->deleteFile(EVENT_IMAGES, $event->getRawOriginal('image_url'));
        $this->deleteFile(GROUP_IMAGES, $event->getRawOriginal('group_image'));
        $this->deleteFile(HOST_IMAGES, $event->getRawOriginal('host_image'));

        // Delete event image
        foreach ($event->event_photos as $photo) {
            $this->deleteFile(EVENT_PHOTOS, $photo->getRawOriginal('event_photos'));
        }

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event Deleted successfully.');

    }

   
}
