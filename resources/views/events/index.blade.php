@extends('layouts.master')
@section('title', config('app.name'))
@section('no-sidebar', true)
@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="m-0">Events</h2>
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                Create Event
            </a>
        </div>

            <table class="table table-striped table-nowrap align-middle">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Category</th>
                        <th scope="col">Organization</th>
                        <th scope="col">Title</th>
                        <th scope="col">Date & Time</th>
                        {{-- <th scope="col">Timezone</th> --}}
                        <th scope="col">Venue</th>
                        {{-- <th scope="col">Address</th> --}}
                        {{-- <th scope="col">Latitude</th>
                        <th scope="col">Longitude</th> --}}
                        <th scope="col">Group Image</th>
                        {{-- <th scope="col">Description</th> --}}
                        <th scope="col">Host Name</th>
                        <th scope="col">Host Image</th>
                        <th scope="col">Price</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        {{-- update modal --}}
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Events</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <form id="UpdateEvents" action="" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                    <div class="row">
                                     <input type="hidden" id="event_slug" name="event_slug">   
                                {{-- Category --}}  
                              <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>

                                    <select id="category_id"
                                            name="category_id"
                                            class="form-select @error('category_id') is-invalid @enderror">

                                        <option value="">Select Category</option>

                                    </select>

                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Organization --}}
                                {{-- <div class="col-md-6 mb-3">

                                    <label class="form-label">Organization</label>

                                    <select id="organization_id" name="organization_id"
                                            class="form-select @error('organization_id') is-invalid @enderror"
                                            >
                                    </select>   

                                    @error('organization_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div> --}}

                                {{-- Title --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">Title</label>

                                    <input type="text" id="title" name="title" class="form-control">

                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Start Time --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">Start Time</label>

                                  <input type="datetime-local" id="start_time" name="start_time" class="form-control">

                                    @error('start_time')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- End Time --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">End Time</label>

                                    <input type="datetime-local" id="end_time" name="end_time" class="form-control">

                                    @error('end_time')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Timezone --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">Timezone</label>

                                  <select id="timezone" name="timezone" class="form-select">
                                        <option value="">Select Timezone</option>

                                        @foreach (DateTimeZone::listIdentifiers() as $tz)
                                            <option value="{{ $tz }}"
                                                {{ old('timezone') == $tz ? 'selected' : '' }}>
                                                {{ $tz }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('timezone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                        {{-- Venue Name --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Venue Name</label>

                            <input type="text" id="venue_name" name="venue_name" class="form-control">

                            @error('venue_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Full Address --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">Full Address</label>

                            <textarea id="full_address" name="full_address" class="form-control"></textarea>

                            @error('full_address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Latitude --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Latitude</label>

                            <input type="text" id="latitude" name="latitude" class="form-control">

                            @error('latitude')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Longitude --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Longitude</label>

                           <input type="text" id="longitude" name="longitude" class="form-control">

                            @error('longitude')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Event Image --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Event Image</label>

                            <input type="file"
                                name="image_url"
                                class="form-control"
                                accept="image/*">

                            <img id="image_url" src="" width="80" 
                                    height="80"
                                    class="rounded border"
                                    style="object-fit:cover;" alt="event Image">

                            @error('image_url')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Group Image --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Group Image</label>

                            <input type="file"
                                name="group_image"
                                class="form-control"
                                accept="image/*">

                            <img id="group_image"  width="80" 
                                    height="80"
                                    class="rounded border"
                                    style="object-fit:cover;"src="" alt="group Image">
                            @error('group_image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">Description</label>

                          <textarea id="description" name="description" class="form-control"></textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Host Name --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Host Name</label>
                            <input type="text" id="host_name" name="host_name" class="form-control">

                            @error('host_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Host Image --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Host Image</label>
                             <input type="file"
                                name="host_image"
                                class="form-control"
                                accept="image/*">
                            <img id="host_image"  width="80" 
                                    height="80"
                                    class="rounded border"
                                    style="object-fit:cover;" src="" alt="host Image">

                            @error('host_image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Price --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Price</label>

                           <input type="number" id="price" name="price" class="form-control">

                            @error('price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Is Online --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label d-block">Is Online?</label>

                            <div class="form-check form-check-inline">

                                <input class="form-check-input"
                                    type="radio"
                                    name="is_online"
                                    value="1"
                                    {{ old('is_online') == '1' ? 'checked' : '' }}>

                                <label class="form-check-label">
                                    Yes
                                </label>
                            </div>

                            <div class="form-check form-check-inline">

                                <input class="form-check-input"
                                    type="radio"
                                    name="is_online"
                                    value="0"
                                    {{ old('is_online', '0') == '0' ? 'checked' : '' }}>

                                <label class="form-check-label">
                                    No
                                </label>
                            </div>

                            @error('is_online')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- events Images --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Event Photos</label>

                            <input type="file"
                                name="event_photos[]"
                                class="form-control"
                                accept="image/*"
                                multiple>
                            

                            @error('event_photos.*')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div id="event_photos_preview" class="d-flex gap-2 flex-wrap"></div>
                        </div>

                                    </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Update Post</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('js')
    <script>
$(document).ready(function () {

    let Datatable = $('.table').DataTable({
        responsive: true,
        autoWidth: false,
        scrollX: true,
        processing: true,
        serverSide: true,
        searchDelay: 500,
        info: true,
        lengthMenu: [
            [10, 25, 50],
            ['10 rows', '25 rows', '50 rows']
        ],
        columnDefs: [
            { targets: "_all", className: "text-center" }
        ],  
        language: {
            search: '',
            searchPlaceholder: "Search Here",
            processing: '<span class="spinner-border spinner-border-sm"></span> Loading...',
        },

        ajax: "{{ route('events.index') }}",

        columns: [
            {
                data: 'DT_RowIndex',
                name: 'ID',
                orderable: false,
                searchable: false
            },

            {
                data: 'image_url',
                render: function (data) {

                    return `
                        <img 
                            src="${data}" 
                            width="80"
                            height="80"
                            class="rounded border"
                            style="object-fit:cover;"
                        >
                    `;
                },
                orderable: false,
                searchable: false
            },

            { data: 'category.name' },

            { data: 'organization.organization_name' },

            { data: 'title' },

            { data: 'formatted_date_time' },

            { data: 'venue_name' },

            {
                data: 'group_image',
                render: function (data) {

                    return `
                        <img 
                            src="${data}" 
                            width="80"
                            height="80"
                            class="rounded border"
                            style="object-fit:cover;"
                        >
                    `;
                },
                orderable: false,
                searchable: false
            },

            { data: 'host_name' },

            {
                data: 'host_image',
                render: function (data) {

                    return `
                        <img 
                            src="${data}" 
                            width="80"
                            height="80"
                            class="rounded border"
                            style="object-fit:cover;"
                        >
                    `;
                },
                orderable: false,
                searchable: false
            },

            { data: 'price' },

            { data: 'status' },

            {
                data: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });



    $(document).on('click', '.EditBtn', function (e) {

        e.preventDefault();

        let slug = $(this).data('slug');

        if (!slug) {
            console.error('Event slug not found');
            return;
        }

        $.ajax({

            url: "{{ url('organization/events') }}/" + slug + "/edit",

            type: "GET",

            success: function (response) {

                // console.log(response);

                // FORM ACTION
                $('#UpdateEvents').attr(
                    'action',
                    "{{ url('organization/events') }}/" + response.event.slug
                );

                // HIDDEN ID
                $('#event_slug').val(response.event.slug);

                // IMAGES
                $("#image_url").attr("src", response.event.image_url);

                $("#group_image").attr("src", response.event.group_image);

                $("#host_image").attr("src", response.event.host_image);


                // EVENT PHOTOS
                let photosHtml = '';

                response.event.event_photos.forEach(function (photo) {

                    photosHtml += `
                        <img 
                            src="${photo.event_photos}" 
                            width="80"
                            height="80"
                            class="rounded border me-2 mb-2"
                            style="object-fit:cover;"
                        >
                    `;
                });

                $("#event_photos_preview").html(photosHtml);


                // CATEGORY
                let categorySelect = $('#category_id');

                categorySelect.empty();

                categorySelect.append(
                    '<option value="">Select Category</option>'
                );

                $.each(response.categories, function (key, category) {

                    categorySelect.append(`
                        <option value="${category.id}"
                            ${response.event.category_id == category.id ? 'selected' : ''}>
                            ${category.name}
                        </option>
                    `);

                });


                // ORGANIZATION
                // let organizationSelect = $('#organization_id');

                // organizationSelect.empty();

                // organizationSelect.append(
                //     '<option value="">Select Organization</option>'
                // );

                // $.each(response.organizations, function (key, organization) {

                //     organizationSelect.append(`
                //         <option value="${organization.id}"
                //             ${response.event.organization_id == organization.id ? 'selected' : ''}>
                //             ${organization.organization_name}
                //         </option>
                //     `);

                // });


                // INPUTS
                $('#title').val(response.event.title);

                $('#start_time').val(
                    response.event.start_time
                        ? response.event.start_time.replace(' ', 'T').slice(0, 16)
                        : ''
                );

                $('#end_time').val(
                    response.event.end_time
                        ? response.event.end_time.replace(' ', 'T').slice(0, 16)
                        : ''
                );

                $('#timezone').val(response.event.timezone);

                $('#venue_name').val(response.event.venue_name);

                $('#full_address').val(response.event.full_address);

                $('#latitude').val(response.event.latitude);

                $('#longitude').val(response.event.longitude);

                $('#description').val(response.event.description);

                $('#host_name').val(response.event.host_name);

                $('#price').val(response.event.price);


                // RADIO BUTTON
                if (response.event.is_online == 1) {

                    $('input[name="is_online"][value="1"]').prop('checked', true);

                } else {

                    $('input[name="is_online"][value="0"]').prop('checked', true);
                }


                // SHOW MODAL
                $('#editModal').modal('show');
            },

            error: function (xhr) {

                console.log(xhr.responseText);
            }
        });

    });

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
    $(document).on('click', '.DeleteBtn', function (e) {

    e.preventDefault();

    let id = $(this).data('id');

    if (!id) {
        console.error('Event ID not found');
        return;
    }

    if (confirm("Are you sure you want to delete this record?")) {

        $.ajax({

            url: "{{ url('organization/events') }}/" + id,

            type: "DELETE",

            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function (response) {

                if (response.status) {

                    Datatable.ajax.reload(null, false);
                }
            },

            error: function (xhr) {

                console.error('Error:', xhr.responseText);
            }
        });
    }

});

});
</script>
@endsection