<div class="modal" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report a Problem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('events.report', ['id' => $event->id]) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="title" class="form-label mt-4">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                        @if ($errors->has('title'))
                            @foreach ($errors->get('title') as $error)
                                <div class="alert alert-dismissible alert-danger">
                                    <strong>Oh snap!</strong> {{ $error }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="content" class="form-label mt-4">Problem Description</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                        @if ($errors->has('content'))
                            @foreach ($errors->get('content') as $error)
                                <div class="alert alert-dismissible alert-danger">
                                    <strong>Oh snap!</strong> {{ $error }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="file">
                            File Attachment
                        </label>
                        <input class="form-control" id="file" name="file" type="file">
                        @if ($errors->has('file'))
                            @foreach ($errors->get('file') as $error)
                                <div class="alert alert-dismissible alert-danger">
                                    <strong>Oh snap!</strong> {{ $error }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Submit Report</button>
                </form>
            </div>
        </div>
    </div>
</div>