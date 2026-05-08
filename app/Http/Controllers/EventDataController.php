<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;

class EventDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $organizations = Organization::all();
        return view('events.create',compact('categories','organizations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([

            'category_id' => 'required|exists:categories,id',

            'group_id' => 'required|exists:organizations,id',

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
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Event Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $validated['image'] = $request
                ->file('image')
                ->store('events', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Group Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('group_image')) {

            $validated['group_image'] = $request
                ->file('group_image')
                ->store('group_images', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Host Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('host_image')) {

            $validated['host_image'] = $request
                ->file('host_image')
                ->store('host_images', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Store Event
        |--------------------------------------------------------------------------
        */

        Event::create($validated);

        return redirect()
            ->back()
            ->withInput()
            ->with('success', 'Event created successfully.');

        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
