@extends('layouts.app')

@section('content')

<ul>
    @foreach ($events as $event)
        <li>
            <a href="{{ url('events/' . $event->id . '/details') }}">
                {{ $event->title }}
            </a>
        </li>
    @endforeach
</ul>
@endsection