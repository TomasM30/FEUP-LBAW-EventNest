@extends('layouts.app')

@section('content')
<div>
    @foreach ($notifications as $notification)
    @if($notification->eventnotification)
    <div class="m-3">
        <div class="alert alert-dismissible alert-primary">>
            <a href="{{ route('events.details', $notification->eventnotification->event->id) }}">
                <h4 class="alert-heading">{{ $notification->eventnotification->event->title }}</h4>
            </a>
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
                @elseif($notification->type == 'event_canceled')
                The event has been cancelled.
                @elseif($notification->type == 'event_edited')
                The event information has been edited.
                @endif
            </p>
            @if($notification->type == 'invitation_received' || $notification->type == 'request')
            <form method="POST" action="{{ route($notification->type == 'invitation_received' ? 'event.join' : 'events.add', $notification->eventnotification->event->id) }}">
                {{ csrf_field() }}
                <input type="hidden" name="id_user" value="{{ $notification->type == 'invitation_received' ? Auth::user()->id : $notification->eventnotification->inviter->user->id }}">
                <input type="hidden" name="eventId" value="{{ $notification->eventnotification->event->id }}">
                <input type="hidden" name="notificationId" value="{{ $notification->id }}">
                <input type="hidden" name="action" value="{{ $notification->type == 'invitation_received' ? 'invitation' : 'request' }}">
                <button class="m-1" type="submit">{{ $notification->type == 'invitation_received' ? 'Accept' : 'Approve' }}</button>
            </form>
            @endif
            <form method="POST" action="{{ route('notification.delete', $notification->id) }}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button class="m-1" type="submit">
                    @if($notification->type == 'invitation_received' || $notification->type == 'request')
                    Reject
                    @else
                    Remove
                    @endif
                </button>
            </form>
            <p>{{ \Carbon\Carbon::parse($notification->created_at)->format('d-m-Y H:i') }}</p>
        </div>
    </div>
    @endif
    @endforeach
</div>

@endsection