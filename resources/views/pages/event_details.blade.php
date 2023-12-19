@extends('layouts.app')

@section('content')

@if ($errors->any())
    <script>
        showAlert('Error!', '{{ $errors->first() }}', 'error');
    </script>
@endif

@if (session('error'))
    <script>
        showAlert('Error!', '{{ session('error') }}', 'error');
    </script>
@endif

@if (session('success'))
    <script>
        showAlert('Success!', '{{ session('success') }}', 'success');
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
            @if($event->hasTickets())
                <h4 style="overflow-wrap: break-word;">Tickets Sold</h4>
                <p>{{ $event->soldTicketsCount() }}</p>
            @else
                <h4>Joined</h4>
                <p>{{ $event->eventparticipants()->count() }}</p>
            @endif
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
                    @if(!$event->hasTickets())
                        <a class="nav-link active" id="v-pills-attendees-tab" data-toggle="pill" href="#v-pills-attendees">Attendees</a>
                    @endif
                    @if(($isParticipant || $isAdmin) && !$event->hasTickets())
                        <a class="nav-link" id="v-pills-chat-tab" data-toggle="pill" href="#v-pills-chat">Chat</a>
                    @endif
                    <a class="nav-link {{ $event->hasTickets() ? 'active' : '' }}" id="v-pills-comments-tab" data-toggle="pill" href="#v-pills-comments">Comments</a>
                </div>
                </div>
                <div class="col-12 col-md-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade {{ !$event->hasTickets() ? 'show active' : '' }}" id="v-pills-attendees">
                        @include('partials.attendeesTable', ['attendees' => $attendees])
                    </div>
                    <div class="tab-pane fade" id="v-pills-chat" data-event-id="{{ $event->id }}"> 
                                    <div id="chat">
                                        <div id="messages" style="height: 250px; overflow-y: auto;">
                                            @foreach($messages as $message)
                                            <div class="message">
                                                <div class="message-header">
                                                    <div style="display: flex; align-items: center;">
                                                        @if ($message->id_user != null)
                                                            <div style="width: 50px; height: 50px; border-radius: 50%; background-image: url('{{$message->authenticated->user->getProfileImage() }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                                                            <p class="ml-3 mr-1" style="margin: 0; padding: 0;">{{ $message->authenticated->user->username }}</p>
                                                            @if($message->authenticated->user->authenticated->is_verified == 1)
                                                                <i class="fa-solid fa-circle-check"></i>
                                                            @endif
                                                        @else
                                                            <div style="width: 50px; height: 50px; border-radius: 50%; background-image: url('{{ asset('profile/default.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                                                            <p class="ml-3 mr-1" style="margin: 0; padding: 0; color: red; font-weight: bold;">User deleted</p>
                                                        @endif
                        
                                                    </div>
                                                </div>
                                                    <p class="message-content" style="max-width: 100%; overflow-wrap: break-word;">{{ $message->content }}</p>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="container mt-5">
                                        <form id="message-form" class="form-inline">
                                            <div class="form-group mb-2" style="flex-grow: 1;">
                                                <input type="text" class="form-control w-100" id="message-input" placeholder="Type your message here..." {{ $event->closed || Auth::user()->isAdmin() ? 'disabled' : '' }}>
                                            </div>
                                            <button type="submit" class="btn btn-primary mb-2 ml-2" {{ $event->closed || Auth::user()->isAdmin() ? 'disabled' : '' }}>Send</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade {{ $event->hasTickets() ? 'show active' : '' }}" id="v-pills-comments">
                                        <section class="gradient-custom">
                                            <div class="row justify-content-center">
                                                <div class="col-12 col-md-12 col-lg-12">
                                                    <div class="card-body p-4">
                                                        <h5 class="mb-0">Recent comments</h5>
                                                        <p class="fw-light mb-4 pb-2">Latest Comments</p>
                                                        <div id="comments" class="overflow-auto" style="max-height: 450px;">
                                                        @if($event->comments != null)
                                                            @foreach($event->comments as $comment)
                                                                <div class="card mb-3">
                                                                    <div class="d-flex flex-start p-3">
                                                                        <img class="rounded-circle shadow-1-strong me-3" src="{{ url($comment->authenticated && $comment->authenticated->user ? $comment->authenticated->user->getProfileImage() : asset('profile/default.png')) }}" alt="avatar" width="40" height="40" />
                                                                    <div>
                                                                            @if($comment->id_user != null)
                                                                                <a href="{{ route('user.profile', [ 'id' => $comment->id_user]) }}" class="text-decoration-none text-dark">
                                                                                    <h6 class="fw-bold mb-1">{{ $comment->authenticated->user->name }}
                                                                                        @if($comment->authenticated->is_verified == true)
                                                                                            <i class="fa-solid fa-circle-check"></i>
                                                                                        @endif
                                                                                    </h6>
                                                                                </a>
                                                                            @else
                                                                                <p class="mr-1" style="margin: 0; padding: 0; color: red; font-weight: bold;">User deleted</p>
                                                                            @endif
                                                                            <div class="d-flex align-items-center mb-3">
                                                                                <p class="mb-0 small">
                                                                                    {{ $comment->date }}
                                                                                    <span class="badge bg-primary">Posted</span>
                                                                                </p>
                                                                                @if( Auth::user()->id == $comment->id_user || $isAdmin )
                                                                                    <form action="{{ route('event.removeComment', ['id' => $event->id, 'commentId' => $comment->id]) }}" method="post" class="ms-2">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button type="submit" class="btn btn-link p-0 ms-2 text-danger">
                                                                                            <i class="fas fa-trash-alt"></i>
                                                                                        </button>
                                                                                    </form>
                                                                                @endif
                                                                            </div>
                                                                            <p class="mb-0">
                                                                                {{ $comment->content }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        </div>
                                                        <div class="card mt-4 shadow">
                                                            <div class="card-body p-4">
                                                                <h5 class="text-center mb-4 pb-2">Add a Comment</h5>
                                                                <form id="commentForm" action="{{ route('event.addComment', ['id' => $event->id]) }}" method="post">
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <textarea class="form-control rounded" name="content" rows="5" placeholder="Your comment" required 
                                                                            {{ $event->eventparticipants()->where('id_user', Auth::user()->id)->exists() 
                                                                                || (Auth::user()->authenticated && Auth::user()->authenticated->orders()->whereHas('tickets.ticketType', function ($query) use ($event) {
                                                                                    $query->where('id_event', $event->id);
                                                                                })->exists())
                                                                                || !Auth::user()->isAdmin() ? '' : 'disabled' }}>
                                                                        </textarea>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary w-100 py-2" 
                                                                        {{ $event->eventparticipants()->where('id_user', Auth::user()->id)->exists() 
                                                                        || (Auth::user()->authenticated && Auth::user()->authenticated->orders()->whereHas('tickets.ticketType', function ($query) use ($event) {
                                                                            $query->where('id_event', $event->id);
                                                                        })->exists()) 
                                                                        || !Auth::user()->isAdmin() ? '' : 'disabled' }}>Add Comment
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="col-lg-3 mt-3" style=" background-color: white;">
                <div class=" d-flex flex-wrap justify-content-center position-sticky" style="top: 15%;">
                @if (!$isAdmin && $alreadyReported == false)
                <div class="btn-group" style="width: 100%;">
                    <button id="reportBtn" type="submit" class="btn btn-primary m-3 ">Report</button>
                </div>
                @endif
                @if ($event->closed == false)
                @if(!$isAdmin && $isParticipant && !$event->hasTickets())
                    <div class="btn-group" style="width: 100%;">
                        <button id='invitebtn' type="button" class="btn btn-primary m-3 " data-toggle="modal">Invite</button>
                    </div>
                @endif
                @if(!$isParticipant && !$isAdmin && $event->eventparticipants()->count() < $event->capacity)
                    @if(($event->type == 'public' || $event->type == 'private') && $event->hasTickets() && !$isOrganizer && !$isParticipant && $userTicketLimit > 0)
                    <div class="card mt-4" style="width: 85%;">
                            <div class="card-header">
                                <h4>Order Tickets</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('events.order', $event->id) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="id_event" value="{{ $event->id }}">

                                    <div class="form-group">
                                        <label for="ticketQuantity">Number of Tickets:</label>
                                        <input type="number" name="amount" id="ticketQuantity" placeholder="Max tickets to buy: {{ $userTicketLimit }}" class="form-control" min="1" max="{{ $userTicketLimit }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="ticketPrice">Ticket Price:</label>
                                        <p id="ticketPrice">{{ $ticketPrice }}€</p>
                                    </div>

                                    <button type="submit" class="btn btn-primary" href="{{ route('paypal.payment') }}" style="width:100%;">Order</button>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if(($event->type == 'public' || $event->type == 'private') && !$event->hasTickets())
                    <form method="POST" action="{{ route('event.join', $event->id) }}" style="width: 100%;" class="d-flex justify-content-center">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="eventId" value="{{ $event->id }}">
                        <div class="btn-group" style="width: 100%;">
                            <button type="submit" class="btn btn-primary m-3 ">Join</button>
                        </div>
                    </form>
                    @elseif($event->type == 'approval' && $alreadyRequested == false && !$event->hasTickets())
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
                    @elseif($isParticipant && !$isAdmin && !$isOrganizer && !$event->hasTickets())
                    <form method="POST" action="{{ route('event.leave', $event->id) }}" style="width: 100%;" class="d-flex justify-content-center">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="eventId" value="{{ $event->id }}">
                        <button type="submit" class="btn btn-primary m-3" style="width: 100%;">Leave</button>
                    </form>
                    @endif

                    @if( ($isOrganizer && !$event->hasTickets()) || $isAdmin)
                        <div class="btn-group" style="width: 100%;">
                            <button id='manage-btn' type="button" class="btn btn-primary m-3 ">Manage Users</button>
                        </div>

                        @if(($isOrganizer && $user->authenticated->is_verified == 0)  || $isAdmin)
                            <div class="btn-group" style="width: 100%;">
                                <button id='cancelbtn' type="button" class="btn btn-primary m-3 ">Cancel</button>
                            </div>
                        @endif

                        <div class="btn-group" style="width: 100%;">
                            <button id='edit-button' type="button" class="btn btn-primary m-3 " data-toggle="modal" data-target="#newEventModal">Edit</button>
                        </div>
                    @endif
                @endif
                @if($isAdmin)
                    <div class="btn-group" style="width: 100%;">
                        <button id='deletebtn' type="button" class="btn btn-primary m-3 ">Delete</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;"></div>
@if (!$isAdmin)
    @include('partials.eventModal', ['formAction' => route('events.edit', $event->id), 'hashtags' => $hashtags])
    @include('partials.reportModal')
    @include('partials.inviteUserModal')
@endif
@include('partials.manageUserModal')
@include('partials.deleteEventModal')
@include('partials.cancelEventModal')
@endsection