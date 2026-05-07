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
document.addEventListener('DOMContentLoaded', () => {

    const el = {
        messages: document.getElementById('messages'),
        form: document.getElementById('message-form'),
        input: document.getElementById('message-input')
    };
    if (!el.messages || !el.form || !el.input) {
        return;
    }

    // Store all messages
    let allMessages = [];

    /* =========================
       BUILD TREE (parent-child)
    ========================= */
    function buildTree(messages) {
        const map = {};
        const roots = [];

        messages.forEach(m => {
            m.replies = [];
            map[m.id] = m;
        });

        messages.forEach(m => {
            if (m.parent_id) {
                map[m.parent_id]?.replies.push(m);
            } else {
                roots.push(m);
            }
        });

        return roots;
    }

    /* =========================
       LOAD INITIAL MESSAGES
    ========================= */
    axios.get('/messages')
        .then(r => {
            allMessages = r.data;
            renderAll();
        });

    /* =========================
       REAL-TIME LISTENER
    ========================= */
    window.Echo.channel('chat')
        .listen('MessageSent', (e) => {
            allMessages.push(e.message);
            renderAll();
        });

    /* =========================
       SEND ROOT MESSAGE
    ========================= */
    if (el.form) {
        el.form.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!el.input.value.trim()) return;

            const res = await axios.post('/messages', {
                message: el.input.value,
                parent_id: null
            });

            allMessages.push(res.data);
            renderAll();

            el.input.value = '';
        });
    }
    /* =========================
       RENDER ALL
    ========================= */
    function renderAll() {
        if (!el.messages) return;
        el.messages.innerHTML = '';
        if (allMessages.length === 0) {
            const noMsg = document.createElement('div');
            noMsg.id = 'no-messages';
            noMsg.className = 'no-messages';
            noMsg.textContent = 'No messages yet. Start the conversation 👋';

            el.messages.appendChild(noMsg);
            return; // stop here
        }

        const tree = buildTree(allMessages);

        tree.forEach(msg => {
            el.messages.appendChild(createMessageNode(msg));
        });

        el.messages.scrollTop = el.messages.scrollHeight;
    }

    /* =========================
       CREATE MESSAGE NODE
    ========================= */
    function createMessageNode(m, level = 0) {

        const div = document.createElement('div');

        const isMe = Number(m.user_id) === Number(window.currentUserId);

        const time = new Date(m.created_at).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });

        div.className = `chat-message ${isMe ? 'me' : 'other'}`;
        div.style.marginLeft = level * 16 + 'px';

        const firstLetter = (m.user?.name || 'U')[0].toUpperCase();

        div.innerHTML = `
        <div class="msg-row">
            
            <div class="avatar">${firstLetter}</div>

            <div class="msg-content">
                <div class="msg-header">
                    <span class="msg-name">${m.user?.name || 'User'}</span>
                    <span class="msg-time">${time}</span>
                </div>

              <div class="msg-line">
                <div class="msg-text">${m.message}</div>

                ${level === 0 ? `
                    <span class="reply-btn">↩ Reply</span>
                ` : ''}
            </div>
                <div class="reply-box" style="display:none;">
                <div class="reply-input">
                    <input type="text" placeholder="Write a reply..." />
                    <button>Send</button>
                </div>
            </div>

                <div class="replies"></div>
            </div>
        </div>
    `;

        const replyBtn = div.querySelector('.reply-btn');
        const replyBox = div.querySelector('.reply-box');
        const replyInput = replyBox.querySelector('input');
        const replySend = replyBox.querySelector('button');
        const repliesContainer = div.querySelector('.replies');

        /* Toggle reply box */
        if (replyBtn) {
            replyBtn.onclick = () => {
                replyBox.style.display = replyBox.style.display === 'none' ? 'flex' : 'none';
            };
        }
        /* Send reply */
        if (replySend) {
            replySend.onclick = async () => {
                if (!replyInput.value.trim()) return;

                const res = await axios.post('http://localhost/running/meetup/public/messages', {
                    message: replyInput.value,
                    parent_id: m.id
                });

                allMessages.push(res.data);
                renderAll();
            };
        }

        /* Replies + View More */
        if (m.replies && m.replies.length) {

            let expanded = false;

            const renderReplies = () => {
                repliesContainer.innerHTML = '';

                // ONLY show replies when expanded
                if (expanded) {
                    m.replies.forEach(r => {
                        repliesContainer.appendChild(
                            createMessageNode(r, level + 1)
                        );
                    });
                }

                // ALWAYS show button
                const toggle = document.createElement('div');
                toggle.className = 'view-more';

                toggle.innerHTML = expanded
                    ? 'Hide replies'
                    : 'View more';

                toggle.onclick = () => {
                    expanded = !expanded;
                    renderReplies();
                };

                repliesContainer.appendChild(toggle);
            };

            renderReplies();
        }
        return div;
    }

    // =====================
    // Chat
    // =====================
    const chatToggle = document.getElementById("chatToggle");
    const chatBox = document.getElementById("chatBox");
    const closeChat = document.getElementById("closeChat");

    if (chatToggle && chatBox) {
        chatToggle.addEventListener("click", () => {
            chatBox.classList.toggle("show");
        });
    }

    if (closeChat && chatBox) {
        closeChat.addEventListener("click", () => {
            chatBox.classList.remove("show");
        });
    }
});
