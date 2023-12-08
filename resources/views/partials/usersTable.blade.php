@if (!$users->isEmpty())
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-hover mx-auto" style="max-width: 1000px;">                               
                <thead>
                    <tr>
                        <th >Username</th>
                        <th class="text-right">Name</th>
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
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-center">
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