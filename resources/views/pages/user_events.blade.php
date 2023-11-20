@extends('layouts.app')

@section('content')
<h1>{{ $user->name }}'s Events</h1>

@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<ul>
    @foreach ($events as $event)
        <li>
            {{ $event->title }}
            <form method="POST" action="{{ route('events.delete', $event->id) }}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit">Delete</button>
            </form>
        </li>
    @endforeach
</ul>
@endsection