@extends('layouts.app')

@section('content')
<h1 class="dashboardtitle">Admin Dashboard</h1>
<div class="dashboard-section">
    <h2 class="tabletitle">Users</h2>
    <table class="user-table table">
        <thead>
            <tr>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $authenticatedUser)
            <tr>
                <td><a href="{{ url('/user/' . $authenticatedUser->user->id . '/events') }}">{{ $authenticatedUser->user->username }}</a></td>
            </tr>
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
                <td>{{ $event->title }}</td>
                <td>{{ $event->date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection