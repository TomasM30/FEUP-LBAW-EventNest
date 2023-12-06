@extends('layouts.app')

@section('content')
<div class="container">
    <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Report #{{ $report->id }}</div>
                <div class="card-body">
                    <h5>{{ $report->title }}</h5>
                    <div class="mt-3">
                        <p>Event: {{ $report->event->title }}</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background-image: url('{{ $report->user->getProfileImage() }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                            <p class="ml-3" style="margin: 0; padding: 0;">{{ $report->user->username }}</p>
                        </div>
                        <p>{{ \Carbon\Carbon::parse($report->created_at)->format('d M Y, H:i') }}</p>

                    </div>
                    <div class="mt-3 p-3 border">
                        <p>{{ $report->content }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection