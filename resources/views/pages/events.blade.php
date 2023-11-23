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

@include('partials.eventModal', ['formAction' => route('events.create')])


@endsection