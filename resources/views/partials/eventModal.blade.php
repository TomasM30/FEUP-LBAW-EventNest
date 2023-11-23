<div class="modal" id="newEventModal" tabindex="-1" role="dialog" aria-labelledby="newEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="newEventModalLabel">
                @if($formAction == route('events.edit', $event->id))
                    Edit Event
                @else
                    Create Event
                @endif
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ $formAction }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Event title" @if($formAction != route('events.edit', $event->id)) required @endif>
                    @if ($errors->has('title'))
                        <span class="error">
                            {{ $errors->first('title') }}
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" @if($formAction != route('events.edit', $event->id)) required @endif>

                    </textarea>
                    @if ($errors->has('description'))
                        <span class="error">
                            {{ $errors->first('description') }}
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type" class="form-control" @if($formAction != route('events.edit', $event->id)) required @endif>
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                        <option value="approval">Approval</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" id="date" name="date"  @if($formAction != route('events.edit', $event->id)) required @endif>
                    @if ($errors->has('date'))
                        <span class="error">
                            {{ $errors->first('date') }}
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="capacity">Capacity</label>
                    <input type="number" class="form-control" id="capacity" name="capacity"  @if($formAction != route('events.edit', $event->id)) required @endif>
                    @if ($errors->has('capacity'))
                        <span class="error">
                            {{ $errors->first('capacity') }}
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="ticket_limit">Ticket Limit</label>
                    <input type="number" class="form-control" id="ticket_limit" name="ticket_limit"  @if($formAction != route('events.edit', $event->id)) required @endif>
                    @if ($errors->has('ticket_limit'))
                        <span class="error">
                            {{ $errors->first('ticket_limit') }}
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="place">Place</label>
                    <input type="text" class="form-control" id="place" name="place"  @if($formAction != route('events.edit', $event->id)) required @endif>
                    @if ($errors->has('place'))
                        <span class="error">
                            {{ $errors->first('place') }}
                        </span>
                    @endif
                </div>
                    <button type="submit" class="btn btn-custom">
                        @if($formAction == route('events.edit', $event->id))
                            Edit Event
                        @else
                            Create Event
                        @endif
                    </button>
            </form>
        </div>
        </div>
    </div>
</div>