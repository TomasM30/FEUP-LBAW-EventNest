<div class="modal" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="newEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('user.profile.update', ['id' => Auth::user()->id]) }}" enctype="multipart/form-data">
                   {{ csrf_field() }}
                    <div class="form-group">
                        <label for="username" class="form-label mt-4">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ Auth::user()->username }}" required>
                        @if ($errors->has('username'))
                            <div class="alert alert-dismissible alert-danger">
                                <strong>Oh snap!</strong> {{ $errors->first('username') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label mt-4">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                        @if ($errors->has('name'))
                            <div class="alert alert-dismissible alert-danger">
                                <strong>Oh snap!</strong> {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label mt-4">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                        @if ($errors->has('email'))
                            <div class="alert alert-dismissible alert-danger">
                                <strong>Oh snap!</strong> {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="file">Profile Picture</label>
                        <input class="form-control" id="file" name="file" type="file">
                        @if ($errors->has('file'))
                            <div class="alert alert-dismissible alert-danger">
                                <strong>Oh snap!</strong> {{ $errors->first('file') }}
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Edit Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>