@extends('layouts.app')

@section('content')
<div class="col mt-3 justify-content-center">
    <div class="col-12 col-md-3 mb-3 mb-md-0 mx-auto">
        <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
            <a class="nav-link pill-link no-js " href="{{ route('dashboard') }}">Statistics</a>
            <a class="nav-link pill-link no-js active" href="{{ route('admin.users') }}">Users</a>
            <a class="nav-link pill-link no-js" href="{{ route('admin.events') }}">Events</a>
            <a class="nav-link pill-link no-js" href="{{ route('admin.reports') }}">Reports</a>
            <a class="nav-link pill-link no-js" href="{{ route('admin.tags') }}">Tags</a>
        </div>
        <div class="form-outline mt-3 d-flex justify-content-center" id="search-form" data-url="{{ route('admin-search-users') }}" style="width: 75%;">
            <input type="search" id="adminsearch" class="form-control mx-auto" placeholder="Search" aria-label="Search" />
        </div>
    </div>
</div>
<div class="col-12 mt-3 usersTable">
    @include('partials.usersTable', ['users' => $users])
</div>
@endsection