<div class="modal" id="inviteUserModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="userModalLabel">Invite Users</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
          </div>
          <input type="text" class="form-control" id="inviteSearch" data-event-id="{{ $event->id }}" placeholder="Search users...">
        </div>
        @include('partials.eventInviteTable')
      </div>
    </div>
  </div>
</div>