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
<script type="text/javascript">
    $('#search').on('keyup',function(){
        $value=$(this).val();
        $.ajax({
            type : 'get',
            url : '{{URL::to('search')}}',
            data:{'search':$value},
            success:function(data){
                $('tbody').html(data);
            }
        });
    })
</script>
<script type="text/javascript">
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>

@endsection