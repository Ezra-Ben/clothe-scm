<div class="chat-thread mb-3" style="height: 70vh; overflow-y: auto; border-bottom: 1px solid #ddd;">
    @foreach($messages->reverse() as $msg)
        <div class="mb-3">
            <div class="d-flex {{ $msg->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                <div class="p-2 rounded {{ $msg->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light text-dark' }}" style="max-width: 60%;">
                    <strong>{{ $msg->sender->name }}</strong><br>
                    {{ $msg->message }}
                    <div class="text-end small mt-1 text-muted">
                        {{ $msg->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<form method="POST" id="messageForm">
    @csrf
    <div class="input-group">
        <input type="text" class="form-control" name="message" id="messageInput" placeholder="Type a message..." required>
        <button class="btn btn-outline-primary" type="submit">Send</button>
    </div>
</form>
