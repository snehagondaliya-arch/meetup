<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'MeetUp'))</title>

    <meta name="title" content="@yield('meta_title')" />
    <meta name="description" content="@yield('meta_description')" />

    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('uploads/favicon.png') }}">

    @include('web.layouts.header-links')
    @yield('site_schema')
</head>

<body>
    <main class="d-flex flex-column min-h-100vh ">
        @include('web.layouts.header')
        @if (!trim($__env->yieldContent('no-sidebar')))
            @include('web.layouts.sidebar')
        @endif
        <div>
            @yield('content')
                @include('web.layouts.footer')
                <!-- Start Tm-header-Section -->
                <section class="tm-header-section d-lg-none d-block">
                    <div class="tm-header-content h-100 d-flex justify-content-center gap-md-4 gap-2 align-items-center">
                        <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index') }}">
                            <i class="fa-solid fa-house fs-24px"></i>
                            <span class="fs-10px lh-normal">Home</span>
                        </a>

                        <a class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq') }}">
                            <i class="fa-solid fa-circle-question fs-24px"></i>
                            <span class="fs-10px lh-normal">FAQ's</span>
                        </a>

                        <a class="nav-link {{ request()->routeIs('disclaimer') ? 'active' : '' }}" href="{{ route('disclaimer') }}">
                            <i class="fa-solid fa-triangle-exclamation fs-24px"></i>
                            <span class="fs-10px lh-normal">Disclaimer</span>
                        </a>

                        <a class="nav-link {{ request()->routeIs('contact.index') ? 'active' : '' }}" href="{{ route('contact.index') }}">
                            <i class="fa-solid fa-envelope fs-24px"></i>
                            <span class="fs-10px lh-normal">Contact</span>
                        </a>
                    </div>
                </section>
                <!-- End Tm-header-Section -->
        </div>
        @include('web.partials.login')
        @include('web.layouts.footer-links')
        @yield('js')
    </main>
</body>
</html>
