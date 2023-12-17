<div class="modal" id="deleteEventModal" tabindex="-1" role="dialog" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEventModalLabel">Delete Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this event? This action cannot be undone.</p>
                <form method="POST" action="{{ route('events.delete', ['id' => $event->id]) }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="hidden" name="id" value="{{ $event->id }}">
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>