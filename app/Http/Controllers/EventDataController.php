<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventPhotos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EventDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Event::with(['category', 'organization'])->latestByTitle();

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('image_url', fn($event) => $event->image_url)
                ->addColumn('formatted_date_time', fn($event) => $event->formatted_date_time)
                ->addColumn('group_image', fn($event) => $event->group_image)
                ->addColumn('host_image', fn($event) => $event->host_image)
                ->addColumn('status', function ($event) {
                    return $event->is_online
                        ? '<span class="badge bg-success">' . Event::ONLINE . '</span>'
                        : '<span class="badge bg-secondary">' . Event::OFFLINE . '</span>';
                })
                ->addColumn('action', function ($event) {
                    return '<a href="#" type="button" data-bs-toggle="modal" data-bs-target="#editModal" class="EditBtn"data-slug="' . $event->slug . '">
                                <i class="fas fa-edit text-success"></i>
                            </a>
                            <a href="" class="DeleteBtn" data-id="' . $event->id . '">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>';
                })

                ->rawColumns(['image_url', 'group_image', 'host_image', 'status', 'action'])
                ->make(true);
        }

        return view('events.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('events.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'organization_id' => 'required|exists:organizations,id',
            'title' => 'required|string|max:255',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'timezone' => 'nullable|string|max:100',
            'venue_name' => 'nullable|string|max:255',
            'full_address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'group_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'host_name' => 'nullable|string|max:255',
            'host_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'nullable|numeric|min:0',
            'is_online' => 'required|boolean',
            'event_photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        $slug = createUniqueSlug($request->title ?? null);
        $validated['slug'] = $slug;

        $imagepath = [
            'events' => 'uploads/events',
            'groups' => 'uploads/groups',
            'hosts' => 'uploads/hosts',
            'event_photos' => 'uploads/event_photos'
        ];

        if ($request->hasFile('image')) {
            $validated['image_url'] = uploadImage($request->file('image'), $imagepath['events']);
        }

        if ($request->hasFile('group_image')) {
            $validated['group_image'] = uploadImage($request->file('group_image'), $imagepath['groups']);

        }
        if ($request->hasFile('host_image')) {
            $validated['host_image'] = uploadImage($request->file('host_image'), $imagepath['hosts']);
        }
        if ($request->start_time) {
                $validated['start_time'] = Carbon::parse(
                    $request->start_time,
                    $request->timezone ?? config('app.timezone')
                )->utc();
        }

            if ($request->end_time) {
                    $validated['end_time'] = Carbon::parse( 
                        $request->end_time,
                        $request->timezone ?? config('app.timezone')
                    )->utc();
            }
            $event = Event::create($validated);
            if ($request->hasFile('event_photos')) {

                foreach ($request->file('event_photos') as $photo) {
                    $event_photos = uploadImage($photo, $imagepath['event_photos']);
                EventPhotos::create([
                    'event_id' => $event->id,
                    'event_photos' => $event_photos
                ]);
            }
        }

        return redirect()
            ->route('events.index')
            ->with('success', 'Event created successfully.');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $event = Event::with('event_photos')
            ->where('slug', $slug)
            ->firstOrFail();
        $categories = Category::all();

        return response()->json([
            'event' => $event,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required',
            'organization_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'timezone' => 'required',
            'venue_name' => 'nullable|string|max:255',
            'full_address' => 'nullable|string',
            'description' => 'nullable|string',
            'host_name' => 'nullable|string|max:255',
            'price' => 'nullable',
            'image_url' => 'nullable|image',
            'group_image' => 'nullable|image',
            'host_image' => 'nullable|image',
            'event_photos.*' => 'nullable|image'
        ]);

        // Update Event Data
        $event->title = $request->title;
        $event->category_id = $request->category_id;
        $event->organization_id = $request->organization_id;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->timezone = $request->timezone;
        $event->venue_name = $request->venue_name;
        $event->full_address = $request->full_address;
        $event->latitude = $request->latitude;
        $event->longitude = $request->longitude;
        $event->description = $request->description;
        $event->host_name = $request->host_name;
        $event->price = $request->price;
        $event->is_online = $request->is_online;

        $imagepath = [
            'events' => 'uploads/events',
            'groups' => 'uploads/groups',
            'hosts' => 'uploads/hosts',
            'event_photos' => 'uploads/event_photos'
        ];


        // EVENT IMAGE
        // if ($event->image_url) {
        //         $debugPath = public_path(EVENT_IMAGES . $event->getRawOriginal('image_url'));
        //         if (!file_exists($debugPath)) {
        //             \Log::error("File not found at: " . $debugPath);
        //         }
        //     }
        if ($request->hasFile('image_url')) {

            if ($event->getRawOriginal('image_url') && file_exists(public_path(EVENT_IMAGES . $event->getRawOriginal('image_url')))) {
                unlink(public_path(EVENT_IMAGES . $event->getRawOriginal('image_url')));
            }

            $event->forceFill(['image_url' => uploadImage($request->file('image_url'), $imagepath['events'])]);

        }


        // GROUP IMAGE
        if ($request->hasFile('group_image')) {

            if ($event->getRawOriginal('group_image') && file_exists(public_path(GROUP_IMAGES . $event->getRawOriginal('group_image')))) {
                unlink(public_path(GROUP_IMAGES . $event->getRawOriginal('group_image')));
            }

            $event->forceFill(['group_image' => uploadImage($request->file('group_image'), $imagepath['groups'])]);
        }


        // HOST IMAGE
        if ($request->hasFile('host_image')) {

            if ($event->getRawOriginal('host_image') && file_exists(public_path(HOST_IMAGES .$event->getRawOriginal('host_image')))) {
                unlink(public_path(HOST_IMAGES . $event->getRawOriginal('host_image')));
            }

            $event->forceFill(['host_image' => uploadImage($request->file('host_image'), $imagepath['hosts'])]);
        }


        // SAVE EVENT
        $event->save();


        // EVENT PHOTOS
        if ($request->hasFile('event_photos')) {

            // delete old
            foreach ($event->event_photos as $photo) {

                $oldImage = $photo->getRawOriginal('event_photos');

                if ($oldImage && file_exists(public_path(EVENT_PHOTOS . $oldImage))) {
                    unlink(public_path(EVENT_PHOTOS . $oldImage));
                }

                $photo->delete();
            }

            // add new
            foreach ($request->file('event_photos') as $file) {

                EventPhotos::create([
                    'event_id' => $event->id,
                    'event_photos' => uploadImage($file, $imagepath['event_photos'])
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Event updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = Event::with('event_photos')->findOrFail($id);

        // Delete event image
        if (
            $event->getRawOriginal('image_url') &&
            file_exists(public_path(EVENT_IMAGES . $event->getRawOriginal('image_url')))
        ) {

            unlink(public_path(EVENT_IMAGES . $event->getRawOriginal('image_url')));
        }

        // Delete group image
        if (
            $event->getRawOriginal('group_image') &&
            file_exists(public_path(GROUP_IMAGES . $event->getRawOriginal('group_image')))
        ) {

            unlink(public_path(GROUP_IMAGES . $event->getRawOriginal('group_image')));
        }

        // Delete host image
        if ($event->getRawOriginal('host_image') && file_exists(public_path(HOST_IMAGES . $event->getRawOriginal('host_image')))) {

            unlink(public_path(HOST_IMAGES . $event->getRawOriginal('host_image')));
        }

        // Delete gallery image files
        foreach ($event->event_photos as $photo) {

            if (
                $photo->getRawOriginal('event_photos') &&
                file_exists(public_path(EVENT_PHOTOS . $photo->getRawOriginal('event_photos')))
            ) {

                unlink(public_path(EVENT_PHOTOS . $photo->getRawOriginal('event_photos')));
            }
        }

        // Database rows auto delete because of cascadeOnDelete()
        $event->delete();

        return response()->json([
            'status' => true,
            'message' => 'Event deleted successfully'
        ]);
    }
}
