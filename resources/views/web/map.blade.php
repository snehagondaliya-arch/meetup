@extends('layouts.master')
@section('title', config('app.name'))
@section('no-sidebar', true)
@php
    $monthNames = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    ];
    $currentMonth = now()->month;
    $currentYear = now()->year;
@endphp
@section('content')
    <div class="d-flex position-relative" style="height: 80vh;">

        <!-- MAP -->
        <div id="map" class="w-75"></div>

        <!-- TIMELINE FILTER -->
        <div class="timeline-wrapper">

            <div class="timeline-container shadow">

                {{-- <div class="timeline-arrow" id="scrollLeft">‹</div> --}}

                <div id="timelineScroll" class="timeline-scroll">
                    @foreach($months as $item)
                        <div class="timeline-chip 
                                            {{ $item->month == $currentMonth && $item->year == $currentYear ? 'active' : '' }}"
                            data-month="{{ $item->month }}" data-year="{{ $item->year }}">
                            {{ $monthNames[$item->month] }} {{ $item->year }}
                        </div>
                    @endforeach
                </div>

                {{-- <div class="timeline-arrow" id="scrollRight">›</div> --}}

            </div>

        </div>

        <!-- SIDEBAR -->
        <div class="w-25 bg-white border-start overflow-auto">
            <div class="p-3">
                <h5 class="fw-semibold mb-3">Upcoming Events</h5>
                <hr>

                <div class="mb-2">
                    <input type="text" id="search" class="form-control rounded-3"
                        placeholder="Search by location, event name...">
                </div>

                <div class="small text-muted mb-2">
                    <a href="">All Events <span class="fw-semibold"></span></a>
                </div>
            </div>

            <div id="event-container">
                @include('web.partials.map-events')
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(function () {
            function scrollChipIntoView(el) {
                if (!el || el.length === 0) return;

                el[0].scrollIntoView({
                    behavior: 'smooth',
                    inline: 'center',
                    block: 'nearest'
                });
            }

            let currentDate = new Date();
            let selectedMonth = currentDate.getMonth() + 1;
            let selectedYear = currentDate.getFullYear();
            let search = '';

            let $activeChip = $('.timeline-chip.active');
            scrollChipIntoView($activeChip);

            $('.timeline-chip').on('click', function () {

                $('.timeline-chip').removeClass('active');
                $(this).addClass('active');

                scrollChipIntoView($(this));

                selectedMonth = $(this).data('month');
                selectedYear = $(this).data('year');

                console.log('Selected Month:', selectedMonth);
                console.log('Selected Year:', selectedYear);

                loadData();
            });
            // serach
            $(document).on('input', '#search', function () {
                search = $(this).val() || '';
                loadData();
            });
            // scroll
            const scrollContainer = document.getElementById('timelineScroll');

            if (scrollContainer) {
                scrollContainer.addEventListener('wheel', function (e) {
                    e.preventDefault();

                    this.scrollBy({
                        left: e.deltaY,
                        behavior: 'smooth'
                    });
                });
            }

            // map
            let map;
            let markerGroup;
            let debounceTimer;

            navigator.geolocation.getCurrentPosition(function (position) {

                let lat = position.coords.latitude;
                let lng = position.coords.longitude;

                if (map) {
                    map.remove();
                }

                var userIcon = L.divIcon({
                    className: "custom-user-marker",
                    html: `
                        <div class="user-marker">
                            <div class="pulse"></div>
                        </div>
                    `,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                // Initialize map
                map = L.map('map').setView([lat, lng], 13);

                // Add user marker
                var userMarker = L.marker([lat, lng], {
                    icon: userIcon
                }).addTo(map);

                setTimeout(() => {
                    map.invalidateSize();
                }, 200);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Marker cluster group
                markerGroup = L.markerClusterGroup();
                map.addLayer(markerGroup);

                // Map move event
                map.on('moveend', function () {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(loadData, 400);
                });
                loadData();
            });

            function loadData() {

                if (!map) return;

                markerGroup.clearLayers();

                let bounds = map.getBounds();

                $.ajax({
                    url: "{{ route('map.data') }}",
                    type: "GET",
                    data: {
                        minLat: bounds.getSouth(),
                        maxLat: bounds.getNorth(),
                        minLng: bounds.getWest(),
                        maxLng: bounds.getEast(),
                        month: selectedMonth || '',
                        year: selectedYear || '',
                        search: search || ''
                    },
                    success: function (response) {

                        $('#event-container').html(response.sidebar);

                        response.events.forEach(function (event) {

                            // console.log(event);

                            let popupContent = `
                            <a href="event-detail/${event.slug}" class="event-card-link">
                                <div class="event-card-popup">

                                    <img src="${event.image_url}" 
                                         alt="${event.title}" 
                                         class="event-img" />

                                    <div class="event-body">
                                        <h3>${event.title}</h3>

                                        <p>
                                            <strong>📍 Location:</strong> ${event.venue_name ?? 'N/A'}
                                        </p>

                                        <p>
                                            <strong>🕒 Date:</strong>
                                            ${event.formatted_date ?? ''}
                                            ${event.formatted_time ?? ''}
                                        </p>
                                    </div>

                                </div>
                            </a>
                        `;

                            let marker = L.marker([
                                parseFloat(event.latitude),
                                parseFloat(event.longitude)
                            ]).bindPopup(popupContent);

                            markerGroup.addLayer(marker);
                        });
                    }
                });
            }

        });
    </script>
@endsection