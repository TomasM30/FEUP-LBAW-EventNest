@extends('layouts.app')

@section('content')
<div id="box">
    <div class="card border border-primary shadow-0 ">
        <h5 class="card-title tag">TAG</h5>
        <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
            <img src="https://mdbootstrap.com/img/new/standard/nature/111.webp" class="img-fluid" />
            <a href="#!">
                <div class="mask"></div>
            </a>
        </div>

        <div class="card-body">
            <h5 class="card-title">Event Name</h5>
            <span class="card-title">Your Date</span>
            <br>
            <span class="card-title">Your Event Location</span>
        </div>
        <div class="card-footer">capacity: 100/250</div>
    </div>
</div>
@endsection