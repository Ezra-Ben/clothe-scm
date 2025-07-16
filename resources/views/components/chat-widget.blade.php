<div id="chat-widget" style="display:none; position:fixed; inset-block-end:100px; inset-inline-end:30px; z-index:10000; inline-size:350px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <span id="chat-with-label"></span>
            <button type="button" class="btn-close float-end" onclick="document.getElementById('chat-widget').style.display='none'"></button>
        </div>
        <div class="card-body" id="chat-messages" style="block-size:300px; overflow-y:auto;">
            <!-- Messages will be loaded here by JS -->
        </div>
        <div class="card-footer">
            <form id="chat-form" autocomplete="off">
                <div class="input-group">
                    <input type="text" id="chat-input" class="form-control" placeholder="Type a message..." required>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>