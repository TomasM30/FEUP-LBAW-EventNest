@if (!$users->isEmpty())
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-hover mx-auto" style="max-width: 1000px;">                               
                <thead>
                    <tr>
                        <th>Username</th>
                        <th class="text-right">Name</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $authenticatedUser)
                        <tr>
                            <td>
                                <a class="text-decoration-none" style="text-decoration: none; color: inherit;" href="{{ route('user.events', $authenticatedUser->user->id) }}">{{ $authenticatedUser->user->username }}</a>
                            </td>
                            <td class="text-right">
                                {{ $authenticatedUser->user->name }}
                            </td>
                            <td class="text-right">
                                @if($authenticatedUser->is_blocked == false)
                                    <form method="POST" action="{{ route('user.block', ['id' => $authenticatedUser->user->id]) }}">
                                        {{ csrf_field() }}
                                        <button type="submit" id="blockUserBtn" class="btn btn-danger btn-sm">
                                            <i class="fas fa-ban"></i> Block User
                                        </button>
                                    </form>
                                @endif

                                @if($authenticatedUser->is_blocked == true)
                                    <form method="POST" action="{{ route('user.unblock', ['id' => $authenticatedUser->user->id]) }}">  
                                        {{ csrf_field() }}
                                        <button type="submit" id="unblockUserBtn" class="btn btn-success btn-sm">
                                            <i class="fas fa-user-check"></i> Unblock User
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-center">
                            {{ $users->links('partials.pagination') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="card m-3">
        <div class="card-body text-center">
            <h4 class="card-title">No users</h4>
            <p class="card-text">There are currently no users</p>
        </div>
    </div>
@endif