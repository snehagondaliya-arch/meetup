<nav class="navbar header-s2 navbar-expand-lg navbar-light py-3">
    <div class="container">
        <a class="navbar-brand fw-600" href="{{ route('index') }}">
            <!-- <img src="#" alt="logo" style="max-width: 200px;" class="d-sm-block d-none">
                    <img src="#" alt="logo" style="max-width: 40px;" class="d-block d-sm-none"> -->
            <h3 class="fs-24px gt-text-theme fw-600 mb-0">{{ config('app.name') }}</h3>
        </a>
        <div class="d-lg-block d-none mx-auto">
            <ul class="navbar-nav custom-scroll px-lg-0 px-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index')}}">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('event-list') ? 'active' : '' }}"
                        href="{{ route('event-list')}}">
                        Event
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        Blog
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq')}}">
                        Faq's
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('disclaimer') ? 'active' : '' }}"
                        href="{{ route('disclaimer')}}">
                        Disclaimer
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                        href="{{ route('contact.index') }}">
                        Contact Us
                    </a>
                </li>
            </ul>
        </div>
        <i class="fa-solid fa-location-dot location-icon" id="open-map"></i>
        <div id="map-modal" style="
            display:none;
            position:fixed;
            inset:0;
            background:#fff;
            z-index:9999;
        ">
            <div id="map" style="height:100%;"></div>

            <button id="close-map" style="
                position:absolute;
                top:10px;
                right:10px;
                z-index:1000;
            ">✖</button>
        </div>
        {{-- Google api --}}
        {{-- <i class="fa-solid fa-location-dot location-icon" id="open-map"></i>
        <div id="map-modal" style="
            display:none;
            position:fixed;
            inset:0;
            background:#fff;
            z-index:9999;
        ">
            <div id="map" style="height:100%;"></div>

            <button id="close-map" style="
                position:absolute;
                top:10px;
                right:10px;
                z-index:1000;
            ">✖</button>
        </div> --}}

        <div class="d-flex align-items-center gap-2 ms-lg-0 ms-auto">
            @auth
                <ul class="list-unstyled user-profile mb-0 ms-lg-2">
                    <li class="nav-item dropdown">
                        <a href="javascript:void(0);" class="nav-link d-flex align-items-center gap-2"
                            id="notification-drop" data-bs-toggle="dropdown">
                            <div class="profile-image">
                                <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=100&rounded=true&bold=true&color=25AEA1&background=f8f9fa' }}"
                                    class="rounded-circle" style="width:50px; height:50px; object-fit:cover;" alt="user">
                            </div>
                        </a>
                        <div class="p-0 sub-drop dropdown-menu dropdown-s1 dropdown-menu-end"
                            aria-labelledby="notification-drop">
                            <div class="m-0 card ">
                                <div class="p-0 card-body">
                                    <a href="javascript:void(0);" class="iq-sub-card">
                                        <div class="d-flex align-items-center p-3 border-bottom">
                                            <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=100&rounded=true&bold=true&color=25AEA1&background=f8f9fa' }}"
                                                class="rounded-circle" style="width:50px; height:50px; object-fit:cover;"
                                                alt="user">

                                            <div class="ms-2 flex-grow-1 text-start gt-text-ffffff">
                                                <h6 class="mb-0 gt-text-title">{{ Auth::user()->name }}</h6>
                                                <p class="mb-0 fs-14px text-muted">{{ Auth::user()->email }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="iq-sub-card d-flex justify-content-center p-3">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button class="btn btn-outline-primary w-100">
                                                Sign out
                                                <i class="fa-solid fa-arrow-right-from-bracket ms-1"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            @endauth
            @guest
                <!-- Login-btn -->
                <a href="javascript:void(0);" class="btn btn-primary header-btn" data-bs-toggle="modal"
                    data-bs-target="#loginBackdrop">Sign In</a>
            @endguest
            <!-- Side-menu-toggle-btn -->
            <button class="navbar-toggler header-s1toggle-btn topbar-click-menu shadow-none border-0" type="button"
                id="categoryButton">
                <div class="position-relative w-100">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
        </div>
    </div>
</nav>
{{-- Google api --}}
{{--
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6SJeUgTcX5GvOPQu_QGMXJtRJdnXylrw"></script> --}}
{{--
<script>
    let map;
    let mapLoaded = false;

    // Open map when icon clicked
    document.getElementById('open-map').addEventListener('click', () => {
        document.getElementById('map-modal').style.display = 'block';

        // Initialize map only once
        if (!mapLoaded) {
            initMap();
            mapLoaded = true;
        }

        // Fix rendering issue
        // setTimeout(() => {
        //     google.maps.event.trigger(map, "resize");
        // }, 200);
    });

    // Close map
    document.getElementById('close-map').addEventListener('click', () => {
        document.getElementById('map-modal').style.display = 'none';
    });

    // Initialize map
    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 21.1702, lng: 72.8311 }, // Surat default
            zoom: 12,
        });

    }

</script> --}}
{{--
<script>
    let map;
    let markers = [];
    document.getElementById('open-map').addEventListener('click', () => {
        document.getElementById('map-modal').style.display = 'block';

        navigator.geolocation.getCurrentPosition(function (position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            console.log('latitude: ', lat);
            console.log('longitude: ', lng);

            map = L.map('map').setView([39.4810, -0.3625], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // L.marker([41.3285, 19.8183]).addTo(map)
            //     .bindPopup("You are here")
            //     .openPopup();

            map.on('moveend', loadEvents);

            loadEvents();
        }, function (error) {
            alert("Location access denied or unavailable");
        });
    });

    function loadEvents() {
        if (!map) return;
        markers.forEach(m => map.removeLayer(m));
        markers = [];
        var bounds = map.getBounds();
        if (bounds) {
            var southWest = bounds.getSouthWest();
            var northEast = bounds.getNorthEast();
            console.log("SW: " + southWest.toString());
            console.log("NE: " + northEast.toString());
            // var url = `events-by-bounds?minLat=41.3285000&maxLat=41.3285000&minLng=19.8183770&maxLng=19.8183770`;
            var url = `events-by-bounds?minLat=${bounds.getSouth()}&maxLat=${bounds.getNorth()}&minLng=${bounds.getWest()}&maxLng=${bounds.getEast()}`;
            console.log(url);
            fetch(url)
                .then(res => res.json())

                .then(data => {
                    console.log("API DATA:", data);
                    data.forEach(event => {
                        console.log("Event:", event);
                        let lat = parseFloat(event.latitude);
                        let lng = parseFloat(event.longitude);
                        let marker = L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup(event.title)
                            .openPopup();;

                        markers.push(marker);
                    });
                });

        }
    }
    document.getElementById('close-map').addEventListener('click', () => {
        document.getElementById('map-modal').style.display = 'none';
    });
</script> --}}