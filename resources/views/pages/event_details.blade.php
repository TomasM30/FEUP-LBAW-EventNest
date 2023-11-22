@extends('layouts.app')

@section('content')
<div class="content-container" id="details-content-container">
    <div class="actions">
        @if(!$isParticipant && !$isAdmin)
            <form method="POST" action="{{ route('event.join', $event->id) }}">
                {{ csrf_field() }}
                <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                <input type="hidden" name="eventId" value="{{ $event->id }}">
                <button type="submit" class="btn btn-custom btn-block">Join</button>
            </form>
        @elseif($isParticipant && !$isAdmin && !$isOrganizer)
            <form method="POST" action="{{ route('event.leave', $event->id) }}">
                {{ csrf_field() }}
                <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                <input type="hidden" name="eventId" value="{{ $event->id }}">
                <button type="submit" class="btn btn-custom btn-block">Leave</button>
            </form>
        @endif
        <form method="POST" action="{{ route('events.invite', $event->id) }}">
            {{ csrf_field() }}
            <input type="hidden" name="sender_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="eventId" value="{{ $event->id }}">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Invite</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <input type="text" class="form-control" name="receiver_username" placeholder="Username" aria-label="Username" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Send</button>
                        </div>
                    </div>
            </div>
        </form>
        @if($isAdmin || $isOrganizer)
            @php
                $participants = $event->eventparticipants()->pluck('id_user')->toArray();
                $nonParticipants = App\Models\AuthenticatedUser::whereNotIn('id_user', $participants)->get();
            @endphp
            @if($event->eventparticipants()->count() > 1)
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Remove
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        @foreach ($event->eventparticipants()->get() as $attendee)
                            @if($attendee->user->id != $event->user->id) 
                                <form method="POST" action="{{ route('events.remove', $event->id) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_user" value="{{ $attendee->user->id }}">
                                    <input type="hidden" name="eventId" value="{{ $event->id }}">
                                    <button type="submit" class="dropdown-item">{{ $attendee->user->name }}</button>
                                </form>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
            @if(count($nonParticipants) > 0)
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Add
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        @foreach ($nonParticipants as $authUser)
                            @if($authUser->user->id != $event->user->id) 
                                <form method="POST" action="{{ route('events.add', $event->id) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_user" value="{{ $authUser->user->id }}">
                                    <input type="hidden" name="eventId" value="{{ $event->id }}">
                                    <button type="submit" class="dropdown-item">{{ $authUser->user->name }}</button>
                                </form>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
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
                @foreach ($event->eventparticipants()->get() as $attendee)
                    <p>{{ $attendee->user->name }}</p>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
