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
                                <input type="text" class="form-control input-field-s1 canvase-search" id="search"
                                    name="search" placeholder="Search events, categories, or locations...">

                                <input type="hidden" name="category" value="{{ request('category') }}">
                                <span class="gt-text-theme rounded-pill"><i
                                        class="fa-solid fa-magnifying-glass  fs-18px"></i></span>
                            </div>
                            <div class="event-filter-content select-content">
                                <select class="form-control event-select-s1" name="date_filter">
                                    <option value="">Any Day</option>
                                    <option value="starting_soon">Starting soon</option>
                                    <option value="today">Today</option>
                                    <option value="tomorrow">Tomorrow</option>
                                    <option value="this_week">This week</option>
                                    <option value="this_weekend">This weekend</option>
                                    <option value="next_week">Next week</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                            <div class="event-filter-content select-content">
                                <select class="form-control event-select-s1" name="event_type">
                                    <option value="">Any type</option>
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                </select>
                            </div>

                            <div class="event-filter-content select-content">
                                <select class="form-control event-select-s1" name="distance">
                                    <option value="30">Within 30 kilometers</option>
                                    <option value="5">5 kilometers</option>
                                    <option value="10">10 kilometers</option>
                                    <option value="25">25 kilometers</option>
                                    <option value="50">50 kilometers</option>
                                    <option value="100">100 kilometers</option>
                                    <option value="150">150 kilometers</option>
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
                <div class="row row-gap-3" id="event-container">
                    @include('web.partials.events')
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
    @if(session('error'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#f8f9fa',
                color: '#333',
                customClass: {
                    popup: 'rounded shadow'
                }
             });
        </script>
    @endif
        <script>
            // Scrolling-Animation
            if (typeof AOS !== 'undefined') {
                AOS.init();
            }

            // Event-Filter-Select-2
            $(document).ready(function () {

                let category = null;
                let search = '';

                $('.event-select-s1').select2({
                    dropdownCssClass: "event-select-s1Dropdown",
                });

                // SEARCH
                $(document).on('input', '#search', function () {
                    search = $(this).val();
                    loadData();
                });

                // CATEGORY CLICK
                $(document).on('click', '.side-menu-nav .nav-link', function (e) {
                    e.preventDefault();

                    category = $(this).data('slug');

                    $('.side-menu-nav .nav-link').removeClass('active');
                    $(this).addClass('active');

                    loadData();
                });
                $('select').on('change', function () {
                    loadData();
                });
                navigator.geolocation.getCurrentPosition(function (position) {
                    loadData(position.coords.latitude, position.coords.longitude);
                });

                function loadData(latitude = null, longitude = null) {

                    const date_filter = $('select[name="date_filter"]').val();
                    const event_type = $('select[name="event_type"]').val();
                    const distance = $('select[name="distance"]').val();

                    $.ajax({
                        url: "{{ route('index') }}",
                        type: "GET",
                        data: {
                            category: typeof category !== 'undefined' ? category : '',
                            search: typeof search !== 'undefined' ? search : '',
                            latitude: latitude,
                            longitude: longitude,
                            date_filter: date_filter,
                            event_type: event_type,
                            // distance: distance
                        },
                        success: function (html) {
                            document.getElementById('event-container').innerHTML = html;

                            if (typeof AOS !== 'undefined') {
                                AOS.refreshHard();
                            }
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
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