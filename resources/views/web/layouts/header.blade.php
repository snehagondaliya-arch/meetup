<nav class="navbar header-s2 navbar-expand-lg navbar-light py-3">
    <div class="container">

        {{-- Brand --}}
        <a class="navbar-brand fw-600" href="{{ route('index') }}">
            <h3 class="fs-24px gt-text-theme fw-600 mb-0">
                {{ config('app.name') }}
            </h3>
        </a>

        {{-- Navigation --}}
        <div class="d-lg-block d-none mx-auto">
            <ul class="navbar-nav custom-scroll px-lg-0 px-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index') }}">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('event-list') ? 'active' : '' }}"
                        href="{{ route('event-list') }}">
                        Event
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq') }}">
                        Faq's
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('disclaimer') ? 'active' : '' }}"
                        href="{{ route('disclaimer') }}">
                        Disclaimer
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}"
                        href="{{ route('contact.index') }}">
                        Contact Us
                    </a>
                </li>

            </ul>
        </div>

        {{-- Map Icon --}}
        <a class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}" href="{{ route('map') }}">
            <i class="fa-solid fa-location-dot location-icon"></i>
        </a>

        {{-- ACTIVE USER (IMPORTANT FIX) --}}
        @php
            $user = Auth::guard('organization')->user() ?? Auth::user();
        @endphp

        <div class="d-flex align-items-center gap-2 ms-lg-0 ms-auto">

            {{-- LOGGED IN --}}
            @if($user)
                <ul class="list-unstyled user-profile mb-0 ms-lg-2">
                    <li class="nav-item dropdown">

                        <a href="javascript:void(0);" class="nav-link d-flex align-items-center gap-2"
                            data-bs-toggle="dropdown">

                            <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($user->organization_name ?? $user->name) . '&size=100&rounded=true&bold=true&color=25AEA1&background=f8f9fa' }}"
                                class="rounded-circle" style="width:50px;height:50px;object-fit:cover;">
                        </a>

                        <div class="dropdown-menu dropdown-menu-end p-0">

                            <div class="card m-0">

                                <div class="card-body p-0">

                                    {{-- User Info --}}
                                    <div class="d-flex align-items-center p-3 border-bottom">

                                        <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($user->organization_name ?? $user->name) . '&size=100&rounded=true&bold=true&color=25AEA1&background=f8f9fa' }}"
                                            class="rounded-circle" style="width:50px;height:50px;object-fit:cover;">

                                        <div class="ms-2">
                                            <h6 class="mb-0">
                                                {{ $user->organization_name ?? $user->name }}
                                            </h6>

                                            <small class="text-muted">
                                                {{ $user->email }}
                                            </small>
                                        </div>

                                    </div>

                                    {{-- Create Event (only organization) --}}
                                    @if(Auth::guard('organization')->check())
                                        <div class="p-3 border-bottom">
                                            <a href="{{ route('events.index') }}" class="btn btn-primary w-100">
                                                Create Event
                                                <i class="fa-solid fa-plus ms-1"></i>
                                            </a>
                                        </div>
                                    @endif

                                    {{-- Logout --}}
                                    <div class="p-3">
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

                {{-- GUEST --}}
            @else
                <a href="javascript:void(0);" class="btn btn-primary header-btn" data-bs-toggle="modal"
                    data-bs-target="#loginBackdrop">
                    Sign In
                </a>
            @endif

            {{-- Mobile toggle --}}
            <button class="navbar-toggler border-0 shadow-none" type="button" id="categoryButton">
                <span></span><span></span><span></span>
            </button>

        </div>
    </div>
</nav>