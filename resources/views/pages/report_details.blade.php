@extends('layouts.app')

@section('content')
<div class="container">
    <div class="container" style="padding-top: 50px;">
        <div class="row">
            <div class="col-md-4">
                <div class="list-group" style="max-height: calc((1.5em + .75rem + 2px) * 3); overflow-y: auto;" id="reportList">
                    @foreach($reports as $reported)
                        <a href="{{ route('report.details', $reported->id) }}" class="list-group-item list-group-item-action{{ $reported->id == $report->id ? ' active' : '' }}" style="max-width: 250px;">
                            Report #{{ $reported->id }} - {{ Str::limit($reported->title, 20, '...') }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        Report #{{ $report->id }}
                        <span class="badge {{ $report->closed ? 'badge-danger' : 'badge-success' }}">{{ $report->closed ? 'Closed' : 'Open' }}</span>
                    </div>
                    <div class="card-body">
                        <h4>{{ $report->title }}</h4>
                        <div class="mt-3 mb-3">
                            <a href="{{ route('events.details', $report->event->id) }}">{{ Str::limit($report->event->title, 45, '...') }}</a>
                        </div>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center"  style=" overflow-wrap:break-word;">
                            @if($report->user)
                                <div class="d-flex align-items-center mb-2 mb-md-0">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background-image: url('{{ $report->user->getProfileImage() }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                                    <p class="ml-3" style="margin: 0; padding: 0; word-break: break-word;">{{ $report->user->username }}</p>
                                </div>
                            @else
                                <div class="alert alert-dismissible alert-danger">
                                    <p class="ml-3" style="margin: 0; padding: 0; color: red; font-weight: bold;">User deleted</p>                            
                                </div>
                            @endif
                            <p class="text-md-right mb-0">{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y, H:i') }}</p>
                        </div>
                        <div class="mt-3 p-3 border">
                            <p>{{ $report->content }}</p>
                        </div>
                        @if($report->file)
                        <div class="mt-3">
                            <a href="{{ asset('report/' . $report->file) }}" target="_blank" style="text-decoration: none;">
                                <i class="fas fa-file-alt"></i> {{ basename($report->file) }}
                            </a>
                        </div>
                        @endif
                        @if($report->notification)
                            <form method="POST" action="{{ route('notification.delete', $report->notification->id) }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button class="btn btn-danger mt-3" type="submit">Close Report</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection