@extends('layouts.app')

@section('content')

<div class="content-container">
    <div class="form-outline" data-mdb-input-init>
        <input type="search" id="form1" class="form-control" placeholder="Search" aria-label="Search" />
        <div class="filters">
            <button id='location-button' type="submit" class="btn btn-custom btn-block">Location</button>
            <button id='date-button' type="submit" class="btn btn-custom btn-block">Date</button>
            <button id='tag-button' type="submit" class="btn btn-custom btn-block">Tag</button>
            <button id='NEvent-button' type="button" class="btn btn-custom btn-block" data-toggle="modal" data-target="#newEventModal">New Event</button>        </div>
    </div>

    <div id="main">
        @foreach ($events as $event)
        @include('partials.event', ['event' => $event])
        @endforeach
    </div>
</div>

<div class="modal fade" id="newEventModal" tabindex="-1" role="dialog" aria-labelledby="newEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newEventModalLabel">New Event</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
            <div class="form-row">
                <div class="col">
                <input type="text" class="form2-control" placeholder="First name">
                </div>
                <div class="col">
                <input type="text" class="form2-control" placeholder="Last name">
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                <input type="text" class="form2-control" placeholder="First name">
                </div>
                <div class="col">
                <input type="text" class="form2-control" placeholder="Last name">
                </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection