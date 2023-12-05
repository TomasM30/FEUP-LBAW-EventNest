<div class="modal" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="newEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newEventModalLabel">
                    Event Photo
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Choose a photo to use on your event.</p>
                <div class="form-group">
                    <form method="POST" action="/file/upload" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input class="form-control" name="file" type="file" required>
                        <input name="id" value="{{ $event->id }}" type="hidden">
                        <input name="type" value="event" type="hidden">
                        <button type="submit" class="btn btn-primary mt-4">
                            Upload
                        </button>
                    </form>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger mt-4" role="alert">
                        @foreach ($errors->all() as $error)
                            <strong>Oh snap!</strong> {{ $error }}
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>