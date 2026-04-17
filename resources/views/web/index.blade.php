@extends('layouts.master')
@section('title', 'MeetUp | Index')
@section('no-sidebar', false)
@section('no-event-blog', true)

@section('content')
    <!-- Main-Content -->
    <div class="main-page-content">
        <!-- Start Search Section -->
        <section class="search-section-s1 section-s1padding  position-relative">
            <div class="container">
                <div class="row row-gap-3">
                    <div class="col-12">
                        <div
                            class="search-content d-flex flex-column justify-content-center align-items-center text-center max-w-800px mx-auto">
                            <div class="search-badge-icon mb-1 d-flex align-items-center gt-text-ffffff gap-2"
                                data-aos="fade-down" data-aos-delay="0" data-aos-duration="900">= <i
                                    class="fa-solid fa-people-line"></i> =</div>
                            <h1 class="search-title-s1 gt-text-title fw-600 mb-3" data-aos="fade-down" data-aos-delay="0"
                                data-aos-duration="800">The <span class="gt-text-theme">people platform,</span> where
                                interests become friendships.</h1>
                            <p class="text-muted change-fs-20px-18px">Whatever your interest, from hiking and cooking to
                                tech and art — there’s a community waiting for you. Explore events happening every day, meet
                                new people, and build meaningful connections through shared experiences.</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="search-input-content d-flex flex-sm-row flex-column gap-2">
                            <div class="search-input-box position-relative w-100">
                                <input type="text" class="form-control input-field-s1 canvase-search" id="game-search"
                                    placeholder="Search events, categories, or locations...">
                                <span class="gt-text-theme rounded-pill"><i
                                        class="fa-solid fa-magnifying-glass  fs-18px"></i></span>
                            </div>
                            <div class="event-filter-content select-content">
                                <select class="form-control event-select-s1">
                                    <option>Filter</option>
                                    <option>Demo 2</option>
                                    <option>Demo 3</option>
                                    <option>Demo 4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- End Search Section -->
        <!-- Start Event-Card Section -->
        <section class="event-section section-s1padding pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title-s2  mb-4 mx-0">
                            <h2>Trending Event</h2>
                            <p>Discover what's happening in your area</p>
                        </div>
                    </div>
                </div>
                <div class="row row-gap-3">
                    @foreach ($events as $event)
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <a href="{{ route('event-detail', $event->id) }}">
                                <div class="card event-card-s2" data-aos="zoom-in" data-aos-duration="800">
                                    <div
                                        class="event-banner card-header bg-transparent border-0 p-3 position-relative rounded-12">
                                        <img class="rounded-12" src="{{ $event->image_url }}" alt="Event Banner">
                                        <div
                                            class="info-badge d-flex align-items-center fw-500 fs-14px text-muted line-clamp-1 py-1 px-2">
                                            {{ $event->status}}
                                        </div>
                                    </div>
                                    <div class="card-body p-3 pt-0">
                                        <div class="d-flex flex-column justify-content-between gap-3 h-100">
                                            <div>
                                                <!-- <div class="gt-bg-s2 rounded-12 p-2 d-flex justify-content-between gap-2 mb-3">
                                                                                <p class="mb-0 d-flex align-items-center fw-500 fs-14px text-muted line-clamp-1"><i class="fa-regular fa-user me-1"></i>Hosted By: <span class="gt-text-title ms-1">Surat</span></p>
                                                                            </div> -->
                                                <h3 class="gt-text-title change-fs-18px-16px mb-2">{{ $event->title }}</h3>

                                                <p class="fs-14px text-muted mb-0 line-clamp-2">
                                                    {!! strip_tags(html_entity_decode($event->description)) !!}
                                                </p>

                                            </div>
                                            <div>
                                                <div class="d-flex justify-content-between gap-2 border-top pt-3 mb-2">
                                                    <span
                                                        class="d-inline-flex d-flex align-items-center fw-500 fs-14px text-muted"><i
                                                            class="fa-regular fa-calendar gt-text-title me-1"></i>
                                                        {{\Carbon\Carbon::parse($event->start_time)->format('d/m/y')}}</span>
                                                    <span
                                                        class="d-inline-flex d-flex align-items-center fw-500 fs-14px text-muted"><i
                                                            class="fa-regular fa-clock gt-text-title me-1"></i>
                                                        {{\Carbon\Carbon::parse($event->start_time)->format('h:i A')}}</span>
                                                </div>
                                                <div class="text-center">
                                                    <a href="{{ route('event-detail', $event->id) }}"
                                                        class="btn btn-outline-primary w-100 py-2">Check Now <i
                                                            class="fa-solid fa-arrow-right-long ms-2"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
        <!-- End Event-Card Section -->

        <!-- Start Tm-header-Section -->
        <section class="tm-header-section d-lg-none d-block">
            <div class="tm-header-content h-100 d-flex justify-content-center gap-md-4 gap-2 align-items-center">
                <a href="./index.html" class="nav-link active">
                    <i class="fa-solid fa-house fs-24px"></i>
                    <span class="fs-10px lh-normal">Home</span>
                </a>

                <a href="./faq.html" class="nav-link">
                    <i class="fa-solid fa-circle-question fs-24px"></i>
                    <span class="fs-10px lh-normal">FAQ's</span>
                </a>

                <a href="./disclaimer.html" class="nav-link">
                    <i class="fa-solid fa-triangle-exclamation fs-24px"></i>
                    <span class="fs-10px lh-normal">Disclaimer</span>
                </a>

                <a href="./contact-us.html" class="nav-link">
                    <i class="fa-solid fa-envelope fs-24px"></i>
                    <span class="fs-10px lh-normal">Contact</span>
                </a>
            </div>
        </section>
        <!-- End Tm-header-Section -->
@endsection
    @section('js')
        <script>
            // Scrolling-Animation
            if (typeof AOS !== 'undefined') {
                AOS.init();
            }

            // Event-Filter-Select-2
            $(document).ready(function () {
                $('.event-select-s1').select2({
                    dropdownCssClass: "event-select-s1Dropdown",
                });
            });


            // Side Menu
            const menuToggle = document.getElementById('categoryButton');
            const menuClose = document.getElementById('menuClose');
            const sideMenu = document.getElementById('side-menu-section');
            const menuOverlay = document.getElementById('sideMenuOverlay');
            // Open menu
            function openMenu() {
                sideMenu.classList.add('categoryOpen');
                menuOverlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
            // Close menu
            function closeMenu() {
                sideMenu.classList.remove('categoryOpen');
                menuOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
            // Event listeners
            if (menuToggle) menuToggle.addEventListener('click', openMenu);
            if (menuClose) menuClose.addEventListener('click', closeMenu);
            if (menuOverlay) menuOverlay.addEventListener('click', closeMenu);


            $(window).on('scroll', function () {
                if ($(window).scrollTop() > 320) {
                    $('.header-s1').addClass('scrolled');
                } else {
                    $('.header-s1').removeClass('scrolled');
                }
            });
        </script>
    @endsection