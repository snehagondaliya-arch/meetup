@extends('layouts.master')
@section('title',config('app.name'))
@section('no-sidebar', true)
@section('content')
                <!-- Start Event-detail-card Section --> 
                <section class="event-section section-s1padding">
                    <div class="container">
                        <!-- Section-Title & Breadcrumb -->
                        <div class="row">
                            <div class="col-12">
                                <div class="breadcrumb-section mb-md-5 mb-4">
                                    <nav aria-label="breadcrumb breadcrumb-s1">
                                        <ol class="breadcrumb breadcrumb-arrow d-flex justify-content-center mb-0">
                                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
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
                                                <img src="{{ $event->image_url }}" onerror="this.onerror=null;this.src='{{ asset(PLACEHOLDER_IMAGE) }}';" alt="">
                                            </div>
                                        </div>
                                        <div class="col-xxl-8 col-xl-6">
                                            <div class="event-detail-content">
                                                <div class="section-title-s1 mb-2 mx-0">
                                                    <h2 class="event-title text-start">{{ $event->title }}</h2>
                                                </div>
                                                <div class="rounded-12 gt-bg-s3 p-3 d-flex gap-2 mb-3">
                                                    <div class="hw-50px d-flex justify-content-center align-items-center bg-white border rounded-12">
                                                        <i class="fa-solid fa-user-tie fs-22px gt-text-theme"></i>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h3 class="fs-18px gt-text-title mb-1">Hosted By <span class="text-muted">{{ $event->host_name }}</span></h3>
                                                        <h3 class="fs-18px gt-text-title mb-0">{{ $event->host_name}}<span class="text-muted"> Super Organizers</span></h3>
                                                    </div>
                                                </div>
                                                @if($event->formatted_date_time)
                                                <div class="rounded-12 gt-bg-s2 p-3">
                                                    <div class="d-flex align-items-center gap-2 border-bottom mb-3 pb-3">
                                                        <div class="fs-22px">
                                                            <i class="fa-regular fa-calendar gt-text-theme"></i>
                                                        </div>
                                                        <div>
                                                           <h3 class="fs-18px gt-text-title mb-0">
                                                                {{ $event->formatted_date_time }}
                                                            </h3>
                                                        </div>
                                                    </div>
                                                @endif
                                                    @if($event->is_online == 1)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="fs-22px">
                                                            <i class="fa-solid fa-video gt-text-theme"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="fs-18px gt-text-title mb-0">Online event</h3>
                                                        </div>
                                                    </div>
                                                    @endif
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
                                                               {!! $event->description !!}
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
                                                                                        <img width="100%" height="100%" src="{{ $event->host_image}}" onerror="this.onerror=null;this.src='{{ asset(PLACEHOLDER_IMAGE) }}';"alt="Hosted By">
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
                                                                        <iframe src="https://maps.google.com/maps?q={{ $event->latitude ?? 0.000 }},{{ $event->longitude ?? 0.000}}&z=15&output=embed" width="100%" height="380" class="rounded-12" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                                    </div>
                                                       
                                                                    <div class="d-flex gap-2 align-items-xl-center">
                                                                        <div class="fs-22px">
                                                                            <i class="fa-solid fa-location-dot gt-text-theme"></i>
                                                                        </div>
                                                                       <div>
                                                                            <h3 class="fs-18px gt-text-title mb-1">{{ $event->formatted_date_time }}</h3>
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
                                                                                                <img src="{{$photo->event_photos}}" onerror="this.onerror=null;this.src='{{ asset(PLACEHOLDER_IMAGE) }}';"alt="Photo-Gallery-Image" data-fancybox="gallery">
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
                        <div class="row row-gap-3" id="container">
                            @include('web.partials.events')
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

</script>
@endsection