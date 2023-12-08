@if (!$events->isEmpty())                   
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-hover mx-auto" style="max-width: 1000px;"> 
                <thead>
                    <tr>
                        <th style="width: 33%;">Title</th>
                        <th class="text-center" style="width: 33%;">Organizer</th>
                        <th class="text-right" style="width: 33%;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>
                                <a class="text-decoration-none text-truncate" style=" text-decoration: none; color: inherit; max-width: 150px; display: inline-block;" href="{{ route('events.details', $event->id) }}">
                                    {{ $event->title }}
                                </a>
                            </td>
                            <td class="text-center">
                                @if($event->user)
                                <a class="text-decoration-none text-truncate" style=" text-decoration: none; color: inherit; max-width: 150px; display: inline-block;" href="{{ route('user.profile', $event->user->id) }}">
                                    {{ $event->user->username }}
                                </a>
                                @else
                                    <strong style="color: red; text-decoration: line-through;">USER DELETED</strong> 
                                @endif
                            </td>
                            <td class="text-right">{{ \Carbon\Carbon::parse($event->date)->format('d/m/y') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-center">
                            {{ $events->links('partials.pagination', ['ajax' => true]) }}
                        </td>
                    </tr>
                </tbody>           
            </table>
        </div>
    </div>
@else
    <div class="card m-3">
        <div class="card-body text-center">
            <h4 class="card-title">No events</h4>
            <p class="card-text">There are currently no events</p>
        </div>
    </div>
@endif