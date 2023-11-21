@extends('layouts.app')

@section('content')

<ul>
    @foreach ($events as $event)
        <li>
            <a href="{{ url('events/' . $event->id . '/details') }}">
                {{ $event->title}}
            </a>

            @if($event->isJoined)
        
                <button style="background-color: gray;" disabled>Joined!</button>
            @else
           
                <form id="joinForm{{ $event->id }}" method="POST" action="{{ route('events.join') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_user" value="{{ $user->id }}">
                    <input type="hidden" name="eventId" value="{{ $event->id }}">
                    <button type="button" onclick="submitForm({{ $event->id }})">Join</button>
                </form>
                @endif
                <form id="inviteForm{{ $event->id }}" method="POST" action="{{ route('events.invite') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_user" value="{{ $user->id }}">
                    <input type="hidden" name="eventId" value="{{ $event->id }}">
                    <button type="button" onclick="inviteUser({{ $event->id }})">Invite</button>
                </form>
        </li>
    @endforeach
    <script>
        function submitForm(eventId) {
            var form = document.getElementById('joinForm' + eventId);
            form.submit();
        }

        function inviteUser(eventId) {
            var form = document.getElementById('inviteForm' + eventId);
            form.submit();
        }
    </script>
    </script>
</ul>
@endsection