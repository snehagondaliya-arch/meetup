@extends('layouts.master')
@section('title', config('app.name'))
@section('no-sidebar', true)
@section('content')
    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="m-0">Events</h2>
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                Create Event
            </a>
        </div>
        {{-- Search Box --}}
        <div class="row mb-5">
            <div class="col-lg-6 col-md-8">
                <div class="search-input-box position-relative">
                    <input type="text" class="form-control input-field-s1 ps-5" id="search" name="search"
                        placeholder="Search events, categories, or locations...">

                    {{-- <input type="hidden" name="category" value="{{ request('category') }}"> --}}

                    <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                        <i class="fa-solid fa-magnifying-glass fs-16px"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="row g-4" id="card-container">
            @include('events.partials.organization-events', ['events' => $events])
        </div>
        <button class="btn btn-primary mt-4" id="show-more">
            Show More
        </button>
    </div>
@endsection
@section('js')
   <script>
    let nextPageUrl = "{{ $events->nextPageUrl() }}";
    let searchValue = '';

    $(document).ready(function () {
        // console.log(nextPageUrl);
        toggleShowMore();
        function loadData(url, append = false) {
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    search: searchValue
                },
                success: function (response) {
                    // console.log(response);
                    if (!append) {
                        $('#card-container').html(response.html);
                    } else {
                        $('#card-container').append(response.html);
                    }
                   setTimeout(() => {
                    AOS.refreshHard();
                    }, 50);

                    nextPageUrl = response.next_page_url;

                   toggleShowMore();
                }
            });
        }
        function toggleShowMore() {
            if (nextPageUrl) {
                $('#show-more').show();
            } else {
                $('#show-more').hide();
            }
        }
        $('#show-more').on('click', function () {
            if (nextPageUrl) {
                loadData(nextPageUrl, true);
            }
        });

        $('#search').on('keyup', function () {
            searchValue = $(this).val().trim();
            nextPageUrl = "{{ route('events.index') }}";
            loadData(nextPageUrl, false);
        });

    });

    // GLOBAL FUNCTION
    function confirmDelete(button) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endsection