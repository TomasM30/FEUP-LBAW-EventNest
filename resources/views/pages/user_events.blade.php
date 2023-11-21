@extends('layouts.app')

@section('content')
<h1>{{ $user->name }}'s Events</h1>

<div id="content">
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
</div>

@endsection