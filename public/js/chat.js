document.addEventListener('DOMContentLoaded', function () {
    // Load chat when clicked
    document.querySelectorAll('.chat-item').forEach(item => {
        item.addEventListener('click', function () {
            const id = this.dataset.id;
            fetch(`/chat/${id}`)
                .then(res => res.text())
                .then(html => {
                    const container = document.getElementById('chat-content');
                    container.innerHTML = html;

                    // Scroll chat to bottom
                    const thread = container.querySelector('.chat-thread');
                    if (thread) thread.scrollTop = thread.scrollHeight;

                    // Attach message form handler
                    attachMessageForm(id);
                });
        });
    });

    function attachMessageForm(conversationId) {
        const form = document.getElementById('messageForm');
        const input = document.getElementById('messageInput');
        const container = document.querySelector('.chat-thread');

        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            fetch(`/chat/${conversationId}/send`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message })
            })
                .then(res => res.json())
                .then(data => {
                    const newMessage = `
                        <div class="mb-3">
                            <div class="d-flex justify-content-end">
                                <div class="p-2 rounded bg-primary text-white" style="max-width: 60%;">
                                    <strong>You</strong><br>
                                    ${data.message}
                                    <div class="text-end small mt-1 text-muted">Just now</div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', newMessage);
                    input.value = '';
                    container.scrollTop = container.scrollHeight;
                });
        });
    }
});
