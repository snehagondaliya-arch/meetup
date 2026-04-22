@extends('layouts.master')
@section('title','MeetUp | About')
@section('no-sidebar', true)
@section('content')
    <!-- Start Contact-Us Section --> 
    <section class="disclaimer-section section-s1padding">
        <div class="container max-w-1000px">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-s1 mb-md-5 mb-4">
                        <div>
                            <span class="badge-s1">Events</span>
                        </div>
                        <h2>About Us</h2>
                        <p>Everything you need to know about events and communities</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="breadcrumb-section mb-md-5 mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-arrow d-flex justify-content-center mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">About Us</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row row-gap-3">
                <div class="col-12">
                    <div class="card card-s2">
                        <div class="card-body p-md-4 p-3">
                            <p><b>Last updated:</b> April 10, 2026</p>
                            <p><b>eventtime.com</b> is a modern platform designed to help people discover events, connect with communities, and turn shared interests into meaningful experiences.</p>
                        
                            <p>Whether you're passionate about technology, fitness, travel, business, or creative hobbies — EventTime brings people together through real-world and virtual events happening every day.</p>
                        
                            <h3 class="fs-22px gt-text-theme">Our Mission</h3>
                            <p>Our mission is simple — to make it easy for anyone to find and join events they love. We aim to build a space where people can connect, learn, and grow together through shared interests and experiences.</p>
                        
                            <h3 class="fs-22px gt-text-theme">What We Offer</h3>
                            <ul class="ps-4">
                                <li class="pb-1">Discover local and online events across multiple categories</li>
                                <li class="pb-1">Connect with like-minded people and communities</li>
                                <li class="pb-1">Explore trending topics, meetups, and workshops</li>
                                <li class="pb-1">Stay updated with the latest events and activities</li>
                            </ul>
                        
                            <h3 class="fs-22px gt-text-theme">Why Choose EventTime</h3>
                            <p>We focus on simplicity, accessibility, and community. Our platform is built to help users easily find events, engage with others, and create memorable experiences without complexity.</p>
                        
                            <ul class="ps-4">
                                <li class="pb-1">User-friendly and easy-to-navigate interface</li>
                                <li class="pb-1">Wide range of event categories</li>
                                <li class="pb-1">Accessible on mobile, tablet, and desktop</li>
                                <li class="pb-1">Built for real connections, not just browsing</li>
                            </ul>
                        
                            <h3 class="fs-22px gt-text-theme">Our Vision</h3>
                            <p>We believe that shared experiences bring people closer. Our vision is to create a global platform where anyone can find their community, explore new interests, and build real-world connections.</p>
                        
                            <h3 class="fs-22px gt-text-theme">Get Involved</h3>
                            <p>Start exploring events, join communities, and be part of something meaningful. Whether you're attending or organizing, EventTime is here to help you make the most of every moment.</p>
                        
                            <h3 class="fs-22px gt-text-theme">Contact Us</h3>
                            <p>If you have any questions or suggestions, feel free to reach out:</p>
                        
                            <a href="#" class="fw-600 lh-normal fs-18px d-inline-flex mb-3">
                                <i class="fa-solid fa-envelope me-2 my-auto fs-22px gt-text-theme"></i>
                                support@eventtime.com
                            </a>
                            <p><b>eventtime.com</b> — Bringing people together through events and shared experiences.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Contact-Us Section --> 
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

            button.addEventListener('click', function(e) {
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