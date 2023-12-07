@extends('layouts.app')

@section('content')
<div class="container">
    <div class="container" style="padding-top: 50px;">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">Report #{{ $report->id }}</div>
                <div class="card-body">
                    <h4>{{ $report->title }}</h4>
                    <div class="mt-3 mb-3">
                        <a href="{{ route('events.details', $report->event->id) }}">{{ Str::limit($report->event->title, 45, '...') }}</a>
                    </div>
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center"  style=" overflow-wrap:break-word;">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background-image: url('{{ $report->user->getProfileImage() }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                            <p class="ml-3" style="margin: 0; padding: 0; word-break: break-word;">{{ $report->user->username }}</p>
                        </div>
                        <p class="text-md-right mb-0">{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y, H:i') }}</p>
                    </div>
                    <div class="mt-3 p-3 border">
                        <p>{{ $report->content }}</p>
                    </div>
                    @if($report->file)
                    <div class="mt-3">
                        <a href="{{ asset('report/' . $report->file) }}" target="_blank" style="text-decoration: none;">
                            &#128196; {{ basename($report->file) }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection