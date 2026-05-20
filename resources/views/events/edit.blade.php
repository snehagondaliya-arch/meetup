@extends('layouts.master')

@section('title', config('app.name'))
@section('no-sidebar', true)

@section('content')
    <div class="container mt-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h3 class="mb-0">Update Event</h3>
            </div>

            <div class="card-body">

                <form action="{{ route('events.update', $event->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    {{-- BASIC INFO --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <strong>Event Basics</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                {{-- Category --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                        <select class="form-control @error('category_id') is-invalid @enderror event-select-s1" name="category_id">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == $event->category_id ? 'selected' : '';}}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Organization --}}
                                <input type="hidden" name="organization_id" value="{{ Auth::guard('organization')->user()->id }}">

                                {{-- Title --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">Title</label>

                                    <input type="text"
                                        id="title"
                                        name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $event->title) }}">
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                </div>

                                {{-- Description --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" rows="4"
                                        class="form-control">{{ $event->description }}</textarea>
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

                                    <input type="datetime-local"
                                        id="start_time"
                                        name="start_time"
                                        class="form-control @error('start_time') is-invalid @enderror"
                                        value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i')) }}">
                                    @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                </div>

                                {{-- End Time --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">End Time</label>

                                    <input type="datetime-local"
                                        id="end_time"
                                        name="end_time"
                                        class="form-control @error('start_time') is-invalid @enderror"
                                        value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i')) }}">
                                    @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Timezone --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">Timezone</label>

                                    <select id="timezone"
                                            name="timezone"
                                            class="form-control @error('timezone') is-invalid @enderror event-select-s1">
                                        <option value="">Select timezone</option>
                                        @foreach (DateTimeZone::listIdentifiers() as $tz)

                                            <option value="{{ $tz }}"
                                                {{ old('timezone', $event->timezone) == $tz ? 'selected' : '' }}>

                                                {{ $tz }}

                                            </option>

                                        @endforeach

                                    </select>
                                    @error('timezone') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                </div>

                                        </div>
                                    </div>
                                </div>

                    {{-- LOCATION  --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <strong>Location Details</strong>
                        </div>
                        <div class="card-body">

                            <input type="hidden"
                               id="latitude"
                               name="latitude"
                               value="{{ $event->latitude }}">

                                <input type="hidden"
                                    id="longitude"
                                    name="longitude"
                                    value="{{ $event->longitude }}">

                                <input type="hidden"
                                    id="full_address"
                                    name="full_address"
                                    value="{{ $event->full_address }}">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Venue Name</label>
                                    <input type="text"
                                            id="venue_name"
                                            name="venue_name"
                                            class="form-control @error('venue_name') is-invalid @enderror"
                                            value="{{ old('venue_name', $event->venue_name) }}">
                                         @error('venue_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                   

                                {{-- <div class="col-12 mb-3">
                                    <label class="form-label">Full Address</label>
                                    <textarea name="full_address" class="form-control"></textarea>
                                </div> --}}

                                <div class="col-12 mb-2">
                                   <label class="form-label">Pick Location on Map</label>
                        
                                    <div id="editMap"
                                        style="height:350px; border-radius:12px; border:1px solid #ddd;">
                                    </div>

                                    <small class="text-muted">
                                        Click or drag marker to set location
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--MEDIA --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <strong>Media</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Event Baneer Image</label>
                                    
                                    <input type="file"
                                        name="image_url"
                                        class="form-control"
                                        accept="image/*">

                                    <img id="image_url_preview"
                                        src="{{ $event->image_url}}"
                                        width="80"
                                        height="80"
                                        class="rounded border mt-2"
                                        style="object-fit:cover;"
                                        alt="Event Image">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Group Image</label>
                                    <input type="file"
                                        name="group_image"
                                        class="form-control"
                                        accept="image/*">

                                    <img id="group_image_preview"
                                        src="{{ $event->group_image}}"
                                        width="80"
                                        height="80"
                                        class="rounded border mt-2"
                                        style="object-fit:cover;"
                                        alt="Group Image">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Host Image</label>
                                    <input type="file"
                                            name="host_image"
                                            class="form-control"
                                            accept="image/*">

                                        <img id="host_image_preview"
                                            src="{{ $event->host_image}}"
                                            width="80"
                                            height="80"
                                            class="rounded border mt-2"
                                            style="object-fit:cover;"
                                            alt="Host Image">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Event Photos</label>
                                    <input type="file"
                                        name="event_photos[]"
                                        class="form-control"
                                        accept="image/*"
                                        multiple>

                                    <div id="event_photos_preview"
                                        class="d-flex gap-2 flex-wrap mt-3">

                                        @foreach ($event->event_photos as $photo)
                                            <img src="{{$photo->event_photos}}"
                                                width="80"
                                                height="80"
                                                class="rounded border"
                                                style="object-fit:cover;">

                                        @endforeach

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{--HOST & SETTINGS  --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <strong>Host & Pricing</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Host Name</label>
                                   <input type="text"
                                        id="host_name"
                                        name="host_name"
                                        class="form-control @error('host_name') is-invalid @enderror"
                                        value="{{ old('host_name', $event->host_name) }}">
                                    @error('host_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="number"
                                            id="price"
                                            name="price"
                                            class="form-control"
                                            value="{{ old('price', $event->price) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label d-block">Is Online?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="is_online"
                                            value="1"
                                            id="yes"
                                            {{ $event->is_online == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="yes">Yes</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="is_online"
                                            value="0"
                                            id="no"
                                            {{ $event->is_online == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="no">No</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            Update Event
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
            $('.event-select-s1').select2({
                dropdownCssClass: "event-select-s1Dropdown",
            });

            let editMap = null;
            let editMarker = null;
            let geocoderControl = null;

            function initEditMap(lat = 51.505, lng = -0.09, address = '') {

                if (editMap !== null) {
                    editMap.remove();
                    editMap = null;
                }

                editMap = L.map('editMap').setView([lat, lng], 13);
                // L.tileLayer(
                //         'http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',
                //         {
                //             subdomains:['mt0','mt1','mt2','mt3']
                //         }).addTo(editMap);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(editMap);

                editMarker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(editMap);

                if (address) {
                    editMarker.bindPopup(address).openPopup();
                }

                $('#latitude').val(lat);
                $('#longitude').val(lng);
                $('#full_address').val(address);

                // drag marker
                editMarker.on('dragend', function () {

                    let pos = editMarker.getLatLng();

                    $('#latitude').val(pos.lat);
                    $('#longitude').val(pos.lng);

                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${pos.lat}&lon=${pos.lng}`)
                        .then(res => res.json())
                        .then(data => {

                            let newAddress = data.display_name || '';

                            $('#full_address').val(newAddress);

                            editMarker
                                .bindPopup(newAddress)
                                .openPopup();
                        });
                });

                editMap.on('click', function (e) {

                    let latlng = e.latlng;

                    editMarker.setLatLng(latlng);

                    $('#latitude').val(latlng.lat);
                    $('#longitude').val(latlng.lng);

                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`)
                        .then(res => res.json())
                        .then(data => {

                            let newAddress = data.display_name || '';

                            $('#full_address').val(newAddress);

                            editMarker
                                .bindPopup(newAddress)
                                .openPopup();
                        });
                });

                if (geocoderControl !== null) {
                    geocoderControl.remove();
                }

                // search control
                geocoderControl = L.Control.geocoder({
                    defaultMarkGeocode: false,
                    geocoder: L.Control.Geocoder.photon()
                })
                .on('markgeocode', function (e) {

                    let latlng = e.geocode.center;
                    let searchedAddress = e.geocode.name;

                    editMap.setView(latlng, 16);

                    editMarker.setLatLng(latlng);

                    $('#latitude').val(latlng.lat);
                    $('#longitude').val(latlng.lng);
                    $('#full_address').val(searchedAddress);

                    editMarker
                        .bindPopup(searchedAddress)
                        .openPopup();
                })
                .addTo(editMap);

                setTimeout(() => {
                    editMap.invalidateSize();
                }, 300);
            }


            let savedLat = parseFloat($('#latitude').val()) || 51.505;
            let savedLng = parseFloat($('#longitude').val()) || -0.09;
            let savedAddress = $('#full_address').val() || '';

            initEditMap(savedLat, savedLng,savedAddress);

        });
    </script>
@endsection