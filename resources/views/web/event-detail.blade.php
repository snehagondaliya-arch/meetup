@extends('layouts.master')
@section('title', 'MeetUp | Event-Detail')
@section('no-sidebar', false)
@section('no-faq-disclaimer',true)

@section('content')
    <div class="main-page-content">
                <!-- Start Event-detail-card Section --> 
                <section class="event-section section-s1padding">
                    <div class="container">
                        <!-- Section-Title & Breadcrumb -->
                        <div class="row">
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
                       <div class="row row-gap-3">
                        <div class="col-12">
                            <div class="card card-s2 event-detail-card">
                                <div class="card-body p-md-4 p-3">
                                    <div class="row row-gap-3">
                                        <div class="col-xxl-4 col-xl-6">
                                            <div class="event-detail-banner gt-bg-s4 rounder-12 d-flex align-items-center h-100">
                                                <img src="{{ $event->image_url }}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-xxl-8 col-xl-6">
                                            <div class="event-detail-content">
                                                <div class="section-title-s1 mb-2 mx-0">
                                                    <h2 class="event-title text-start">{{ $event->title }}</h2>
                                                </div>
                                                <div class="rounded-12 gt-bg-s3 p-3 d-flex gap-2 mb-3">
                                                    <div class="hw-50px d-flex justify-content-center align-items-center bg-white border rounded-12">
                                                        <!-- <img src="#" alt="Hosted By"> -->
                                                        <i class="fa-solid fa-user-tie fs-22px gt-text-theme"></i>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h3 class="fs-18px gt-text-title mb-1">Hosted By <span class="text-muted">{{ $event->host_name }}</span></h3>
                                                        <h3 class="fs-18px gt-text-title mb-0">{{ $event->host_name}}<span class="text-muted"> Super Organizers</span></h3>
                                                    </div>
                                                </div>
                                                <div class="rounded-12 gt-bg-s2 p-3">
                                                    <div class="d-flex align-items-center gap-2 border-bottom mb-3 pb-3">
                                                        <div class="fs-22px">
                                                            <i class="fa-regular fa-calendar gt-text-theme"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="fs-18px gt-text-title mb-0">{{ $event->datetime_text }}</h3>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="fs-22px">
                                                            <i class="fa-solid fa-video gt-text-theme"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="fs-18px gt-text-title mb-0"> {{ $event->is_online == 1 ? 'Online event' : '' }}</h3>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="event-detail-content-s2 pt-3">
                                                <div class="row row-gap-4">
                                                    <!-- Detail -->
                                                    <div class="col-12">
                                                        <div class="row row-gap-3">
                                                            <div class="col-12">
                                                                <div class="section-title-s2 text-start mx-0">
                                                                    <h2 class="mb-0">Details</h2>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                               {!! html_entity_decode($event->description) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Attendees -->
                                                    <div class="col-12">
                                                        <div class="row row-gap-3">
                                                            <div class="col-12">
                                                                <div class="section-title-s2 text-start mx-0">
                                                                    <h2 class="mb-0">Attendees</h2>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="row row-gap-3">
                                                                    <div class="col-xxl-2 col-xl-3 col-md-3 col-sm-4 col-6">
                                                                        <a href="javascript:void(0);">
                                                                            <div class="card gt-bg-s3 border-0">
                                                                                <div class="card-body d-flex flex-column align-items-center p-md-4 p-3">
                                                                                    <div class="hw-50px rounded-12 gt-bg-s3 mb-2">
                                                                                        <img width="100%" height="100%" src="" alt="Hosted By">
                                                                                    </div>
                                                                                    <div class="text-center">
                                                                                        <h3 class="fs-16px fw-600 gt-text-title mb-1">Aarav Mehta</h3>
                                                                                        <p class="fs-14px text-muted mb-0">Organizer</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                        
                                                                        <div class="col-xxl-2 col-xl-3 col-md-3 col-sm-4 col-6">
                                                                            <a href="javascript:void(0);">
                                                                                <div class="card gt-bg-s3 border-0">
                                                                                    <div class="card-body d-flex flex-column align-items-center p-md-4 p-3">
                                                                                        <div class="hw-50px rounded-12 gt-bg-s3 mb-2">
                                                                                            <img width="100%" height="100%" src="" alt="Hosted By">
                                                                                        </div>
                                                                                        <div class="text-center">
                                                                                            <h3 class="fs-16px fw-600 gt-text-title mb-1">Priya Shah</h3>
                                                                                            <p class="fs-14px text-muted mb-0">Co-Organizer</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        
                                                                    <div class="col-xxl-2 col-xl-3 col-md-3 col-sm-4 col-6">
                                                                        <a href="javascript:void(0);">
                                                                            <div class="card gt-bg-s3 border-0">
                                                                                <div class="card-body d-flex flex-column align-items-center p-md-4 p-3">
                                                                                    <div class="hw-50px rounded-12 gt-bg-s3 mb-2">
                                                                                        <img width="100%" height="100%" src="{{ $event->host_image}}" alt="Hosted By">
                                                                                    </div>
                                                                                    <div class="text-center">
                                                                                        <h3 class="fs-16px fw-600 gt-text-title mb-1">{{ $event->host_name }}</h3>
                                                                                        <p class="fs-14px text-muted mb-0">Host</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Google-Map -->
                                                    <div class="col-12">
                                                        <div class="row row-gap-3">
                                                            <div class="col-12">
                                                                <div class="section-title-s2 text-start mx-0">
                                                                    <h2 class="mb-0">Location</h2>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="p-3 gt-bg-s2 rounded-12">
                                                                    <div class="google-map-content rounded-12 mb-3">
                                                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3718.958043694313!2d72.86186125040913!3d21.23351227661883!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be04f2652963ac9%3A0x7d9787a5b5c4275d!2sSilver%20Business%20Point!5e0!3m2!1sen!2sin!4v1775794440758!5m2!1sen!2sin" width="100%" height="380" class="rounded-12" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                                    </div>
                                                       
                                                                    <div class="d-flex gap-2 align-items-xl-center">
                                                                        <div class="fs-22px">
                                                                            <i class="fa-solid fa-location-dot gt-text-theme"></i>
                                                                        </div>
                                                                       <div>
                                                                            <h3 class="fs-18px gt-text-title mb-1">{{ $event->datetime_text }}</h3>
                                                                            <p class="mb-0 text-muted">{{ $event->venue_name }}, {{ $event->full_address }}</p>
                                                                       </div>
                                                                    </div>  
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Photo-Gallery -->
                                                  @if($event->event_photos->isNotEmpty())
                                                        <div class="col-12">
                                                            <div class="row row-gap-3">
                                                                <div class="col-12">
                                                                    <div class="section-title-s2 text-start mx-0">
                                                                        <h2 class="mb-0">Photos</h2>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">    
                                                                    <div class="photos-content position-relative">
                                                                        <div class="swiper photoGallerySlider">
                                                                            <div class="swiper-wrapper w-auto">
                                                                                <div class="swiper-slide">
                                                                                    <div class="photo-card-s1" >
                                                                                    
                                                                                            @foreach ($event->event_photos as $photo)
                                                                                                <img src="{{$photo->event_photos}}" alt="Photo-Gallery-Image" data-fancybox="gallery">
                                                                                            @endforeach
                                                                                    
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Navigation Buttons -->
                                                                        <div class="photo-gallery-btn photo-gallery-prev">
                                                                            <i class="fa-solid fa-chevron-left"></i>
                                                                        </div>
                                                                        <div class="photo-gallery-btn photo-gallery-next">
                                                                            <i class="fa-solid fa-chevron-right"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
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
                <!-- End Event-detail-card Section --> 
                {{-- @dump($events) --}}
                <!-- Start Event-Card Section --> 
                <section class="event-section section-s1padding pt-0">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="section-title-s2  mb-4 mx-0">
                                    <h2>Related Events</h2>
                                    <p>Discover what's happening in your area</p>
                                </div>
                            </div>
                        </div>
                        <div class="row row-gap-3">
                            @foreach ($events as $eventdata)
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <a href="javascript:void(0);">
                                    <div class="card event-card-s2" data-aos="zoom-in" data-aos-duration="800">
                                        <div class="event-banner card-header bg-transparent border-0 p-3 position-relative rounded-12">
                                            <img class="rounded-1" src="{{ $eventdata->image_url }}" alt="Event Banner">
                                            <div class="info-badge d-flex align-items-center fw-500 fs-14px text-muted line-clamp-1 py-1 px-2">Expired</div> 
                                        </div>
                                        <div class="card-body p-3 pt-0">
                                            <div class="d-flex flex-column justify-content-between gap-3 h-100">
                                                <div>
                                                    <h3 class="gt-text-title change-fs-18px-16px mb-2">{{ $eventdata->title }}</h3> 
                                                    <p class="fs-14px text-muted mb-0 line-clamp-2"> {!! strip_tags(html_entity_decode($eventdata->description)) !!}</p>
                                                </div>
                                                <div>
                                                    <div class="d-flex justify-content-between gap-2 border-top pt-3 mb-2">
                                                        <span class="d-inline-flex d-flex align-items-center fw-500 fs-14px text-muted"><i class="fa-regular fa-calendar gt-text-title me-1"></i> 19/04/2027</span>
                                                        <span class="d-inline-flex d-flex align-items-center fw-500 fs-14px text-muted"><i class="fa-regular fa-clock gt-text-title me-1"></i> 12:02 AM</span>
                                                    </div>
                                                    <div class="text-center">
                                                        <a href="{{ route('event-detail', $eventdata->id) }}" class="btn btn-outline-primary w-100 py-2">Check Now <i class="fa-solid fa-arrow-right-long ms-2"></i></a>
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
@endsection
@section('js')
<script>
     var swiper = new Swiper(".photoGallerySlider", {
            speed: 400,
            slidesPerView: "auto",
            spaceBetween: 20,
            freeMode: true,
            watchOverflow: true,

            navigation: {
                nextEl: ".photo-gallery-next",
                prevEl: ".photo-gallery-prev",
            }
        });

        // Fancybox Gallery
        Fancybox.bind('[data-fancybox]', {
            Slideshow: true,
            Toolbar: {
                display: {
                    left: ["zoomIn", "zoomOut"],
                    middle: [],
                    right: ["close"]
                }
            }
        });

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


        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 320) {
                $('.header-s1').addClass('scrolled');
            } else {
                $('.header-s1').removeClass('scrolled');
            }
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