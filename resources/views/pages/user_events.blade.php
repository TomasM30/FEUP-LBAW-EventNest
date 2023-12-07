@extends('layouts.app')

@section('content')
<h1 class="text-center mb-4 mt-4">{{ $user->username }}'s Events</h1>
<div class="col mt-3">
    <div class="col-12 col-md-3 mb-3 mb-md-0">
        <div class="nav flex-row nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="v-pills-users-tab" href="#v-pills-created">Created</a>
            <a class="nav-link" id="v-pills-events-tab" href="#v-pills-joined">Joined</a>
            <a class="nav-link" id="v-pills-events-tab" href="#v-pills-favorite">Favorite</a>
            <a class="nav-link" id="v-pills-events-tab" href="#v-pills-attended">Attended</a>
        </div>
    </div>
    <div class="col-12 col-md-9 mt-3">
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-created">
                <div class="row row-cols-1 row-cols-md-3 g-4 custom-row" id="container">
                    @foreach ($createdEvents as $event)
                    @include('partials.event', ['event' => $event])
                    @endforeach
                </div>
            </div>
        </div>
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-joined">
                <div class="row row-cols-1 row-cols-md-3 g-4 custom-row" id="container">
                    @foreach ($joinedEvents as $event)
                    @include('partials.event', ['event' => $event])
                    @endforeach
                </div>
            </div>
        </div>
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-favorite">
                <div class="row row-cols-1 row-cols-md-3 g-4 custom-row" id="container">
                    @foreach ($favoriteEvents as $event)
                    @include('partials.event', ['event' => $event])
                    @endforeach
                </div>
            </div>
        </div>
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-attended">
                <div class="row row-cols-1 row-cols-md-3 g-4 custom-row" id="container">
                    @foreach ($attendedEvents as $event)
                    @include('partials.event', ['event' => $event])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection