console.log('JS file loaded');
import Alpine from 'alpinejs';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Alpine = Alpine;
Alpine.start();

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    authEndpoint: '/broadcasting/auth', // Laravel's default endpoint
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    let currentEchoChannel = null;
    let currentChatUserName = null;

    // 1. Handle "Start Chat" button click in user modal
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('start-chat-btn')) {
            console.log('Start Chat clicked for user:', e.target.dataset.userId);
            const userId = e.target.dataset.userId;
            currentChatUserName = e.target.closest('li').querySelector('span').textContent;
            fetch('/chat/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ user_id: userId })
            })
            .then(res => res.json())
            .then(data => {
                // Hide user modal (Bootstrap 5)
                const modalEl = document.getElementById('chatUserModal');
                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                }

                // Set conversationId and show widget
                const chatWidget = document.getElementById('chat-widget');
                chatWidget.dataset.conversationId = data.conversation_id;
                chatWidget.style.display = 'block';
                document.getElementById('chat-with-label').textContent = 'Chat with ' + currentChatUserName;

                // Load messages for this conversation
                loadChatMessages(data.conversation_id);

                // Subscribe to real-time updates
                subscribeToConversation(data.conversation_id);
            });
        }
    });

    // 2. Handle chat form submit (send message)
    document.getElementById('chat-form')?.addEventListener('submit', function(e){
        e.preventDefault();
        const input = document.getElementById('chat-input');
        const message = input.value;
        const chatWidget = document.getElementById('chat-widget');
        const conversationId = chatWidget?.dataset.conversationId;
        if (!message.trim() || !conversationId) return;

        fetch(`/chat/conversation/${conversationId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message })
        })
        .then(res => res.json())
        .then(data => {
            input.value = '';
            appendMessage('You', data.message, data.created_at, true);
        })
        .catch(() => alert('Failed to send message.'));
    });

    // 3. Load previous messages for a conversation
    function loadChatMessages(conversationId) {
        fetch(`/chat/${conversationId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => {
                const messagesDiv = document.getElementById('chat-messages');
                messagesDiv.innerHTML = '';
                data.messages.forEach(m => {
                    appendMessage(m.sender, m.message, m.created_at, m.sender === 'You');
                });
            });
    }

    // 4. Subscribe to real-time updates for the conversation
    function subscribeToConversation(conversationId) {
        if (currentEchoChannel) {
            window.Echo.leave(currentEchoChannel);
        }
        currentEchoChannel = 'private-conversation.' + conversationId;
        window.Echo.private('conversation.' + conversationId)
            .listen('MessageSent', (e) => {
                appendMessage(e.message.sender.name, e.message.message, e.message.created_at, false);
                // Alert for new incoming messages
                if (e.message.sender.name !== 'You') {
                    alert('New message from ' + e.message.sender.name + ': ' + e.message.message);
                }
            });
    }

    // Helper to append message
    function appendMessage(sender, message, createdAt, isMine) {
        const messagesDiv = document.getElementById('chat-messages');
        const msg = document.createElement('div');
        msg.className = 'mb-2 ' + (isMine ? 'text-end' : 'text-start');
        msg.innerHTML = `<strong>${sender}</strong>: ${message} <small>${createdAt}</small>`;
        messagesDiv.appendChild(msg);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }
});