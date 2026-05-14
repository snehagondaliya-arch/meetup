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

            <form id="UpdateEvents"
                  action="{{ route('events.update', $event->slug) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row">

                    <input type="hidden"
                           id="event_slug"
                           name="event_slug"
                           value="{{ $event->slug }}">

                    {{-- Category --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Category</label>

                        <select id="category_id"
                                name="category_id"
                                class="form-control event-select-s1">
                        <option value="">Select Category</option>
                            @foreach ($categories as $category)

                                <option value="{{ $category->id }}"
                                    {{ $event->category_id == $category->id ? 'selected' : '' }}>

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Title --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Title</label>

                        <input type="text"
                               id="title"
                               name="title"
                               class="form-control"
                               value="{{ old('title', $event->title) }}">

                    </div>

                    {{-- Start Time --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Start Time</label>

                        <input type="datetime-local"
                               id="start_time"
                               name="start_time"
                               class="form-control"
                               value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i')) }}">

                    </div>

                    {{-- End Time --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">End Time</label>

                        <input type="datetime-local"
                               id="end_time"
                               name="end_time"
                               class="form-control"
                               value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i')) }}">

                    </div>

                    {{-- Timezone --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Timezone</label>

                        <select id="timezone"
                                name="timezone"
                                class="form-control event-select-s1">

                            @foreach (DateTimeZone::listIdentifiers() as $tz)

                                <option value="{{ $tz }}"
                                    {{ old('timezone', $event->timezone) == $tz ? 'selected' : '' }}>

                                    {{ $tz }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Venue --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Venue Name</label>

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

                        <input type="text"
                               id="venue_name"
                               name="venue_name"
                               class="form-control"
                               value="{{ old('venue_name', $event->venue_name) }}">
                    </div>

                    {{-- Map --}}
                    <div class="col-12 mb-3">

                        <label class="form-label">Pick Location on Map</label>
                        
                        <div id="editMap"
                             style="height:350px; border-radius:12px; border:1px solid #ddd;">
                        </div>

                        <small class="text-muted">
                            Click or drag marker to set location
                        </small>

                    </div>

                    {{-- Event Image --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Event Image</label>

                        <input type="file"
                               name="image_url"
                               class="form-control"
                               accept="image/*">

                        <img id="image_url_preview"
                             src="{{ asset($event->image_url) }}"
                             width="80"
                             height="80"
                             class="rounded border mt-2"
                             style="object-fit:cover;"
                             alt="Event Image">

                    </div>

                    {{-- Group Image --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Group Image</label>

                        <input type="file"
                               name="group_image"
                               class="form-control"
                               accept="image/*">

                        <img id="group_image_preview"
                             src="{{ asset($event->group_image) }}"
                             width="80"
                             height="80"
                             class="rounded border mt-2"
                             style="object-fit:cover;"
                             alt="Group Image">

                    </div>

                    {{-- Description --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">Description</label>

                        <textarea id="description"
                                  name="description"
                                  class="form-control"
                                  rows="4">{{ old('description', $event->description) }}</textarea>

                    </div>

                    {{-- Host Name --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Host Name</label>

                        <input type="text"
                               id="host_name"
                               name="host_name"
                               class="form-control"
                               value="{{ old('host_name', $event->host_name) }}">

                    </div>

                    {{-- Host Image --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Host Image</label>

                        <input type="file"
                               name="host_image"
                               class="form-control"
                               accept="image/*">

                        <img id="host_image_preview"
                             src="{{ asset($event->host_image) }}"
                             width="80"
                             height="80"
                             class="rounded border mt-2"
                             style="object-fit:cover;"
                             alt="Host Image">

                    </div>

                    {{-- Price --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">Price</label>

                        <input type="number"
                               id="price"
                               name="price"
                               class="form-control"
                               value="{{ old('price', $event->price) }}">

                    </div>

                    {{-- Is Online --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label d-block">Is Online?</label>

                        <div class="form-check form-check-inline">

                            <input class="form-check-input"
                                   type="radio"
                                   name="is_online"
                                   value="1"
                                   {{ $event->is_online == 1 ? 'checked' : '' }}>

                            <label class="form-check-label">
                                Yes
                            </label>

                        </div>

                        <div class="form-check form-check-inline">

                            <input class="form-check-input"
                                   type="radio"
                                   name="is_online"
                                   value="0"
                                   {{ $event->is_online == 0 ? 'checked' : '' }}>

                            <label class="form-check-label">
                                No
                            </label>

                        </div>

                    </div>

                    {{-- Event Photos --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">Event Photos</label>

                        <input type="file"
                               name="event_photos[]"
                               class="form-control"
                               accept="image/*"
                               multiple>

                        <div id="event_photos_preview"
                             class="d-flex gap-2 flex-wrap mt-3">

                            @foreach ($event->event_photos as $photo)

                                <img src="{{ asset($photo->image) }}"
                                     width="80"
                                     height="80"
                                     class="rounded border"
                                     style="object-fit:cover;">

                            @endforeach

                        </div>

                    </div>

                </div>

                <div class="text-end">

                    <button type="submit"
                            class="btn btn-primary">

                        Update Event

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@section('scripts')
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

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(editMap);

                // draggable marker
                editMarker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(editMap);

                if (address) {
                    editMarker.bindPopup(address).openPopup();
                }

                // set initial values
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

                // click on map
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

                // remove old geocoder
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

            // =========================
            // INITIALIZE EDIT MAP
            // =========================

            let savedLat = parseFloat($('#latitude').val()) || 51.505;
            let savedLng = parseFloat($('#longitude').val()) || -0.09;
            let savedAddress = $('#full_address').val() || '';

            initEditMap(savedLat, savedLng,);

        });
    </script>
@endsection
{{-- 
        <script>
             $(document).on('submit', '#UpdateEvents', function (e) {

                e.preventDefault();

                let form = $(this);

                let url = form.attr('action');
                console.log(url);

                let formData = new FormData(this);

                $.ajax({

                    url: url,

                    type: "POST",

                    data: formData,

                    processData: false,

                    contentType: false,

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function (response) {

                        // console.log(response);

                        if (response.status) {

                            $('#editModal').modal('hide');

                            Datatable.ajax.reload(null, false);
                        }
                    },

                    error: function (xhr) {

                        console.log(xhr.responseText);
                    }
                });

            });
        </script> --}}

         