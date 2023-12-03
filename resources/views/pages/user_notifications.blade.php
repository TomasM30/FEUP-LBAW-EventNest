@extends('layouts.app')

@section('content')
<div>
    @foreach ($notifications as $notification)
        @if($notification->eventnotification)
            <div>
                <div>
                    <h2>
                        <a href="{{ route('events.details', $notification->eventnotification->event->id) }}">{{ $notification->eventnotification->event->title }}</a>
                    </h2>
                    <p>
                        @if($notification->type == 'invitation_received')
                            {{ $notification->eventnotification->inviter->user->username}} invited you to join the event.
                        @elseif($notification->type == 'request')
                            {{ $notification->eventnotification->inviter->user->username}} requested to join the event.
                        @elseif($notification->type == 'invitation_accepted')
                            {{ $notification->eventnotification->inviter->user->username}} has accepted your invite.
                        @elseif($notification->type == 'invitation_rejected')
                            {{ $notification->eventnotification->inviter->user->username}} has rejected your invite.
                        @elseif($notification->type == 'request_rejected')
                            Your request to join the event has been rejected.
                        @elseif($notification->type == 'request_accepted')
                            Your request to join the event has been accepted.
                        @elseif($notification->type == 'removed_from_event')
                            You have been removed from the event.
                        @elseif($notification->type == 'added_to_event')
                            You have been added to the event.
                        @endif
                    </p>
                    @if($notification->type == 'invitation_received' || $notification->type == 'request')
                        <form method="POST" action="{{ route($notification->type == 'invitation_received' ? 'event.join' : 'events.add', $notification->eventnotification->event->id) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="id_user" value="{{ $notification->type == 'invitation_received' ? Auth::user()->id : $notification->eventnotification->inviter->user->id }}">
                            <input type="hidden" name="eventId" value="{{ $notification->eventnotification->event->id }}">
                            <input type="hidden" name="notificationId" value="{{ $notification->id }}">
                            <input type="hidden" name="action" value="{{ $notification->type == 'invitation_received' ? 'invitation' : 'request' }}">
                            <button type="submit">{{ $notification->type == 'invitation_received' ? 'Accept' : 'Approve' }}</button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
        <form method="POST" action="{{ route('notification.delete', $notification->id) }}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit">
                @if($notification->type == 'invitation_received' || $notification->type == 'request')
                    Reject
                @else
                    Remove
                @endif
            </button>
        </form>
        <p>{{ \Carbon\Carbon::parse($notification->created_at)->format('d-m-Y H:i') }}</p>    @endforeach
</div>
@endsection