@extends('layouts.app')

@section('content')
<div class="col mt-3">
    <div class="col-12 col-md-3 mb-3 mb-md-0">
        <div class="nav flex-row nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="v-pills-users-tab" href="#v-pills-users">Users</a>
            <a class="nav-link" id="v-pills-events-tab" href="#v-pills-events">Events</a>
        </div>
    </div>
    <div class="col-12 col-md-9">
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-users">
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
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-events">
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