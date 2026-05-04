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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6SJeUgTcX5GvOPQu_QGMXJtRJdnXylrw"></script>

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
        setTimeout(() => {
            google.maps.event.trigger(map, "resize");
        }, 200);
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

        // Optional: center on user location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((pos) => {
                map.setView([pos.coords.latitude, pos.coords.longitude], 13);
                loadEvents();
            });
        }
    }
    function loadEvents() {
        const bounds = map.getBounds();

        axios.get('/events-by-bounds', {
            params: {
                north: bounds.getNorth(),
                south: bounds.getSouth(),
                east: bounds.getEast(),
                west: bounds.getWest(),
            }
        }).then(res => {
            renderMarkers(res.data);
        });
    }
    function renderMarkers(events) {
        markers.forEach(m => map.removeLayer(m));
        markers = [];

        events.forEach(event => {
            const marker = L.marker([event.latitude, event.longitude])
                .addTo(map)
                .bindPopup(`<b>${event.title}</b>`);

            markers.push(marker);
        });
    }
</script>