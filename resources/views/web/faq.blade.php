@extends('layouts.master')
@section('title', 'MeetUp | Index')
@section('no-sidebar', true)
@section('no-faq-disclaimer',true)
@section('content')
    <!-- Start FAQ's Section -->
    <section class="faq-section section-s1padding">
        <div class="container max-w-1000px">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-s1 mb-md-5 mb-4">
                        <div>
                            <span class="badge-s1">Events</span>
                        </div>
                        <h2>Frequently Asked Questions</h2>
                        <p>Everything you need to know about events and communities</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="breadcrumb-section mb-md-5 mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-arrow d-flex justify-content-center mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Faq's</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row row-gap-3">
                <div class="col-12">
                    <div class="accordion accordion-s1" id="accordionMeetup">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading1">
                                <button class="accordion-button fw-500" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    1. What kind of events can I find here?
                                    <i class="fas fa-chevron-down ms-auto accordion-icon"></i>
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="heading1"
                                data-bs-parent="#accordionMeetup">
                                <div class="accordion-body pt-0 fs-16px">
                                    You can find a wide range of events including tech meetups, fitness groups, workshops,
                                    social gatherings, outdoor activities, and more.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading2">
                                <button class="accordion-button collapsed fw-500" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                    2. How do I join an event?
                                    <i class="fas fa-chevron-down ms-auto accordion-icon"></i>
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2"
                                data-bs-parent="#accordionMeetup">
                                <div class="accordion-body pt-0 fs-16px">
                                    Simply browse events, select the one you’re interested in, and click the join button to
                                    reserve your spot.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading3">
                                <button class="accordion-button collapsed fw-500" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                    3. Do I need an account to participate?
                                    <i class="fas fa-chevron-down ms-auto accordion-icon"></i>
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
                                data-bs-parent="#accordionMeetup">
                                <div class="accordion-body pt-0 fs-16px">
                                    You can explore events without an account, but signing up allows you to join events,
                                    interact with members, and manage your activity.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading4">
                                <button class="accordion-button collapsed fw-500" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                    4. Can I create and host my own events?
                                    <i class="fas fa-chevron-down ms-auto accordion-icon"></i>
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4"
                                data-bs-parent="#accordionMeetup">
                                <div class="accordion-body pt-0 fs-16px">
                                    Yes, you can easily create events, invite people, and build your own community around
                                    shared interests.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading5">
                                <button class="accordion-button collapsed fw-500" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                    5. Are events free or paid?
                                    <i class="fas fa-chevron-down ms-auto accordion-icon"></i>
                                </button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5"
                                data-bs-parent="#accordionMeetup">
                                <div class="accordion-body pt-0 fs-16px">
                                    Many events are free, while some may require a small fee depending on the organizer and
                                    type of event.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading6">
                                <button class="accordion-button collapsed fw-500" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                    6. Can I use this platform on mobile devices?
                                    <i class="fas fa-chevron-down ms-auto accordion-icon"></i>
                                </button>
                            </h2>
                            <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading6"
                                data-bs-parent="#accordionMeetup">
                                <div class="accordion-body pt-0 fs-16px">
                                    Yes, the platform is fully responsive and works smoothly on mobile, tablet, and desktop
                                    devices.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End FAQ's Section -->
@endsection
@section('js')
    <script>
        // Hero-Slider
        var swiper = new Swiper(".mySwiper", {
            autoplay: true,
            loop: true,
            autoplay: {
                delay: 1400,
            },
        });

        // Event Info CTA Slider
        var swiper = new Swiper(".evCTASlider", {
            loop: true,
            autoplay: {
                delay: 2500,
            },
            speed: 800,
            effect: "slide"
        });

        // Upcoming-Event Slider 
        var reviewSwiper = new Swiper(".upcomingEvent", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 2000,
            },

            pagination: {
                el: ".upcomingEvent .swiper-pagination",
                clickable: true
            },

            navigation: {
                nextEl: ".upcomingEvent .swiper-button-next",
                prevEl: ".upcomingEvent .swiper-button-prev"
            },

            breakpoints: {
                576: {
                    slidesPerView: 2
                },
                992: {
                    slidesPerView: 3
                }
            }
        });

        // Scrolling-Animation
        AOS.init();

        // -Header-side-menu-
        const menuToggle = document.getElementById('menuToggle');
        const menuClose = document.getElementById('menuClose');
        const sideMenu = document.getElementById('sideMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        // Open Menu
        menuToggle.addEventListener('click', () => {
            sideMenu.classList.add('open');
            menuOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
        // Close Menu
        menuClose.addEventListener('click', () => {
            sideMenu.classList.remove('open');
            menuOverlay.classList.remove('show');
            document.body.style.overflow = '';
        });
        // Close on overlay click
        menuOverlay.addEventListener('click', () => {
            sideMenu.classList.remove('open');
            menuOverlay.classList.remove('show');
            document.body.style.overflow = '';
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