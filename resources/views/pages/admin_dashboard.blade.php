@extends('layouts.app')


@section('content')
    <h1>Admin Dashboard</h1>

    <h2>Users</h2>
    <ul>
        @foreach ($users as $authenticatedUser)
            <li><a href="{{ url('/user/' . $authenticatedUser->user->id . '/events') }}">{{ $authenticatedUser->user->username }}</a></li>
        @endforeach
    </ul>

    <h2>Events</h2>
    <ul>
        @foreach ($events as $event)
            <li>{{ $event->title }} - {{ $event->date }}</li>
        @endforeach
    </ul>
@endsection