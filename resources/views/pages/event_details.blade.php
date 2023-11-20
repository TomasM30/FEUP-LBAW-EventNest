@extends('layouts.app')

@section('content')

<ul>
    @foreach ($events as $attendee)
        <li>{{ $attendee->user->username }}</li>
    @endforeach
</ul>

@endsection