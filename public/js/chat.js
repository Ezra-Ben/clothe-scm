document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.bg-light');
    const chatContent = document.getElementById('chat-content');
    const modalForm = document.querySelector('#newConversationModal form');

    // Helper to load chat messages
    function loadConversation(convId) {
        fetch(`/chat/${convId}`)
            .then(res => res.text())
            .then(html => {
                chatContent.innerHTML = html;

                const thread = chatContent.querySelector('.chat-thread');
                if (thread) thread.scrollTop = thread.scrollHeight;

                attachMessageForm(convId);
            });
    }

    // Attach event to conversation items
    sidebar.addEventListener('click', function (e) {
        const chatItem = e.target.closest('.chat-item');
        if (!chatItem) return;

        const id = chatItem.dataset.id;
        loadConversation(id);
    });

    // Attach form submit handler
    function attachMessageForm(conversationId) {
        const form = document.getElementById('messageForm');
        const input = document.getElementById('messageInput');
        const container = document.getElementById('chat-content').querySelector('.chat-thread');

        if (!form) return;

        if (form._submitHandler) {
            form.removeEventListener('submit', form._submitHandler);
        }

        form._submitHandler = function (e) {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            form.querySelector('button').disabled = true;

            fetch(`/chat/${conversationId}/send`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
                                    <strong>${window.CurrentUserRole}</strong><br>
                                    ${data.message}
                                    <div class="text-end small mt-1 text-muted">Just now</div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', newMessage);
                    input.value = '';
                    container.scrollTop = container.scrollHeight;
                })
                .catch(err => console.error('Send message failed:', err))
                .finally(() => {
                    form.querySelector('button').disabled = false;
                });
        };

        form.addEventListener('submit', form._submitHandler);
    }

    // Handle starting a new conversation
    if (modalForm) {
        modalForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const userId = this.querySelector('#user_id').value;

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ user_id: userId })
            })
                .then(res => res.json())
                .then(data => {
                    const convId = data.conversation_id;

                    // Load conversation content
                    loadConversation(convId);

                    // Fetch conversation meta info for sidebar
                    fetch(`/conversations/${convId}/info`)
                        .then(res => res.json())
                        .then(conv => {
                            // Prevent duplicates
                            if (sidebar.querySelector(`.chat-item[data-id="${conv.id}"]`)) return;

                            const otherUser = conv.user_one_id === window.CurrentUserId
                                ? conv.user_two
                                : conv.user_one;

                            const newItem = document.createElement('div');
                            newItem.className = 'chat-item py-2 px-3 mb-2 border rounded bg-white';
                            newItem.dataset.id = conv.id;
                            newItem.style.cursor = 'pointer';
                            newItem.innerHTML = `
                                <div class="fw-bold">${otherUser.name}</div>
                                <small class="text-muted">Just now</small>
                            `;

                            const hr = sidebar.querySelector('hr');
                            sidebar.insertBefore(newItem, hr);

                            // Attach click event
                            newItem.addEventListener('click', () => loadConversation(conv.id));
                        });
                })
                .catch(err => console.error('Failed to create conversation:', err));
        });
    }
});
