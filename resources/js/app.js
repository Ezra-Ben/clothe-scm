
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
});
const chatWidget = document.getElementById('chat-widget');
const conversationId = chatWidget?.dataset.conversationId;

if (conversationId) {
window.Echo.private('conversation.' + conversationId)
    .listen('MessageSent', (e) => {
       const messagesDiv = document.getElementById('chat-messages');
            const msg = document.createElement('div');
            msg.innerHTML = `<strong>${e.message.sender.name}</strong>: ${e.message.message} <small>${e.message.created_at}</small>`;
            messagesDiv.appendChild(msg);
        });


    // Load existing messages
    fetch(`/chat/conversation/${conversationId}`)
        .then(res => res.json())
        .then(data => {
            const messagesDiv = document.getElementById('chat-messages');
            messagesDiv.innerHTML = '';
            data.messages.forEach(m => {
                const msg = document.createElement('div');
                msg.innerHTML = `<strong>${m.sender}</strong>: ${m.message} <small>${m.created_at}</small>`;
                messagesDiv.appendChild(msg);
    });
        });
document.getElementById('chat-form')?.addEventListener('submit', function(e){
    e.preventDefault();
    const input = document.getElementById('chat-input');
    const message = input.value;
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
        
    })
    .catch(() => alert('Failed to send message.'));
    
});


} 

