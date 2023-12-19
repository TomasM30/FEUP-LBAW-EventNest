@extends('layouts.app')

@section('content')
<div class="col mt-3 justify-content-center">
    <div class="col-12 col-md-3 mb-3 mb-md-0 mx-auto">
        <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
            <a class="nav-link pill-link no-js active" href="{{ route('dashboard') }}">Statistics</a>
            <a class="nav-link pill-link no-js" href="{{ route('admin.users') }}">Users</a>
            <a class="nav-link pill-link no-js" href="{{ route('admin.events') }}">Events</a>
            <a class="nav-link pill-link no-js" href="{{ route('admin.reports') }}">Reports</a>
            <a class="nav-link pill-link no-js" href="{{ route('admin.tags') }}">Tags</a>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <h2 class="mb-4">User Statistics</h2>
        <div class="col-md-12">
            <div class="card bg-light mb-3">
                <div class="card-header">Users</div>
                <div class="card-body">
                    <div class="progress">
                        <?php $totalUsers = $usersverifiedCount + $usersnotverifiedCount; ?>
                        <div class="progress-bar" role="progressbar" style="width: {{ ($usersverifiedCount/$totalUsers)*100 }}%;" aria-valuenow="{{ $usersverifiedCount }}" aria-valuemin="0" aria-valuemax="{{ $totalUsers }}">Verified Users: {{ $usersverifiedCount }}</div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($usersnotverifiedCount/$totalUsers)*100 }}%;" aria-valuenow="{{ $usersnotverifiedCount }}" aria-valuemin="0" aria-valuemax="{{ $totalUsers }}">Unverified Users: {{ $usersnotverifiedCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <h2 class="mb-4">Event Statistics</h2>
        <div class="col-md-12">
            <div class="card bg-light mb-3">
                <div class="card-header">Events</div>
                <div class="card-body">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($ongoingevents/$eventscount)*100 }}%;" aria-valuenow="{{ $ongoingevents }}" aria-valuemin="0" aria-valuemax="{{ $eventscount }}">Ongoing Events: {{ $ongoingevents }}</div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($closedevents/$eventscount)*100 }}%;" aria-valuenow="{{ $closedevents }}" aria-valuemin="0" aria-valuemax="{{ $eventscount }}">Closed Events: {{ $closedevents }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <h2 class="mt-5 mb-4">Top 3 Event Hashtags</h2>
        @foreach($hasthagsbycount as $hashtag)
        <div class="col-md-4">
            <div class="card bg-light mb-3">
                <div class="card-header">Hashtag: {{ $hashtag->title }}</div>
                <div class="card-body">
                    <h5 class="card-title">Used in {{ $hashtag->events_count }} events</h5>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>



@endsection