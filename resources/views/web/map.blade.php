@extends('layouts.master')
@section('title', config('app.name'))
@section('no-sidebar', true)
@section('content')
    <div class="d-flex position-relative" style="height: 80vh;">

        <!-- MAP -->
        <div id="map" class="w-75"></div>

        <!-- TIMELINE FILTER -->
        <div class="timeline-wrapper">

            <div class="timeline-container shadow">

                <div class="timeline-arrow" id="scrollLeft">‹</div>

                <div id="timelineScroll" class="timeline-scroll">
                    <div class="timeline-chip" data-month="01">January 2026</div>
                    <div class="timeline-chip" data-month="02">February 2026</div>
                    <div class="timeline-chip" data-month="03">March 2026</div>
                    <div class="timeline-chip" data-month="04">April 2026</div>
                    <div class="timeline-chip" data-month="05">May 2026</div>
                    <div class="timeline-chip" data-month="06">June 2026</div>
                    <div class="timeline-chip" data-month="07">July 2026</div>
                    <div class="timeline-chip" data-month="08">August 2026</div>
                    <div class="timeline-chip" data-month="09">September 2026</div>
                    <div class="timeline-chip" data-month="10">October 2026</div>
                    <div class="timeline-chip" data-month="11">November 2026</div>
                    <div class="timeline-chip" data-month="12">December 2026</div>
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
        let selectedMonth = null;
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

                // console.log('Now active:', this.innerText);

                selectedMonth = this.dataset.month;

                console.log('Selected Month set to:', selectedMonth);

                loadEvents();
                loadSidebar();
            });
        });
        $(document).on('input', '#search', function () {
            search = $(this).val() || '';

            loadEvents();
            loadSidebar();
        });
        function loadSidebar() {
            $.ajax({
                url: "{{ route('map') }}",
                type: "GET",
                data: {
                    search: search || '',   
                    month: selectedMonth || ''
                },
                success: function (html) {
                    document.getElementById('event-container').innerHTML = html;
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

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
            if (map) {
                map.remove();
            }

            map = L.map('map').setView([39.4810, -0.3625], 13);

            setTimeout(() => {
                map.invalidateSize();
            }, 200);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            map.on('moveend', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(loadEvents, 400);
            });
            markerGroup = L.markerClusterGroup();
            map.addLayer(markerGroup);
            loadEvents();
            loadSidebar();
        });
        function loadEvents() {
            if (!map) return;

            // markers.forEach(m => map.removeLayer(m));
            // markers = [];
            markerGroup.clearLayers();

            var bounds = map.getBounds();

            var url = `events-by-bounds?minLat=${bounds.getSouth()}&maxLat=${bounds.getNorth()}&minLng=${bounds.getWest()}&maxLng=${bounds.getEast()}`;

            if (selectedMonth) {
                url += `&month=${selectedMonth}`;
            }
            if (search) {
                url += `&search=${encodeURIComponent(search)}`;
            }
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    data.forEach(event => {
                        // console.log("Event:", event);
                        let lat = parseFloat(event.latitude);
                        let lng = parseFloat(event.longitude);
                        // let marker = L.marker([lat, lng])
                        //     .addTo(map) 
                        //     .bindPopup(event.title);

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
                                                                                    <strong>🕒 Date:</strong> ${event.formatted_date ?? ''} ${event.formatted_time ?? ''}
                                                                                </p>

                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                            `;
                        // markers.push(marker);
                        let marker = L.marker([lat, lng])
                            .bindPopup(popupContent);

                        markerGroup.addLayer(marker);
                    });
                })
                .catch(err => console.error(err));
        }
    </script>
@endsection