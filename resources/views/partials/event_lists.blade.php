@foreach ($events as $event)
    @include('partials.event', ['event' => $event])
@endforeach