@extends('layouts.app')

@section('content')


<div class="content-container">
    <div class="form-outline" data-mdb-input-init>
        <input type="search" id="form1" class="form-control" placeholder="Search" aria-label="Search" />
        <div class="filters">
            <button id='location-button' type="submit" class="btn btn-custom btn-block">Location</button>
            <button id='date-button' type="submit" class="btn btn-custom btn-block">Date</button>
            <button id='tag-button' type="submit" class="btn btn-custom btn-block">Tag</button>
            @if (App\Models\AuthenticatedUser::where('id_user', Auth::user()->id)->exists())
                <button id='NEvent-button' type="button" class="btn btn-custom btn-block" data-toggle="modal" data-target="#newEventModal">New Event</button>
            @endif        
        </div>
    </div>

    <div id="main">
        @foreach ($events as $event)
            @include('partials.event', ['event' => $event])
        @endforeach
    </div>
</div>

<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;"></div>

@include('partials.eventModal', ['formAction' => route('events.create')])


@endsection