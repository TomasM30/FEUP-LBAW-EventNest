<div class="modal" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="tagModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tagModalLabel">Add a new Tag</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('tag.add') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="hashtag">Hashtag</label>
                        <input type="text" class="form-control" id="hashtag" name="hashtag" placeholder="Enter new hashtag">
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Add Tag</button>
                </form>
            </div>
        </div>
    </div>
</div>