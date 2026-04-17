@extends('layouts.master')
@section('title', 'MeetUp | Event-Detail')
@section('no-sidebar', false)
@section('no-faq-disclaimer', true)
@section('content')
    <div class="main-page-content">
        <!-- Start Event-Card Section -->
        <section class="event-section section-s1padding">
            <div class="container">
                <!-- Section-Title & Breadcrumb -->
                <div class="row">
                    <div class="col-12">
                        <div class="section-title-s1 mb-3">
                            <div>
                                <span class="badge-s1">Events</span>
                            </div>
                            <h2>Events near you</h2>
                            <p>Discover what's happening in your area</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="breadcrumb-section mb-md-5 mb-4">
                            <nav aria-label="breadcrumb breadcrumb-s1">
                                <ol class="breadcrumb breadcrumb-arrow d-flex justify-content-center mb-0">
                                    <li class="breadcrumb-item"><a href="./index.html">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Events</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Events Card -->
                {{-- @dd($events) --}}
            <div class="row">
                @foreach ($events as $event)
                    <div class="col-xxl-3 col-lg-4 col-md-6">
                        <a href="{{ route('event-detail',$event->id) }}">
                            <div class="card event-card-s1">
                                <div class="card-body p-3">
                                    <div class="event-banner rounded-12 mb-3">
                                        <img class="rounded-12" src="{{ $event->image_url }}" alt="Event Banner">
                                    </div>
                                    <div class="event-card-content gt-bg-s2 rounded-12 p-3">
                                        <div class="d-flex flex-wrap justify-content-between gap-2 border-bottom pb-2 mb-2">
                                            <span class="d-inline-flex d-flex align-items-center fw-500 fs-14px text-muted"><i
                                                    class="fa-regular fa-calendar"></i> 19/04/2027</span>
                                            <span class="d-inline-flex d-flex align-items-center fw-500 fs-14px text-muted"><i
                                                    class="fa-regular fa-clock"></i> 12:02 AM</span>
                                        </div>
                                        <h3 class="gt-text-title change-fs-18px-16px mb-0">{{ $event->title }}</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    
                    </div>
                    @endforeach
                </div>
                
        </section>
        <!-- End Event-Card Section -->

        <!-- Start CTA Section -->
        <section class="cta-section section-s1padding pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="gt-bg-s2 p-md-5 p-4 rounded-12">
                            <div class="section-title-s1 mb-md-5 mb-4">
                                <div>
                                    <span class="badge-s2">Get Started</span>
                                </div>
                                <h2 class="cta-title">Discover events happening near you</h2>
                                <p>From tech meetups to outdoor adventures, find events, join communities, and start
                                    building real connections today.</p>
                            </div>
                            <div class="text-center">
                                <a href="javascript:void(0);" class="btn btn-primary w-sm-auto">Browse Events <i
                                        class="fa-solid fa-arrow-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
    @section('js')
        <script>
            // Scrolling-Animation
            AOS.init();
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

            // Apply start animation to ALL buttons with class "animation-btn"
            document.querySelectorAll('.animation-btn').forEach(button => {

                button.addEventListener('click', function (e) {
                    const circle = document.createElement("span");
                    const diameter = Math.max(this.clientWidth, this.clientHeight);
                    const radius = diameter / 2;

                    circle.style.width = circle.style.height = `${diameter}px`;
                    circle.style.left = `${e.clientX - this.getBoundingClientRect().left - radius}px`;
                    circle.style.top = `${e.clientY - this.getBoundingClientRect().top - radius}px`;
                    circle.classList.add("start");

                    const start = this.getElementsByClassName("start")[0];
                    if (start) {
                        start.remove();
                    }
                    this.appendChild(circle);
                });

            });
        </script>
    @endsection