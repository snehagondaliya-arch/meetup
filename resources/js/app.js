import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import axios from 'axios';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

const el = {
    messages: document.getElementById('messages'),
    form: document.getElementById('message-form'),
    input: document.getElementById('message-input')
};

// Load messages
axios.get('/messages').then(r => {
    el.messages.innerHTML = '';
    r.data.forEach(renderMessage);
});

// window.Echo.connector.pusher.connection.bind('connected', () => {
//     window.axios.defaults.headers.common['X-Socket-Id'] =
//         window.Echo.socketId();
// });

// Listen real-time
window.Echo.channel('chat')
    .listen('MessageSent', (e) => {
        console.log('RECEIVED:', e);  
        renderMessage(e.message);  
         const chatBody = document.querySelector('.chat-body');
        chatBody.scrollTop = chatBody.scrollHeight;   
    });


// Send message
el.form.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!el.input.value.trim()) return;

    const response = await axios.post('/messages', {
        message: el.input.value
    });

    renderMessage(response.data);
    const chatBody = document.querySelector('.chat-body');
    chatBody.scrollTop = chatBody.scrollHeight;

    el.input.value = '';
    el.input.focus();
});

// UI function
function renderMessage(m) {
    const div = document.createElement('div');

    const isMe = Number(m.user_id) === Number(window.currentUserId);

    const displayName = isMe
        ? 'You'
        : (m.user?.name || 'User');

    // Format time
    const time = new Date(m.created_at).toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });

    div.classList.add('chat-message', isMe ? 'me' : 'other');

    div.innerHTML = `
    <div class="msg-header">
        <span class="msg-name">${displayName}</span>
        <span class="msg-time">${time}</span>
    </div>
    <div class="msg-text">${m.message}</div>
`;

    el.messages.appendChild(div);
    el.messages.scrollTop = el.messages.scrollHeight;
}