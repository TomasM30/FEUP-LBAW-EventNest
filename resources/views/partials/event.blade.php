<div class="box">
    <a href="{{ route('events.details', ['id' => $event->id]) }}">
        <div class="card shadow-0">
            <h5 class="card-title tag">dsad</h5>
            <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                <img src="https://mdbootstrap.com/img/new/standard/nature/111.webp" class="img-fluid" />
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $event->title }}</h5>
                <span class="card-title">{{ $event->date }}</span>
                <br>
                <span class="card-title">{{ $event->place }}</span>
            </div>
            <div class="card-footer">capacity: {{ $event->eventparticipants()->count() ."/". $event->capacity }}</div>
            <a href="{{ route('events.details', ['id' => $event->id]) }}">
        </div>
    </a>
</div>