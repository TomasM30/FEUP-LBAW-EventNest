@extends('layouts.app')

@section('content')
<div>
    @foreach ($notifications as $notification)
        @if($notification->invitationnotification)
            <div>
                <div>
                    <h2>
                        <a href="{{ route('events.details', $notification->invitationnotification->event->id) }}">{{ $notification->invitationnotification->event->title }}</a>
                    </h2>
                    @if($notification->type == 'invitation_received')
                        <p>Invitation by: {{ $notification->invitationnotification->inviter->user->username}}</p>
                        <form method="POST" action="{{ route('event.join', $notification->invitationnotification->event->id) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="eventId" value="{{ $notification->invitationnotification->event->id }}">
                            <button type="submit">Accept</button>
                        </form>
                        <form method="POST" action="{{ route('invite.delete', ['userId' => Auth::user()->id, 'eventId' => $notification->invitationnotification->event->id, 'inviterId' => $notification->invitationnotification->inviter_id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <input type="hidden" name="inviterId" value="{{ $notification->invitationnotification->inviter_id }}">
                            <button type="submit">Reject</button>
                        </form>
        
                    @endif
                    <p>{{ \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d') }}</p>

                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection