@if (!$users->isEmpty())
    <div class="table-responsive" style="height: 500px; overflow-y: auto;">
        <table class="table table-hover mx-auto" id="inviteTable" style="max-width: 1000px;">                                
            <thead>
                <tr>
                    <th>Username</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $authenticatedUser)
                    @if(!in_array($authenticatedUser->user->id, $participants) && !in_array($authenticatedUser->user->id, $invitedUsers))
                        <tr>
                            <td>
                                <p style="text-decoration: none; color: inherit;">{{ $authenticatedUser->user->username }}</p>
                            </td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('events.notification', $event->id) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_user" value="{{ $authenticatedUser->user->id }}">
                                    <input type="hidden" name="eventId" value="{{ $event->id }}">
                                    <input type="hidden" name="action" value="invitation">
                                    <button type="submit" class="btn btn-outline-primary">Invite</button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="card m-3">
        <div class="card-body text-center">
            <h4 class="card-title">No users</h4>
            <p class="card-text">There are currently no users</p>
        </div>
    </div>
@endif