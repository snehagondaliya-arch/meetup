@foreach ($events_map as $event)
    <div class="event-item">
        <img src="{{ $event->image_url }}">
        <div class="event-info">
            <div class="title">{{ $event->title }}</div>
            <div class="meta">{{ $event->formatted_date }}</div>
        </div>
    </div>
@endforeach