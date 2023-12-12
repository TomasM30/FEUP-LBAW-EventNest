@extends('layouts.app')

@section('content')

@if ($errors->any())
    <script>
        Swal.fire({
            title: 'Error!',
            text: '{{ $errors->first() }}',
            icon: 'error',
            timer: 1500,
            showConfirmButton: false
        });
    </script>
@endif

@if (session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    </script>
@endif

<div class="d-flex justify-content-center">
    <div class="col-9 content-container m-3" id="details-content-container">
        @if($event->closed)
        <div class="alert alert-info event-closed-alert">
            This event was cancelled or already happened.
        </div>
        @endif

        <div class="text-center position-relative overflow-hidden">
            <div id="bluredPic" class="position-absolute w-100 h-100 bluredPic" style="background-image: url('{{ $event->getProfileImage() }}');"></div>
            @if(Auth::user()->id == $event->id_user)
            <img class="mx-auto d-block img-fluid" src="{{ $event->getProfileImage() }}" style="max-height: 300px; position: relative; z-index: 1;" />
            @else
            <img class="mx-auto d-block img-fluid" src="{{ $event->getProfileImage() }}" style="max-height: 300px; position: relative; z-index: 1;" />
            @endif
        </div>

        <div class="row mt-3">
            <div class="col-lg-9 col-md-12">
                <div id="eventInfo" class="event-info mt-3" style="overflow-wrap: break-word;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1>{{ $event->title }}</h1>
                        @if(!$isAdmin && !$isFavourite)
                        <form method="POST" action="{{ route('event.favourite', $event->id) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id_event" value="{{ $event->id }}">
                            <button type="submit" class="btn btn-outline-danger p-2 rounded-circle" style="width: 50px; height: 50px;">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </form>
                        @elseif(!$isAdmin && $isFavourite)
                        <form method="POST" action="{{ route('event.removeFavourite', $event->id) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id_event" value="{{ $event->id }}">
                            <button type="submit" class="btn btn-outline-secondary p-2 rounded-circle" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-heart-crack"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                    <h4 class="m-0">Hosted by:</h4>
                    <div class="d-flex align-items-center mb-3">
                        @if($event->user)
                        <a href="{{ route('user.profile', $event->user->id) }}" style="text-decoration: none; color: inherit; overflow-wrap: break-word;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background-image: url('{{ $event->user->getProfileImage() }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                                <p class="ml-3 mr-1" style="margin: 0; padding: 0;">{{ $event->user->username }}</p>
                                @if($event->user->authenticated->is_verified == 1)
                                <i class="fa-solid fa-circle-check"></i>
                                @endif
                            </div>
                        </a>
                        @else
                        <div class="alert alert-dismissible alert-danger">
                            <p class="ml-3" style="margin: 0; padding: 0; color: red; font-weight: bold;">User deleted</p>
                        </div>
                        @endif
                    </div>
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
                    <div class="capacity d-flex justify-content-around  mt-5 mb-3">
                        <div class="capacity-item d-flex flex-column justify-content-center align-items-center">
                            <h4>Joined</h4>
                            <p>{{ $event->eventparticipants()->count() }}</p>
                        </div>
                        <div class="capacity-item d-flex flex-column justify-content-center align-items-center">
                            <h4>Limit</h4>
                            <p>{{ $event->capacity }}</p>
                        </div>
                    </div>
                </div>
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="v-pills-attendees-tab" data-toggle="pill" href="#v-pills-attendees">Attendees</a>
                                <a class="nav-link" id="v-pills-chat-tab" data-toggle="pill" href="#v-pills-chat">Chat</a>
                                <a class="nav-link" id="v-pills-comments-tab" data-toggle="pill" href="#v-pills-comments">Comments</a>
                            </div>
                        </div>
                        <div class="col-12 col-md-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-attendees">
                                    <!-- Attendees content goes here -->
                                    @include('partials.attendeesTable', ['attendees' => $attendees])
                                </div>
                                <div class="tab-pane fade" id="v-pills-chat" data-event-id="{{ $event->id }}"> 
                                    <!-- Chat content goes here -->
                                    <div id="chat">
                                        <!-- Messages will be added here -->
                                    </div>

                                    <form id="message-form">
                                        <input type="text" id="message-input">
                                        <button type="submit">Send</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="v-pills-comments">
                                    <!-- Comments content goes here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mt-3 position-sticky" style="bottom: 0%; background-color: white;"">
                <div class=" d-flex flex-wrap justify-content-center position-sticky" style="top: 15%;">
                @if (!$isAdmin && $alreadyReported == false)
                <div class="btn-group" style="width: 100%;">
                    <button id="reportBtn" type="submit" class="btn btn-primary m-3 ">Report</button>
                </div>
                @endif
                @if ($event->closed == false)
                @if(!$isAdmin && $isParticipant)
                <div class="dropdown d-flex justify-content-center" style="width: 100%">
                    <button class="btn btn-primary m-3 dropdown-toggle invite" style="width: 100%;" type="button" id="dropdownMenuInvite" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                @if(!$isParticipant && !$isAdmin && $event->eventparticipants()->count() < $event->capacity)
                    @if($event->type == 'public' || $event->type == 'private')
                    <form method="POST" action="{{ route('event.join', $event->id) }}" style="width: 100%;" class="d-flex justify-content-center">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="eventId" value="{{ $event->id }}">
                        <div class="btn-group" style="width: 100%;">
                            <button type="submit" class="btn btn-primary m-3 ">Join</button>
                        </div>
                    </form>
                    @elseif($event->type == 'approval' && $alreadyRequested == false)
                    <form method="POST" action="{{ route('events.notification', $event->id) }}" style="width: 100%;" class="d-flex justify-content-center">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_user" value="{{ $event->id_user }}">
                        <input type="hidden" name="eventId" value="{{ $event->id }}">
                        <input type="hidden" name="action" value="request">
                        <input type="hidden" name="type" value="request">
                        <div class="btn-group" style="width: 100%;">
                            <button type="submit" class="btn btn-primary m-3 ">Request</button>
                        </div>
                    </form>
                    @endif
                    @elseif($isParticipant && !$isAdmin && !$isOrganizer)
                    <form method="POST" action="{{ route('event.leave', $event->id) }}" style="width: 100%;" class="d-flex justify-content-center">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="eventId" value="{{ $event->id }}">
                        <button type="submit" class="btn btn-primary m-3" style="width: 100%;">Leave</button>
                    </form>
                    @endif

                    @if($isAdmin || $isOrganizer)
                    @if($event->eventparticipants()->count() > 1)
                    <div class="dropdown d-flex justify-content-center" style="width: 100%;">
                        <button class="btn btn-primary m-3  dropdown-toggle remove" style="width: 100%;" type="button" id="dropdownMenuRemove" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Remove
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            @foreach ($event->eventparticipants()->get() as $attendee)
                            @if($attendee->user->id != $event->user->id)
                            <form method="POST" action="{{ route('events.remove', $event->id) }}" style="width: 100%;" class="d-flex justify-content-center">
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
                        <div class="dropdown d-flex justify-content-center" style="width: 100%;">
                            <button class="btn btn-primary m-3  dropdown-toggle" style="width: 100%;" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Add
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                @foreach ($nonParticipants as $authUser)
                                @if($authUser->user->id != $event->user->id)
                                <form method="POST" action="{{ route('events.add', $event->id) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_user" value="{{ $authUser->user->id }}">
                                    <input type="hidden" name="eventId" value="{{ $event->id }}">
                                    <button type="submit" class="dropdown-item">{{ $authUser->user->username }}</button>
                                </form>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(($isOrganizer && $user->authenticated->is_verified == 0)  || $isAdmin)
                        <form method="POST" action="{{ route('events.cancel', $event->id) }}" style="width: 100%;" class="d-flex justify-content-center">
                            {{ csrf_field() }}
                            <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="eventId" value="{{ $event->id }}">
                            <div class="btn-group" style="width: 100%;">
                                <button type="submit" class="btn btn-primary m-3 ">Cancel</button>
                            </div>
                        </form>
                        @endif

                        <div class="btn-group" style="width: 100%;">
                            <button id='edit-button' type="button" class="btn btn-primary m-3 " data-toggle="modal" data-target="#newEventModal">Edit</button>
                        </div>
                        @endif
                        @endif
                        @if($isAdmin)
                        <form method="POST" action="{{ route('events.delete', ['id' => $event->id]) }}" class="d-flex justify-content-center" style="width: 100%;">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <input type="hidden" name="id" value="{{ $event->id }}">
                            <div class="btn-group" style="width: 100%;">
                                <button type="submit" class="btn btn-primary m-3 ">Delete</button>
                            </div>
                        </form>
                        @endif
            </div>
        </div>
    </div>
</div>
</div>

<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;"></div>
@include('partials.eventModal', ['formAction' => route('events.edit', $event->id), 'hashtags' => $hashtags])
@include('partials.reportModal')
@endsection