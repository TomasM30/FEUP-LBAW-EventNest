@extends('layouts.app')

@section('content')

<div class="content-container">
    <div class="form-outline" data-mdb-input-init>
        <input type="search" id="form1" class="form-control" placeholder="Search" aria-label="Search" />
    </div>

    <div id="main">
        @foreach ($events as $event)
            @include('partials.event', ['event' => $event])
        @endforeach
    </div>
</div>
@endsection