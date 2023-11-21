@extends('layouts.app')

@section('content')
<h1>{{ $user->name }}'s Events</h1>

@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<div class="content">
    <div>
        <h2>Created Events</h2>
        <ul>
            @foreach ($createdEvents as $event)
                <li>
                    {{ $event->title }}
                    <form method="POST" action="{{ route('events.delete', $event->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit">Delete</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>

    <div>
        <h2>Joined Events</h2>
        <ul>
            @foreach ($joinedEvents as $event)
                <li>{{ $event->title }}</li>
            @endforeach
        </ul>
    </div>

    <div>
        <h2>Favorite Events</h2>
        <ul>
            @foreach ($favoriteEvents as $event)
                <li>{{ $event->title }}</li>
            @endforeach
        </ul>
    </div>
</div>

@endsection