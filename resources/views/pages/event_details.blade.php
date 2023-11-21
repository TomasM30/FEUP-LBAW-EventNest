@extends('layouts.app')

@section('content')
<div class="content-container" id="details-content-container">
    <div class="actions">
        <form method="POST" action="{{ route('event.join', $event->id) }}">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-primary">Edit</button>
        </form>
    </div>
    <div class="info">
        <div class="event-top">
            <div id="event-bg">
                <img src="https://mdbootstrap.com/img/new/standard/nature/111.webp"/>
            </div>
            <div class="event-info">
                <h2>{{ $event->title }}</h2>
                <h4>Hosted by:</h4>
                <p>{{ $event->user->name }}</p>

                <h4>Location:</h4>
                <p>{{ $event->place }}</p>

                <h4>Date:</h4>
                <p>{{ $event->date }}</p>

                <h4>Description:</h4>
                <p>{{ $event->description }}</p>
            </div>
        </div>
        <div class="attendees">
            <div class="capacity">
                <div class="capacity-item">
                    <h4>Joined</h4>
                    <p>{{ $event->eventparticipants()->count() }}</p>   
                </div>
                <div class="capacity-item">
                    <h4>Limit</h4>
                    <p>{{ $event->capacity }}</p>
                </div>
            </div>
            <div class="attendees-list">
                <h3>Attendees:</h3>
                @foreach ($attendees as $attendee)
                    <p>{{ $attendee->user->name }}</p>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection