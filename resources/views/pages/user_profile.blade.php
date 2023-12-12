@extends('layouts.app')

@section('content')

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

<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-md-3 mb-3 mb-md-0">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-profile-tab" href="#v-pills-profile">Profile</a>
                <a class="nav-link" id="v-pills-events-tab" href="#v-pills-events">Events</a>
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