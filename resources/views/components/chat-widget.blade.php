{{-- resources/views/components/chat-widget.blade.php --}}
<div id="chat-widget" data-conversation-id="{{ $conversationId ?? '' }}" 
style="position:fixed; bottom:30px; right:30px; z-index:9999; width:350px; max-width:90vw; box-shadow: 0 0 10px rgba(0,0,0,0.2); border-radius: 8px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <strong>Chat</strong>
            <button type="button" class="btn-close float-end" onclick="document.getElementById('chat-widget').style.display='none'"></button>
        </div>
        <div class="card-body" id="chat-messages" style="block-size:250px;overflow-y:auto;">
            {{-- Messages will be loaded here by JS --}}
        </div>
        <div class="card-footer">
            <form id="chat-form">
                <div class="input-group">
                    <input type="text" id="chat-input" class="form-control" placeholder="Type a message...">
                    <button class="btn btn-primary" type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>