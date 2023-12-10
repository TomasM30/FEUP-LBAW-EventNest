@extends('layouts.app')

@section('content')
<h1 class="text-center mb-4 mt-4" style="overflow-wrap: break-word;">{{ $user->username }}'s Favorite Events</h1>
<div class="col mt-3 justify-content-center">
    <div class="col-12 col-md-3 mb-3 mb-md-0 mx-auto">
        <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
            <a class="nav-link pill-link no-js" href="{{ route('user.events', ['id' => $user->id]) }}">Created</a>
            <a class="nav-link pill-link no-js" href="{{ route('user.events.joined', ['id' => $user->id]) }}">Joined</a>
            <a class="nav-link pill-link no-js active" href="{{ route('user.events.favorites', ['id' => $user->id]) }}">Favorites</a>
            <a class="nav-link pill-link no-js" href="{{ route('user.events.attended', ['id' => $user->id]) }}">Attended</a>
        </div>
    </div>
    @include('partials.usersEventList', ['events' => $favouriteEvents, 'type' => 'favorite'])
    
@endsection