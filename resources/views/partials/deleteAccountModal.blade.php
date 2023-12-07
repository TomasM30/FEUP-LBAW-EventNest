<div class="modal" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                <form method="POST" action="{{ route('user.delete', ['id' => Auth::user()->id]) }}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-danger mt-4">Delete Account</button>
                </form>
            </div>
        </div>
    </div>
</div>