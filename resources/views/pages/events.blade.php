@extends('layouts.app')

@section('content')


@if ($errors->any())
    <script>
        showAlert('Error!', '{{ $errors->first() }}', 'error');
    </script>
@endif

@if (session('error'))
    <script>
        showAlert('Error!', '{{ session('error') }}', 'error');
    </script>
@endif

@if (session('success'))
    <script>
        showAlert('Success!', '{{ session('success') }}', 'success');
    </script>
@endif

<div class="content-container overflow-x-hidden">
    <div class="row justify-content-center mt-3">
        <div class="form-outline mt-2 d-flex align-items-center row" id="search-form" data-url="{{ route('search-events') }}" style="width: 55%;">
            <div class="col-md-8 col-sm-12">
                <input type="search" id="form1" class="form-control" placeholder="Search" aria-label="Search">
                <input type="hidden" name="type" value="main">
            </div>
            @if (App\Models\AuthenticatedUser::where('id_user', Auth::user()->id)->exists())
            <div class="col-md-4 col-sm-12 text-sm-left text-md-right">
                <button id='NEvent-button' type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#newEventModal">New Event</button>
            </div>
            @endif
        </div>
        <div class="row m-4 mb-1" style="width: 60%;">
            <div class="col-md-6">
                <div class="accordion m-4" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Order By
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <button id='date-button' class="btn btn-custom btn-block" data-direction="asc">Date</button>
                                <button id='title-button' class="btn btn-custom btn-block" data-direction="asc">Title</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 ">
                <div class="accordion m-4" id="accordionExample2">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Filter
                        </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body row">
                                <div class="col-md-6 mb-3">
                                    <h5>Hashtags</h5>
                                    <div class="card-body hashtags-body">
                                        @foreach ($hashtags as $hashtag)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="hashtags[]" value="{{ $hashtag->id }}" id="hashtag{{ $hashtag->id }}">
                                            <label class="form-check-label" for="hashtag{{ $hashtag->id }}">
                                                #{{ $hashtag->title }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Places</h5>
                                    <div class="card-body hashtags-body">
                                        @foreach ($places as $place)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="places[]" value="{{ $place->place }}" id="place{{ $place->place }}">
                                            <label class="form-check-label" for="place{{ $place->place }}">
                                                {{ $place->place }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container mt-5">
        <div id="selected-filters" class="d-flex flex-wrap mb-3"></div>
        @if (!$events->isEmpty())
            <div class="row row-cols-1 row-cols-md-3 g-4 custom-row" id="container">
                @foreach ($events as $event)
                    @include('partials.event', [
                        'event' => $event])                
                @endforeach
            </div>
        @else
            <div class="card m-3">
                <div class="card-body text-center">
                    <h4 class="card-title">No events</h4>
                    <p class="card-text">There are currently no events for YOU</p>
                </div>
            </div>
        @endif
        {{ $events->links('partials.pagination') }}

    </div>

    <div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;"></div>
    @if(!Auth::user()->isAdmin())
        @include('partials.eventModal', ['formAction' => route('events.create'), 'hashtags' => $hashtags])
    @endif
</div>

@endsection