@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
                <a class="nav-link active" id="v-pills-users-tab" href="#v-pills-users">Users</a>
                <a class="nav-link" id="v-pills-events-tab" href="#v-pills-events">Events</a>
                <a class="nav-link" id="v-pills-Reports-tab" href="#v-pills-Reports">Reports</a>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-users">
                    @if (!$users->isEmpty())
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover mx-auto" style="max-width: 1000px;">                               
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th class="text-right">Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $authenticatedUser)
                                            <tr>
                                                <td>
                                                    <a class="text-decoration-none" href="{{ route('user.events', $authenticatedUser->user->id) }}">{{ $authenticatedUser->user->username }}</a>
                                                </td>
                                                <td class="text-right">
                                                    {{ $authenticatedUser->user->name }}
                                                </td>
                                            </tr>
                                        @endforeach
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
                </div>
                <div class="tab-pane fade" id="v-pills-events"> 
                    @if (!$events->isEmpty())                   
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover mx-auto" style="max-width: 1000px;"> 
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th class="text-center">Organizer</th>
                                            <th class="text-right">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($events as $event)
                                            <tr>
                                                <td>
                                                    <a class="text-decoration-none text-truncate" style="max-width: 150px; display: inline-block;" href="{{ route('events.details', $event->id) }}">
                                                        {{ $event->title }}
                                                    </a>
                                                </td>
                                                <td class="text-center">{{ $event->user->username }}</td>
                                                <td class="text-right">{{ \Carbon\Carbon::parse($event->date)->format('d/m/y') }}</td>
                                            </tr>
                                        @endforeach
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
                </div>
                <div class="tab-pane fade" id="v-pills-Reports">
                    @if (!$reports->isEmpty())
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover mx-auto" style="max-width: 1000px;">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th class="text-center">Author</th>
                                            <th class="text-right">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td>
                                                <span class="badge {{ $report->closed ? 'badge-danger' : 'badge-success' }}">{{ $report->closed ? 'Closed' : 'Open' }}</span>
                                                <a class="text-decoration-none text-truncate" href="{{ route('report.details', $report->id) }}">{{ $report->title }}</a>
                                            </td>
                                            <td class="text-center">{{ $report->user->username }}</td>
                                            <td class="text-right">{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/y') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="card m-3">
                            <div class="card-body text-center">
                                <h4 class="card-title">No reports</h4>
                                <p class="card-text">There are currently no reports</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection