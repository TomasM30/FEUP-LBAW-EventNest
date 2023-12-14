@extends('layouts.app')

@section('content')

@if ($errors->any())
    <script>
        showAlert('Error!', '{{ $errors->first() }}', 'error');
    </script>
@endif

@if (session('error'))
    <script>
        showAlert('Error!', '{{ session('error') }}', 'error');
    </script>
@endif

@if (session('success'))
    <script>
        showAlert('Success!', '{{ session('success') }}', 'success');
    </script>
@endif

<div class="mx-auto" style="max-width: 50%;">
    <h1 class="text-center mb-4 mt-4">Notifications</h1>
    @if(count($notifications) > 0)
    @foreach ($notifications as $notification)
        @if($notification->eventnotification)
        <div class="m-3">
            <div class="alert alert-dismissible alert-primary">
                <form method="POST" action="{{ route('notification.delete', $notification->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-close" type="submit">
                    </button>
                </form>
                <a href="{{ route('events.details', $notification->eventnotification->event->id) }}">
                    <h4 class="alert-heading">{{ $notification->eventnotification->event->title }}</h4>
                </a>
                <p style="overflow-wrap:break-word;">
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
                    @elseif($notification->type == 'report_received')
                        <a href="{{ route('report.details', $notification->report->id) }}" style="text-decoration: none;">
                            {{ $notification->eventnotification->inviter->user->username}} has reported the event
                            <i class="fas fa-exclamation-triangle"></i>
                        </a>
                    @elseif($notification->type == 'report_closed')
                        The report has been closed.
                    @endif
                </p>
                <div class="row">
                    <div class="col" style="max-width: fit-content;">
                    </div>
                </div>
                <p class="mt-3">{{ \Carbon\Carbon::parse($notification->created_at)->format('d-m-Y H:i') }}</p>
                @if($notification->type == 'invitation_received' || $notification->type == 'request')
                <form method="POST" action="{{ route($notification->type == 'invitation_received' ? 'event.join' : 'events.add', $notification->eventnotification->event->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_user" value="{{ $notification->type == 'invitation_received' ? Auth::user()->id : $notification->eventnotification->inviter->user->id }}">
                    <input type="hidden" name="eventId" value="{{ $notification->eventnotification->event->id }}">
                    <input type="hidden" name="notificationId" value="{{ $notification->id }}">
                    <input type="hidden" name="action" value="{{ $notification->type == 'invitation_received' ? 'invitation' : 'request' }}">
                    <button class="btn btn-outline-primary" type="submit">{{ $notification->type == 'invitation_received' ? 'Accept' : 'Approve' }}</button>
                </form>
                @endif
            </div>
        </div>
        @endif
    @endforeach
    @else
    <div class="card m-3">
        <div class="card-body text-center">
            <h4 class="card-title">No Notifications</h4>
            <p class="card-text">You currently have no notifications.</p>
        </div>
    </div>
    @endif
</div>


@endsection