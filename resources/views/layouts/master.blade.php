<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

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
        @yield('content')
        @include('web.layouts.footer')
        @include('web.partials.login')
        @include('web.layouts.footer-links')
        @yield('js')
    </main>
</body>
</html>