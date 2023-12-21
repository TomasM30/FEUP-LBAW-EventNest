<div class="modal" id="deleteAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the account? This action cannot be undone.</p>
                <form method="POST" action="{{ route('user.delete', ['id' => $user->id]) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-danger mt-4">Delete Account</button>
                </form>
            </div>
        </div>
    </div>
</div>