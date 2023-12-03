<div class="col-12 col-sm-6 col-lg-4 mb-4">
    <div class="card text-white bg-primary mb-3 mx-auto" style="max-width:30rem; max-height:40rem;">
        <img src="https://mdbootstrap.com/img/new/standard/nature/111.webp" class="img-fluid" />

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
</div>