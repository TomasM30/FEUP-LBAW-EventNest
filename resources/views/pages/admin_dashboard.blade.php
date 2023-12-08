@extends('layouts.app')

@section('content')
<div class="col mt-3 justify-content-center">
    <div class="col-12 col-md-3 mb-3 mb-md-0 mx-auto">
        <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
            <a class="nav-link active" id="v-pills-users-tab" href="#v-pills-users">Users</a>
            <a class="nav-link" id="v-pills-events-tab" href="#v-pills-events">Events</a>
            <a class="nav-link" id="v-pills-Reports-tab" href="#v-pills-Reports">Reports</a>
            <a class="nav-link" id="v-pills-Tags-tab" href="#v-pills-Tags">Tags</a>
        </div>
    </div>
    <div class="col-12 mt-3">
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-users">
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
                                        <th style="width: 33%;">Title</th>
                                        <th class="text-center" style="width: 33%;">Author</th>
                                        <th class="text-right" style="width: 33%;">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td>
                                                <span class="badge {{ $report->closed ? 'badge-danger' : 'badge-success' }}">{{ $report->closed ? 'Closed' : 'Open' }}</span>
                                                <a class="text-decoration-none text-truncate" style="text-decoration: none; color: inherit;" href="{{ route('report.details', $report->id) }}">{{ $report->title }}</a>
                                            </td>
                                            <td class="text-center">
                                                @if($report->user)
                                                    <a class="text-decoration-none" style="text-decoration: none; color: inherit;" href="{{ route('user.profile', $report->user->id) }}">
                                                        {{ $report->user->username }}
                                                    </a>
                                                @else
                                                    <strong style="color: red; text-decoration: line-through;">USER DELETED</strong> 
                                                @endif
                                            </td>
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
            <div class="tab-pane fade" id="v-pills-Tags">
                @if (!$tags->isEmpty())
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover mx-auto" style="max-width: 500px;">
                                <thead>
                                    <tr>
                                        <th class="text-center">Tag</th>
                                        <th class="text-right">
                                            <a href="#" class="btn btn-success float-right"><i class="fa fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tags as $tag)
                                        <tr>
                                            <td>
                                                #{{ $tag->title }}
                                            </td>
                                            <td class="text-right">
                                                <a href="#" class="btn btn-danger"><i class="fa fa-trash"></i></a>
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
                            <h4 class="card-title">No tags</h4>
                            <p class="card-text">There are currently no tags</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection