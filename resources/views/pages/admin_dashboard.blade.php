@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col m-4">
        <div class="dashboard-section">
            <h2 class="tabletitle">Users</h2>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Users</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $authenticatedUser)
                    <tr>
                        <td><a class="text-decoration-none" href="{{ route('user.events', $authenticatedUser->user->id) }}">{{ $authenticatedUser->user->username }} | {{ $authenticatedUser->user->name }}</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col m-4">
        <div class="dashboard-section">
            <h2 class="tabletitle">Events</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                    <tr>
                        <td><a class="text-decoration-none" href="{{ route('events.details', $event->id) }}">{{ $event->title }}</a></td>
                        <td>{{ \Carbon\Carbon::parse($event->date)->format('d/m/y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection