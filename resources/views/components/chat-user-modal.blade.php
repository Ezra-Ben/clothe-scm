
<div class="modal fade" id="chatUserModal" tabindex="-1" aria-labelledby="chatUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="chatUserModalLabel">Start a New Chat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          @foreach($users as $user)
              <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>{{ $user->name }} <small class="text-muted">({{ $user->role->label ?? $user->role->name }})</small></span>
                <button type="button"
                    class="btn btn-sm btn-primary start-chat-btn"
                    data-user-id="{{ $user->id }}">
                      Start Chat
                </button>
              </li>
            
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>