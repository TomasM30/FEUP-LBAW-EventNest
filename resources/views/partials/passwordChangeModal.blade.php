<div class="modal" id="passwordChangeModal" tabindex="-1" role="dialog" aria-labelledby="newEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
                <h5 class="modal-title" id="passwordChangeLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('user.password.update', ['id' => Auth::user()->id]) }}" enctype="multipart/form-data">                    
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                        @if ($errors->has('current_password'))
                            @foreach ($errors->get('current_password') as $error)
                                <div class="alert alert-dismissible alert-danger">
                                    <strong>Oh snap!</strong> {{ $error }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        @if ($errors->has('new_password'))
                            @foreach ($errors->get('new_password"') as $error)
                                <div class="alert alert-dismissible alert-danger">
                                    <strong>Oh snap!</strong> {{ $error }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        @if ($errors->has('new_password_confirmation'))
                            @foreach ($errors->get('new_password_confirmation') as $error)
                                <div class="alert alert-dismissible alert-danger">
                                    <strong>Oh snap!</strong> {{ $error }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>