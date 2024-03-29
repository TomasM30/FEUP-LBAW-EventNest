<div class="modal" id="cancelEventModal" tabindex="-1" role="dialog" aria-labelledby="cancelEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEventModalLabel">Cancel Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this event? This action cannot be undone.</p>
                <form method="POST" action="{{ route('events.cancel', $event->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="eventId" value="{{ $event->id }}">
                    <div class="d-flex">
                        <button type="submit" class="btn btn-danger">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>