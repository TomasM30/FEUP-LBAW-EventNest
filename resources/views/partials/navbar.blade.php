@extends('layouts.app')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#"><img src="{{ asset('images/Logo.png') }}" alt="EventNest" id='logonav'></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mt-auto">
            <li class="nav-item">
                <a class="btn btn-primary btn-block" href="#">Events</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary btn-block" href="#">My Events</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary btn-block" href="#">Profile</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary btn-block" href="#">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary btn-block" href="#">Logout</a>
            </li>
        </ul>
    </div>
</nav>

@endsection