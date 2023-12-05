<div class="col-12 col-sm-6 col-lg-4 mb-4">
    <a href="{{ route('events.details', ['id' => $event->id]) }}" style="text-decoration: none; color: inherit;">
        <div class="card text-white bg-primary mb-3 mx-auto" style="max-width:30rem; max-height:40rem;">
            <img src="{{ $event->getProfileImage() }}" class="img-fluid" style="height: 25rem; object-fit: cover;"/>

            <div class="card-body">
                <h4 class="card-title" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{ $event->title }}</h4>
            </div>
            <div class=" card-body">
                <p class=" card-text">{{ $event->place }}</p>
                <p class="card-text">{{ \Carbon\Carbon::parse($event->date)->format('d/m/y') }}</p>
            </div>
            <div class="card-header">
                @if($event->hashtags->isNotEmpty())
                @foreach($event->hashtags as $hashtag)
                <span class="hashtag">#{{ $hashtag->title }}</span>
                @endforeach
                @else
                &nbsp;
                @endif
            </div>
            <div class="card-footer">
                capacity: {{ $event->eventparticipants()->count() ."/". $event->capacity }}
            </div>
        </div>
    </a>
</div>