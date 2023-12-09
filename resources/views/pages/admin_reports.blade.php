@extends('layouts.app')

@section('content')
<div class="col mt-3 justify-content-center">
    <div class="col-12 col-md-3 mb-3 mb-md-0 mx-auto">
        <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
            <a class="nav-link pill-link no-js" href="{{ route('admin.users') }}">Users</a>
            <a class="nav-link pill-link no-js" href="{{ route('admin.events') }}">Events</a>
            <a class="nav-link pill-link no-js active" href="{{ route('admin.reports') }}">Reports</a>
            <a class="nav-link pill-link no-js" href="{{ route('admin.tags') }}">Tags</a>
        </div>
    </div>
</div>
<div class="col-12 mt-3">
    @include('partials.reportsTable', ['reports' => $reports])
</div>
@endsection