@forelse ($events as $event)
    <div class="col-xxl-3 col-lg-4 col-md-6">
        <a href="{{ route('event-detail', $event->slug) }}">
            <div class="card event-card-s1">
                <div class="card-body p-3">
                    <div class="event-banner rounded-12 mb-3">
                        <img class="rounded-12" src="{{ $event->image_url }}" onerror="this.onerror=null;this.src='{{ asset(PLACEHOLDER_IMAGE) }}';" alt="Event Banner">
                    </div>
                    <div class="event-card-content gt-bg-s2 rounded-12 p-3">
                        <div class="d-flex flex-wrap justify-content-between gap-2 border-bottom pb-2 mb-2">
                            <span class="d-inline-flex d-flex align-items-center fw-500 fs-14px text-muted"><i
                                    class="fa-regular fa-calendar"></i>  {{ $event->formatted_date }}</span>
                            <span class="d-inline-flex d-flex align-items-center fw-500 fs-14px text-muted"><i
                                    class="fa-regular fa-clock"></i>
                                {{ $event->formatted_time }}</span>
                        </div>
                        <h3 class="gt-text-title change-fs-18px-16px mb-0">{{ $event->title }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

@empty
    <div class="col-12">
        <div class="text-center py-5">
            <h4>No Data Found</h4>
        </div>
    </div>
@endforelse
