@extends('layouts.master')
@section('title','MeetUp | Contact-Us')
@section('no-sidebar', true)
@section('no-event-blog',true)
@section('content')
    <!-- Start Contact-Us Section --> 
    <section class="contact-us-section section-s1padding">
        <div class="container max-w-1000px">
            <div class="row">
                <div class="col-12">
                    <div class="section-title-s1 mb-md-5 mb-4">
                        <div>
                            <span class="badge-s1">Events</span>
                        </div>
                        <h2>Contact Us</h2>
                        <p>Everything you need to know about events and communities</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="breadcrumb-section mb-md-5 mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-arrow d-flex justify-content-center mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row row-gap-3">
                <div class="col-12">
                    <div class="card card-s2">
                        <div class="card-body p-md-4 p-3">
                            <div class="row">
                                <div class="col-lg-6 mb-lg-0 mb-4">
                                    <form id="contactUsForm">
                                        <div class="row g-3 mt-0">
                                            <div class="col-md-6">
                                                <label for="first_name" class="form-label text-start fw-500 fs-16px">First Name</label>
                                                <div>
                                                    <input type="text" name="first_name" class="form-control input-field-s1 rounded-8" id="first_name" placeholder="Enter name">
                                                </div>
                                                <label id="first_name-error" class="text-danger" for="first_name" style="display: none"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="last_name" class="form-label text-start fw-500 fs-16px">Last Name</label>
                                                <div>
                                                    <input type="text" name="last_name" class="form-control input-field-s1 rounded-8" id="last_name" placeholder="Enter last name">
                                                </div>
                                                <label id="last_name-error" class="text-danger" for="last_name" style="display: none"></label>
                                            </div>
                                            <div class="col-12">
                                                <label for="email" class="form-label text-start fw-500 fs-16px">Email Address</label>
                                                <div>
                                                    <input type="email" name="email" class="form-control input-field-s1 rounded-8" id="email" placeholder="Enter email address">
                                                </div>
                                                <label id="email-error" class="text-danger" for="email" style="display: none"></label>
                                            </div>
                                            <div class="col-12">
                                                <label for="message" class="form-label text-start fw-500 fs-16px">Message</label>
                                                <div>
                                                    <textarea class="form-control input-field-s1 rounded-8" name="message" id="contactMessage" placeholder="Enter Comment Here" style="height: 150px"></textarea>
                                                </div>
                                                <label id="message-error" class="text-danger" for="message" style="display: none"></label>
                                            </div>
                                            <div class="col-12 mt-4">
                                                <div class="text-center">
                                                    <button id="contactUsBtn" type="submit" class="btn btn-primary" id="contactFormBtn"><i class="fa-regular fa-paper-plane me-2"></i>Send Message</button>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-4">
                                                <div class="d-flex gap-2 border p-3 rounded-12">
                                                    <div class=" fs-22px">
                                                        <i class="fa-solid fa-envelope gt-text-theme"></i>
                                                    </div>
                                                   <div>
                                                    <a href="mailto:support@paycoin.ltd" class="fw-600 lh-normal fs-18px">support@paycoin.ltd</a>
                                                    <p class="mb-0 lh-normal text-muted fs-16px">Our response time is typically within 24 to 48 hours.</p>
                                                   </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-6">
                                    <div class="gt-bg-s3 rounded-12 p-3 h-100">
                                        <h3 class="fs-22px gt-text-theme mb-3">Get In Touch With Us</h3>
                                        <p>We’re here to help. To clarify our financial data, analytics content, partner interest, feedback, or any other ideas on how to improve <a href="javascript:void(0);">eventtime.com</a>, please contact us.</p>
                                        <p>You want to report a problem, need to clarify a point about some article, or have an idea for a new topic, our team will read your message and reply to you as soon as possible.</p>
                                        <h4 class="fs-18px fw-600 mb-3">What You Can Contact Us About</h4>
                                        <div>
                                            <ul class="ps-4 mb-0">
                                                <li class="pb-1">The general questions are related to the financial information and the educational material</li>
                                                <li class="pb-1">Suggestions for new tools, articles, or improvements</li>
                                                <li class="pb-1">Reporting incorrect or outdated information</li>
                                                <li class="pb-1">Advertising/collaboration queries</li>
                                                <li class="pb-1">Technical problems with the website</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
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