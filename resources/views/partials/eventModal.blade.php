<div class="modal" id="newEventModal" tabindex="-1" role="dialog" aria-labelledby="newEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newEventModalLabel">
                    @if(isset($event))
                        @if($formAction == route('events.edit', $event->id))
                            Edit Event
                        @else
                            Create Event
                        @endif
                    @else
                        Create Event
                    @endif
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <fieldset>
                        @if(auth::user()->authenticated->is_verified == 1)
                            <div class="form-group">
                                <label for="event_type">Event Type</label>
                                <select id="event_type" name="event_type" class="form-control" style="height: 3em" required>
                                    <option value="free" selected>Free to Join</option>
                                    <option value="tickets">Tickets Required</option>
                                </select>
                            </div>
                            <div id="ticketFields" style="display: none;">
                                <div class="form-group">
                                    <label for="ticket_limit">Ticket Limit by User</label>
                                    <input type="number" class="form-control" id="ticket_limit" name="ticket_limit" placeholder="How many tickets per user">
                                    @if ($errors->has('ticket_limit'))
                                        @foreach ($errors->get('ticket_limit') as $error)
                                            <div class="alert alert-dismissible alert-danger">
                                                <strong>Oh snap!</strong> {{ $error }}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="ticket_price">Ticket Price</label>
                                    <input type="number" class="form-control" id="ticket_price" name="ticket_price" placeholder="price of each ticket">
                                    <p>Specify the price for each ticket.</p>
                                    @if ($errors->has('ticket_price'))
                                        @foreach ($errors->get('ticket_price') as $error)
                                            <div class="alert alert-dismissible alert-danger">
                                                <strong>Oh snap!</strong> {{ $error }}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="form-group" id="event_type">
                            <label for="title" class="form-label mt-4">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Event title" @if(isset($event) && $formAction != route('events.edit', $event->id)) required @endif>
                            @if ($errors->has('title'))
                                @foreach ($errors->get('title') as $error)
                                    <div class="alert alert-dismissible alert-danger">
                                        <strong>Oh snap!</strong> {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label mt-4">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Event description" @if(isset($event) && $formAction != route('events.edit', $event->id)) required @endif></textarea>
                            @if ($errors->has('description'))
                                @foreach ($errors->get('description') as $error)
                                    <div class="alert alert-dismissible alert-danger">
                                        <strong>Oh snap!</strong> {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div id="typeEvent" class="form-group">
                            <label for="type">Type</label>
                            <select id="type" name="type" class="form-control" style="height: 3em" @if(isset($event) && $formAction != route('events.edit', $event->id)) required @endif>
                            <option value="" disabled hidden>Select visibility</option>
                                <option value="public" @if(isset($event) && $event->type == 'public') selected @endif>Public</option>
                                <option value="private" @if(isset($event) && $event->type == 'private') selected @endif>Private</option>
                                <option value="approval" @if(isset($event) && $event->type == 'approval') selected @endif>Approval</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date"  @if(isset($event) && $formAction != route('events.edit', $event->id)) required @endif>
                            @if ($errors->has('date'))
                                @foreach ($errors->get('date') as $error)
                                    <div class="alert alert-dismissible alert-danger">
                                        <strong>Oh snap!</strong> {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="capacity">Capacity</label>
                            <input type="number" class="form-control" id="capacity" placeholder="Event Capacity" name="capacity"  @if(isset($event) && $formAction != route('events.edit', $event->id)) required @endif>
                            @if ($errors->has('capacity'))
                                @foreach ($errors->get('capacity') as $error)
                                    <div class="alert alert-dismissible alert-danger">
                                        <strong>Oh snap!</strong> {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="place">Place</label>
                            <input type="text" placeholder="Place where event will be held" class="form-control" id="place" name="place"  @if(isset($event) && $formAction != route('events.edit', $event->id)) required @endif>
                            @if ($errors->has('place'))
                                @foreach ($errors->get('place') as $error)
                                    <div class="alert alert-dismissible alert-danger">
                                        <strong>Oh snap!</strong> {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Hashtags</h5>
                            </div>
                            <div class="card-body hashtags-body">
                                @foreach ($hashtags as $hashtag)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="hashtags2[]" value="{{ $hashtag->id }}" id="hashtag02{{ $hashtag->id }}">
                                        <label class="form-check-label" for="hashtag02{{ $hashtag->id }}">
                                            #{{ $hashtag->title }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group mt-5">
                            <label for="file">Event Photo</label>
                            <p>
                                @if(isset($event) && $formAction != route('events.edit', $event->id))
                                    Choose a photo or leave empty to use the default image.
                                @endif
                            </p>
                            <input class="form-control" id="file" name="file" type="file">
                            @if ($errors->has('file'))
                                @foreach ($errors->get('file') as $error)
                                    <div class="alert alert-dismissible alert-danger">
                                        <strong>Oh snap!</strong> {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">
                            @if(isset($event) && $formAction == route('events.edit', $event->id))
                                Edit Event
                            @else
                                Create Event
                            @endif
                        </button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>