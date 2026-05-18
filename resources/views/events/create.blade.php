@extends('layouts.master')

@section('title', config('app.name'))
@section('no-sidebar', true)

@section('content')
    <div class="container mt-4 mb-4">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h3 class="mb-0">Create Event</h3>
            </div>

            <div class="card-body">

                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{--BASIC INFO --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <strong>Event Basics</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                {{-- Category --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-control @error('category_id') is-invalid @enderror event-select-s1"
                                        name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Organization --}}
                                <input type="hidden" name="organization_id"
                                    value="{{ Auth::guard('organization')->user()->id }}">

                                {{-- Title --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" value="{{ old('title') }}"
                                        class="form-control @error('title') is-invalid @enderror">
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Description --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" rows="4"
                                        class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- SCHEDULE --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <strong>Schedule</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                {{-- Start Time --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Time</label>
                                    <input type="datetime-local" name="start_time" value="{{ old('start_time') }}"
                                        class="form-control @error('start_time') is-invalid @enderror">
                                    @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- End Time --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Time</label>
                                    <input type="datetime-local" name="end_time" value="{{ old('end_time') }}"
                                        class="form-control @error('end_time') is-invalid @enderror">
                                    @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Timezone --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Timezone</label>
                                    <select name="timezone" class="form-control event-select-s1">
                                        <option value="">Select Timezone</option>
                                        @foreach (DateTimeZone::listIdentifiers() as $tz)
                                            <option value="{{ $tz }}" {{ old('timezone') == $tz ? 'selected' : '' }}>
                                                {{ $tz }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- LOCATION --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <strong>Location Details</strong>
                        </div>
                        <div class="card-body">

                            <input type="hidden" name="latitude" value="">
                            <input type="hidden" name="longitude" value="">
                            <input type="hidden" name="full_address" value="">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Venue Name</label>
                                    <input type="text" name="venue_name" class="form-control">
                                </div>

                                {{-- <div class="col-12 mb-3">
                                    <label class="form-label">Full Address</label>
                                    <textarea name="full_address" class="form-control"></textarea>
                                </div> --}}

                                <div class="col-12 mb-2">
                                    <label class="form-label">Pick Location on Map</label>
                                    <div id="map" style="height: 350px; border-radius: 12px; border: 1px solid #ddd;"></div>
                                    <small class="text-muted">Click or drag marker to set location</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MEDIA --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <strong>Media</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Event Banner Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Group Image</label>
                                    <input type="file" name="group_image" class="form-control" accept="image/*">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Host Image</label>
                                    <input type="file" name="host_image" class="form-control" accept="image/*">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Event Photos</label>
                                    <input type="file" name="event_photos[]" class="form-control" multiple accept="image/*">
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- HOST & SETTINGS --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <strong>Host & Pricing</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Host Name</label>
                                    <input type="text" name="host_name" value="{{ old('host_name') }}" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label d-block">Is Online?</label>
                                    <div class="form-check form-check-inline">
                                        <input id="yes" class="form-check-input" type="radio" name="is_online" value="1" {{ old('is_online') == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="yes">Yes</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input id="no" class="form-check-input" type="radio" name="is_online" value="0" {{ old('is_online', 0) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="no">No</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            Save Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {

            // Select2
            $('.event-select-s1').select2({
                dropdownCssClass: "event-select-s1Dropdown",
            });

            // Get current location
            navigator.geolocation.getCurrentPosition(

                // Success callback
                function (position) {

                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;

                    // Initialize map with current location
                    var map = L.map('map').setView([lat, lng], 13);

                    // Tile layer
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    let marker;

                    // Update form fields
                    function updateForm(lat, lng, address = '') {
                        $('input[name="latitude"]').val(lat);
                        $('input[name="longitude"]').val(lng);
                        $('input[name="full_address"]').val(address);
                    }

                    // Set initial marker
                    marker = L.marker([lat, lng], {
                        draggable: true
                    }).addTo(map);

                    // Reverse geocode initial location
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                        .then(res => res.json())
                        .then(data => {

                            let address = data.display_name || '';

                            marker.bindPopup(address).openPopup();

                            updateForm(lat, lng, address);
                        });

                    // Drag marker event
                    marker.on('dragend', function () {

                        let pos = marker.getLatLng();

                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${pos.lat}&lon=${pos.lng}`)
                            .then(res => res.json())
                            .then(data => {

                                let addr = data.display_name || '';

                                marker.bindPopup(addr).openPopup();

                                updateForm(pos.lat, pos.lng, addr);
                            });
                    });

                    // Geocoder search
                    var geocoder = L.Control.geocoder({
                        defaultMarkGeocode: false,
                        geocoder: L.Control.Geocoder.photon()
                    })
                        .on('markgeocode', function (e) {

                            let latlng = e.geocode.center;
                            let address = e.geocode.name;

                            map.setView(latlng, 16);

                            marker.setLatLng(latlng);

                            marker.bindPopup(address).openPopup();

                            updateForm(latlng.lat, latlng.lng, address);
                        })
                        .addTo(map);
                },

                // Error callback
                function (error) {

                    console.error("Geolocation error:", error.message);

                    // Fallback map location
                    var map = L.map('map').setView([20.5937, 78.9629], 5);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);
                }
            );

        });
    </script>
@endsection