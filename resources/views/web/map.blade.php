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

                <div class="timeline-arrow" id="scrollLeft">‹</div>

                <div id="timelineScroll" class="timeline-scroll">
                    @foreach($months as $item)
                        <div class="timeline-chip 
                            {{ $item->month == $currentMonth && $item->year == $currentYear ? 'active' : '' }}"
                            data-month="{{ $item->month }}" data-year="{{ $item->year }}">
                            {{ $monthNames[$item->month] }} {{ $item->year }}
                        </div>
                    @endforeach
                </div>

                <div class="timeline-arrow" id="scrollRight">›</div>

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
        let currentDate = new Date();
        let selectedMonth = currentDate.getMonth() + 1;
        let selectedYear = currentDate.getFullYear();
        let search = '';
        document.querySelectorAll('.timeline-chip').forEach((chip, index) => {

            // console.log('Loop init → chip:', index, chip.innerText);

            chip.addEventListener('click', function () {

                // console.log('Clicked:', this.innerText);
                // console.log('Month value:', this.dataset.month);

                document.querySelectorAll('.timeline-chip').forEach((c, i) => {
                    // console.log('Removing active from:', i, c.innerText);
                    c.classList.remove('active');
                });

                this.classList.add('active');

                // console.log('active:', this.innerText);

                selectedMonth = this.dataset.month;
                selectedYear = this.dataset.year;

                console.log('Selected Month set to:', selectedMonth);
                console.log('Selected year set to:', selectedYear);

                loadData();
            });
        });

        $(document).on('input', '#search', function () {
            search = $(this).val() || '';
            loadData();
        });
        //  Month filter
        const scrollContainer = document.getElementById('timelineScroll');

        document.getElementById('scrollLeft').onclick = () => {
            scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
        };

        document.getElementById('scrollRight').onclick = () => {
            scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
        };


        // map
        let map;
        // let markers = [];
        let markerGroup;
        let debounceTimer;

        navigator.geolocation.getCurrentPosition(function (position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            // console.log('latitude: ', lat);
            // console.log('longitude: ', lng);
            // 39.4810, -0.3625
            if (map) {
                map.remove();
            }

            map = L.map('map').setView([lat, lng], 13);

            setTimeout(() => {
                map.invalidateSize();
            }, 200);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            map.on('moveend', () => {
                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(loadData, 400);
            });
            markerGroup = L.markerClusterGroup();
            map.addLayer(markerGroup);
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
                    search: search || '',
                },
                success: function (response) {
                    $('#event-container').html(response.sidebar);
                    response.events.forEach(event => {
                        console.log(event);
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
                },
            });
        }
    </script>
@endsection