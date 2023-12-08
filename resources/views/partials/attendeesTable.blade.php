<div class="attendees-list mt-5">
    <h3 style="overflow-wrap: break-word;" >Attendees:</h3>
    <div class="table-responsive">
        <table class="table table-hover" style="border: 0;">
            <thead>
                <tr>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendees as $attendee)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('user.profile', $attendee->user->id) }}" style="text-decoration: none; color: inherit;">
                                <div style="display: flex; align-items: center;">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background-image: url('{{ $event->user->getProfileImage() }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                                    <p class="ml-3 mr-1" style="margin: 0; padding: 0;">{{ $attendee->user->username }}</p>
                                    @if($attendee->user->authenticated->is_verified == 1)
                                        <i class="fa-solid fa-circle-check"></i>
                                    @endif
                                </div>
                            </a>      
                        </div>
                    </td>
                </tr>
                @endforeach
                <tr style="border-bottom: none;">
                    <td style="border-bottom: none;">
                        {{ $attendees->links('partials.pagination') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>