@extends('layouts.app')

@section('content')
<h1 class="dashboardtitle">Admin Dashboard</h1>
<div class="dashboard-section">
    <h2 class="tabletitle">Users</h2>
    <table class="user-table table">
        <thead>
            <tr>
                <th>Users</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $authenticatedUser)
                <tr>
                <td><a class="custom-link" href="{{ route('user.events', $authenticatedUser->user->id) }}">{{ $authenticatedUser->user->username ." | ". $authenticatedUser->user->name }}</a></td>
            @endforeach
        </tbody>
    </table>
</div>

<div class="dashboard-section">
    <h2 class="tabletitle">Events</h2>
    <table class="event-table table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $event)
            <tr>
                <td><a class="custom-link" href="{{ route('events.details', $event->id) }}">{{ $event->title }}</a></td>
                <td>{{ \Carbon\Carbon::parse($event->date)->format('d/m/y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection