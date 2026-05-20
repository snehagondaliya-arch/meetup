@foreach ($events_map as $event)
    <div class="event-item mt-1">
        <img src="{{ $event->image_url }}" alt="{{ $event->title }}">

        <div class="event-info">

            @if($event->category->name !== 'All Events')
                <div class="event-category">
                    {{ $event->category->name }}
                </div>
            @endif

            <div class="title">
                {{ $event->title }}
            </div>

            <div class="meta">
                {{ $event->formatted_date }}
            </div>

        </div>
    </div>
@endforeach