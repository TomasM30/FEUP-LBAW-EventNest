@extends('layouts.app')

@section('content')
<h1 class="usertitle">{{ $user->username }}'s Events</h1>

<div class="myevents">
    <div id="created">
        <h2>Created Events</h2>
        @foreach ($createdEvents as $event)
            @include('partials.event', ['event' => $event])
        @endforeach
    </div>
    <div id="joined">
        <h2>Joined Events</h2>
        @foreach ($joinedEvents as $event)
            @include('partials.event', ['event' => $event])
        @endforeach
    </div>
    <div id="favorite">
        <h2>Favorite Events</h2>
        @foreach ($favoriteEvents as $event)
            @include('partials.event', ['event' => $event])
        @endforeach
    </div>
    <div id ="attended">
        <h2>Attended Events</h2>
        @foreach ($attendedEvents as $event)
            @include('partials.event', ['event' => $event])
        @endforeach
    </div>
</div>

@endsection