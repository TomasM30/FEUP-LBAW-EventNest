@extends('layouts.app')

@section('content')

<div class="content-container">
    <form action="{{ route('search-events') }}" method="get">
        <div class="form-outline" data-mdb-input-init>
            <input type="search" id="form1" class="form-control" placeholder="Search" aria-label="Search" />
            <div class="filters">
                <button id='location-button' type="submit" class="btn btn-custom btn-block">Location</button>
                <button id='date-button' type="submit" class="btn btn-custom btn-block">Date</button>
                <button id='tag-button' type="submit" class="btn btn-custom btn-block">Tag</button>
            </div>
        </div>
    </form>
    <div id="main">
        @foreach ($events as $event)
            @include('partials.event', ['event' => $event])
        @endforeach
    </div>
</div>

<script>
    $('form').on('submit', function(event) {
    event.preventDefault();
    });
    $('#form1').on('keyup', function() {
        var value = $(this).val();
        $.ajax({
            type: 'get',
            url: '{{ route('search-events') }}',
            data: { 'search': value },
            success: function(data) {
                $('#main').html(data);
            }
        });
    });
</script>

@endsection