<div class="modal fade" id="newConversationModal" tabindex="-1" aria-labelledby="newConversationLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('chat.startConversation') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newConversationLabel">Start New Conversation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="user_id" class="form-label">Select User</label>
            <select class="form-select" name="user_id" id="user_id" required>
              <option value="">-- Choose --</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role->name }})</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Start Chat</button>
        </div>
      </div>
    </form>
  </div>
</div>
