
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
            @if($user->id !== auth()->id())
              <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $user->name }}
                <form method="POST" action="{{ route('chat.start') }}">
                  @csrf
                  <input type="hidden" name="user_id" value="{{ $user->id }}">
                  <button type="submit" class="btn btn-sm btn-primary">Start Chat</button>
                </form>
              </li>
            @endif
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>