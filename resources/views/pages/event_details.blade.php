@extends('layouts.app')

@section('content')
<div class="content-container m-3" id="details-content-container">
    @if($event->closed)
    <div class="alert alert-info event-closed-alert">
        This event has already happened
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="actions d-flex justify-content-start">
                @php
                $participants = $event->eventparticipants()->pluck('id_user')->toArray();
                $nonParticipants = App\Models\AuthenticatedUser::whereNotIn('id_user', $participants)->get();
                $invitedUsers = \DB::table('eventnotification')
                ->join('notification', 'eventnotification.id', '=', 'notification.id')
                ->where('inviter_id', Auth::user()->id)
                ->where('id_event', $event->id)
                ->pluck('notification.id_user')
                ->toArray();

                $notInvited = App\Models\AuthenticatedUser::whereNotIn('id_user', $participants)
                ->whereNotIn('id_user', $invitedUsers)
                ->get();
                @endphp

                @if ($event->closed == false)
                @if(!$isParticipant && !$isAdmin && $event->eventparticipants()->count() < $event->capacity)
                    @if($event->type == 'public' || $event->type == 'private')
                    <form method="POST" action="{{ route('event.join', $event->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="eventId" value="{{ $event->id }}">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary m-3">Join</button>
                        </div>
                    </form>
                    @elseif($event->type == 'approval')
                    <form method="POST" action="{{ route('events.notification', $event->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_user" value="{{ $event->id_user }}">
                        <input type="hidden" name="eventId" value="{{ $event->id }}">
                        <input type="hidden" name="action" value="request">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary m-3">Request</button>
                        </div>
                    </form>
                    @endif
                    @elseif($isParticipant && !$isAdmin && !$isOrganizer)
                    <form method="POST" action="{{ route('event.leave', $event->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="eventId" value="{{ $event->id }}">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary m-3 leave">Leave</button>
                        </div>
                    </form>
                    @endif

                    @if(!$isAdmin && $isParticipant)
                    <div class="dropdown">
                        <button class="btn btn-primary m-3 dropdown-toggle invite" type="button" id="dropdownMenuInvite" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Invite
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuInvite">
                            @foreach ($notInvited as $authUser)
                            @if($authUser->user->id != $event->user->id)
                            <form method="POST" action="{{ route('events.notification', $event->id) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="id_user" value="{{ $authUser->user->id }}">
                                <input type="hidden" name="eventId" value="{{ $event->id }}">
                                <input type="hidden" name="action" value="invitation">
                                <button type="submit" class="dropdown-item">{{ $authUser->user->name }}</button>
                            </form>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($isAdmin || $isOrganizer)
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

                    @if(count($nonParticipants) > 0 && $event->eventparticipants()->count() < $event->capacity)
                        <div class="dropdown">
                            <button class="btn btn-primary m-3 dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

                        <div class="btn-group">
                            <button id='edit-button' type="button" class="btn btn-primary m-3" data-toggle="modal" data-target="#newEventModal">Edit</button>
                        </div>
                        @endif
                        @endif

                        @if($isAdmin)
                        <form method="POST" action="{{ route('events.delete', $event->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <div class="btn-group">
                                <button type="submit" class="btn btn-custom">Delete</button>
                            </div>
                        </form>
                        @endif
            </div>
        </div>
    </div>
    <div class="info mx-auto d-block" style="width: 70%;">
        <div class="event-top">
            <div id="event-bg">
                <img class="mx-auto d-block" src="https://mdbootstrap.com/img/new/standard/nature/111.webp" />
            </div>
            <div class="event-info mt-5">
                <h1>{{ $event->title }}</h1>
                <h4>Hosted by:</h4>
                <p>{{ $event->user->name }}</p>

                <h4>Location:</h4>
                <p>{{ $event->place }}</p>

                <h4>Date:</h4>
                <p>{{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</p>

                <h4>Description:</h4>
                <p>{{ $event->description }}</p>
                <div class="tags">
                    @foreach($event->hashtags as $hashtag)
                    <span class="hashtag">#{{ $hashtag->title }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="attendees">
            <div class="capacity d-flex">
                <div class="capacity-item mr-3">
                    <h4>Joined</h4>
                    <p>{{ $event->eventparticipants()->count() }}</p>
                </div>
                <div class="capacity-item">
                    <h4>Limit</h4>
                    <p>{{ $event->capacity }}</p>
                </div>
            </div>
            <div class="attendees-list mr-4">
                <h3>Attendees:</h3>
                <table class="table table-hover" style="border: 0;">
                    <thead>
                        <tr>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($event->eventparticipants()->get() as $attendee)
                        <tr>
                            <td>{{ $attendee->user->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;"></div>

    @include('partials.eventModal', ['formAction' => route('events.edit', $event->id), 'hashtags' => $hashtags])

    @endsection