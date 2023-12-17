@if (!$users->isEmpty())
    <div class="table-responsive" style="height: 500px; overflow-y: auto;">
        <table class="table table-hover mx-auto" id="userTable" style="max-width: 1000px;">                                
            <thead>
                <tr>
                    <th>Username</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $authenticatedUser)
                    <tr>
                        <td>
                            <p style="text-decoration: none; color: inherit;">{{ $authenticatedUser->user->username }}</p>
                        </td>
                        <td class="text-right">
                            @if($event->eventparticipants()->where('id_user', $authenticatedUser->user->id)->exists())
                                <form method="POST" action="{{ route('events.remove', $event->id) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_user" value="{{ $authenticatedUser->user->id }}">
                                    <input type="hidden" name="eventId" value="{{ $event->id }}">
                                    <button type="submit" class="btn btn-danger removeUser">Remove</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('events.add', $event->id) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_user" value="{{ $authenticatedUser->user->id }}">
                                    <input type="hidden" name="eventId" value="{{ $event->id }}">
                                    <button type="submit" class="btn btn-success addUser" data-user-id="{{ $authenticatedUser->user->id }}">Add</button>
                                </form>
                            @endif
                        </td>
                    </tr>
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