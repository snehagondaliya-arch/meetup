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
axios.get('/messages').then(r =>
    r.data.forEach(renderMessage)
);

// Listen real-time
window.Echo.channel('chat')
    .listen('MessageSent', e => renderMessage(e.message));

// Send message
el.form.addEventListener('submit', async (e) => {
    e.preventDefault();

    await axios.post('/messages', {
        message: el.input.value
    });

    el.input.value = '';
});

// UI function
function renderMessage(m) {
    const li = document.createElement('li');
    li.textContent = `${m.user.name}: ${m.message}`;
    el.messages.appendChild(li);
}