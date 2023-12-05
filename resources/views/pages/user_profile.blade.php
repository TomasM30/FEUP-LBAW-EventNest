@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-md-3 mb-3 mb-md-0">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-profile-tab" href="#v-pills-profile">Profile</a>
                <a class="nav-link" id="v-pills-messages-tab" href="#v-pills-messages">Messages</a>
                <a class="nav-link" id="v-pills-settings-tab" href="#v-pills-settings">Settings</a>
            </div>
        </div>
        <div class="col-12 col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-profile">
                    <div class="container" style="max-width: 500px;">
                            <div class="card" style="max-width: 80%;">
                                <img id="profile-image" src="{{ Auth::user()->getProfileImage() }}" class="card-img-top" alt="Profile Image">
                                <div class="card-body">
                                    <h5 class="card-title">{{ Auth::user()->username }}</h5>
                                    <p class="card-text">{{ Auth::user()->name }}</p>
                                    <p class="card-text">{{ Auth::user()->email }}</p>
                                    <button type="button" id="editProfileButton" class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">
                                        Edit Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="tab-pane fade" id="v-pills-messages">Messages content...</div>
                <div class="tab-pane fade" id="v-pills-settings">
                    <h3>Settings</h3>
                    <div class="row mt-4">
                        @if(Auth::user()->google_id == null)
                            <div class="col-12 col-md-6 mb-3">
                                <a id="changePasswordBtn" href="#" class="btn btn-primary btn-block">Change Password</a>
                            </div>
                        @endif
                        <div class="col-12 col-md-6 mb-3">
                            <button id="deleteAccountBtn" type="button" class="btn btn-danger btn-block" >
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;"></div>
@include('partials.profileModal', ['id' => Auth::user()->id, 'type' => 'profile'])
@include('partials.passwordChangeModal')
@include('partials.deleteAccountModal')

@endsection