@foreach ($events as $event)
    <div class="col-xxl-3 col-lg-4 col-sm-6">
            <div class="card event-card-s2" data-aos="zoom-in" data-aos-duration="800">
                  <a href="{{ route('event-detail', $event->id) }}"></a>
                <div class="event-banner card-header bg-transparent border-0 p-3 position-relative rounded-12">
                    <img class="rounded-12" src="{{ $event->image_url }}" onerror="this.onerror=null;this.src='{{ asset(PLACEHOLDER_IMAGE) }}';" alt="Event Banner">
                    <div class="info-badge d-flex align-items-center fw-500 fs-14px text-muted line-clamp-1 py-1 px-2">
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
                                <span class="d-inline-flex d-flex align-items-center fw-500 fs-14px text-muted"><i
                                        class="fa-regular fa-calendar gt-text-title me-1"></i>
                                    {{$event->start_date}}</span>
                                <span class="d-inline-flex d-flex align-items-center fw-500 fs-14px text-muted"><i
                                        class="fa-regular fa-clock gt-text-title me-1"></i>
                                    {{$event->formatted_start_time}}</span>
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
      
    </div>
@endforeach