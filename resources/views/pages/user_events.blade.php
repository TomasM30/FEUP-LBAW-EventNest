@extends('layouts.app')

@section('content')
<h1>{{ $user->name }}'s Events</h1>

<ul>
    @foreach ($events as $event)
        <li>{{ $event->title }}</li>
    @endforeach
</ul>
@endsection