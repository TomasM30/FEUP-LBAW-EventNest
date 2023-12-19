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

<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-md-3 mb-3 mb-md-0">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-profile-tab" href="#v-pills-profile">Profile</a>
                <a class="nav-link" id="v-pills-events-tab" href="#v-pills-events">Events</a>
                <a class="nav-link" id="v-pills-tickets-tab" href="#v-pills-tickets">Tickets</a>
                @if($user->id == Auth::user()->id || Auth::user()->isAdmin())
                    <a class="nav-link" id="v-pills-settings-tab" href="#v-pills-settings">Settings</a>
                @endif
            </div>
        </div>
        <div class="col-12 col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-profile">
                    <div class="container m-0" style="max-width: 700px;">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card" style="max-width: 90%;">
                                    <img id="profile-image" src="{{ $user->getProfileImage() }}" class="card-img-top" alt="Profile Image">
                                    <div class="card-body">
                                        <h5 
                                            class="card-title">{{ $user->username }}
                                            @if($user->authenticated->is_verified == 1)
                                                <i class="fa-solid fa-circle-check"></i>
                                            @endif
                                        </h5>
                                        <p class="card-text">{{ $user->name }}</p>
                                        <p class="card-text">{{ $user->email }}</p>
                                        @if($user->id == Auth::user()->id || Auth::user()->isAdmin())
                                            <button type="button" id="editProfileButton" class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">
                                                Edit Profile
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($user->id == Auth::user()->id && $user->authenticated->is_verified == 0)
                                <div class="col-md-4 mt-3 mt-md-0">
                                    <div class="card" style="max-width: 100%; position: relative;">
                                        <button id="questionBtn" type="button" class="p-0" style="position: absolute; top: 10px; right: 10px; color: inherit; text-decoration: none; background: none; border: none;">
                                            <i class="fas fa-question-circle"></i>
                                        </button>
                                        <div class="card-body">
                                            <h5 class="card-title">Want to be a verified User?</h5>
                                            <button type="button" class="btn btn-primary" id="verifiedBTn">
                                                Request to be verified
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-events">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Events Hosted</h5>
                                        <p class="card-text display-4">{{ $eventsHosted }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Events Joined</h5>
                                        <p class="card-text display-4">{{ $eventsJoined }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Total Participants</h5>
                                        <p class="card-text display-4">{{ $totalParticipants }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-tickets">
                    <h3>Tickets</h3>
                    <div class="accordion mt-4" id="ordersAccordion">
                        @forelse($user->authenticated->orders as $order)
                            <div class="card">
                                <div class="card-header" id="headingOrder{{ $order->id }}">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOrder{{ $order->id }}" aria-expanded="true" aria-controls="collapseOrder{{ $order->id }}">
                                            Order #{{ $order->id }}
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapseOrder{{ $order->id }}" class="collapse" aria-labelledby="headingOrder{{ $order->id }}" data-parent="#ordersAccordion">
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($order->tickets as $ticket)
                                                @if($ticket->ticketType->event->date > now())
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <div class="card h-100">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Ticket #{{ $ticket->id }}</h5>
                                                                <p class="card-text">Event: {{ $ticket->ticketType->event->title }}</p>
                                                                <p class="card-text">Type: {{ $ticket->ticketType->title }}</p>
                                                                <p class="card-text">Price: {{ $ticket->ticketType->price }}</p>
                                                                <p class="card-text">Event Date: {{ \Carbon\Carbon::parse($ticket->ticketType->event->date)->format('d M Y') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                                <div class="alert" style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb;">
                                    <h4 class="alert-heading">No Tickets Purchased Yet!</h4>
                                    <p>It seems like you haven't purchased any tickets yet. Check out our events and find something you'd love to attend!</p>
                                    <hr>
                                    <p class="mb-0">Once you've purchased tickets, they will appear here.</p>
                                </div>
                            @endforelse
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-settings">
                    <h3>Settings</h3>
                    <div class="row mt-4">
                        <div class="col-12 col-md-6 mb-3">
                            <a id="changePasswordBtn" href="#" class="btn btn-primary btn-block">Change Password</a>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <button id="deleteAccountBtn" type="button" class="btn btn-danger btn-block" >
                                Delete Account
                            </button>
                        </div>
                    </div>
                    @if( Auth::user()->isAdmin() )
                        <div class="row mt-1">
                            <div class="col-12 col-md-6 mb-3">
                                @if($user->authenticated->is_blocked == false)
                                    <form method="POST" action="{{ route('user.block', ['id' => $user->id]) }}">
                                        {{ csrf_field() }}
                                        <button type="submit "id="blockUserBtn" href="#" class="btn btn-primary btn-block">Block User</button>
                                    </form>
                                @endif

                                @if($user->authenticated->is_blocked == true)
                                    <form method="POST" action="{{ route('user.unblock', ['id' => $user->id]) }}">  
                                        {{ csrf_field() }}
                                        <button type="submit "id="unblockUserBtn" href="#" class="btn btn-primary btn-block">Unblock User</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;"></div>
@if($user->id == Auth::user()->id || Auth::user()->isAdmin())
    @include('partials.profileModal', ['type' => 'profile'])
    @include('partials.passwordChangeModal')
    @include('partials.deleteAccountModal')
    @include('partials.verificationModal')
@endif

@endsection