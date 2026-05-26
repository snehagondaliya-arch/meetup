@foreach ($events_map as $event)
    <div class="event-item">
        <img src="{{ $event->image_url }}" alt="{{ $event->title }}">

        <div class="event-info">

            @if($event->category->name !== 'All Events')
                <div class="event-category line-clamp-2">
                    {{ $event->category->name }}
                </div>
            @endif

            <div class="title line-clamp-2">
                {{ $event->title }}
            </div>

            <div class="meta">
                {{ $event->formatted_date }}
            </div>

        </div>
    </div>
@endforeach
