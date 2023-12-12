@extends('layouts.app')

@section('content')
<div class=" col mt-3 justify-content-center">
    <h1 class="text-center mb-4 mt-4" style="overflow-wrap: break-word;">{{ $user->username }}'s Attended Events</h1>
    <div class="col-12 col-md-4 mb-3 mb-md-0 mx-auto pl-5"> 
        <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
            <a class="nav-link pill-link no-js" href="{{ route('user.events', ['id' => $user->id]) }}">Created</a>
            <a class="nav-link pill-link no-js" href="{{ route('user.events.joined', ['id' => $user->id]) }}">Joined</a>
            <a class="nav-link pill-link no-js" href="{{ route('user.events.favorites', ['id' => $user->id]) }}">Favorites</a>
            <a class="nav-link pill-link no-js  active" href="{{ route('user.events.attended', ['id' => $user->id]) }}">Attended</a>
            @if(Auth::user()->isAdmin())
                <a class="nav-link pill-link no-js" href="{{ route('user.profile', ['id' => $user->id]) }}">Profile</a>
            @endif
        </div>
    </div>
    @include('partials.usersEventList', ['events' => $attendedEvents, 'type' => 'attended'])
    
@endsection