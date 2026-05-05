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
axios.get('http://localhost/running/meetup/public/messages').then(r => {
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

    const response = await axios.post('http://localhost/running/meetup/public/messages', {
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

// =====================
// Scrolling-Animation
// =====================
if (typeof AOS !== "undefined") {
    AOS.init();
}


// =====================
// Side Menu (Category)
// =====================
const categoryBtn = document.getElementById('categoryButton');
const categoryCloseBtn = document.getElementById('menuClose');
const sideCategoryMenu = document.getElementById('side-menu-section');
const categoryOverlay = document.getElementById('sideMenuOverlay');

function openCategoryMenu() {
    if (sideCategoryMenu && categoryOverlay) {
        sideCategoryMenu.classList.add('categoryOpen');
        categoryOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeCategoryMenu() {
    if (sideCategoryMenu && categoryOverlay) {
        sideCategoryMenu.classList.remove('categoryOpen');
        categoryOverlay.classList.remove('show');
        document.body.style.overflow = '';
    }
}

if (categoryBtn) categoryBtn.addEventListener('click', openCategoryMenu);
if (categoryCloseBtn) categoryCloseBtn.addEventListener('click', closeCategoryMenu);
if (categoryOverlay) categoryOverlay.addEventListener('click', closeCategoryMenu);


// =====================
// Header Side Menu
// =====================
const headerMenuBtn = document.getElementById('menuToggle');
const headerMenuCloseBtns = document.querySelectorAll('#menuClose');
const headerSideMenu = document.getElementById('sideMenu');
const headerOverlay = document.getElementById('menuOverlay');

function openHeaderMenu() {
    if (headerSideMenu && headerOverlay) {
        headerSideMenu.classList.add('open');
        headerOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeHeaderMenu() {
    if (headerSideMenu && headerOverlay) {
        headerSideMenu.classList.remove('open');
        headerOverlay.classList.remove('show');
        document.body.style.overflow = '';
    }
}

if (headerMenuBtn) headerMenuBtn.addEventListener('click', openHeaderMenu);

// safe loop (even if empty NodeList)
if (headerMenuCloseBtns) {
    headerMenuCloseBtns.forEach(btn => {
        btn.addEventListener('click', closeHeaderMenu);
    });
}

if (headerOverlay) headerOverlay.addEventListener('click', closeHeaderMenu);


// =====================
// Header Scroll Effect
// =====================
if (typeof $ !== "undefined") {
    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 320) {
            $('.header-s1').addClass('scrolled');
        } else {
            $('.header-s1').removeClass('scrolled');
        }
    });
}


// =====================
// Button Animation
// =====================
document.querySelectorAll('.animation-btn').forEach(button => {

    if (!button) return;

    button.addEventListener('click', function (e) {

        const circle = document.createElement("span");

        const diameter = Math.max(this.clientWidth, this.clientHeight);
        const radius = diameter / 2;

        circle.style.width = circle.style.height = `${diameter}px`;
        circle.style.left = `${e.clientX - this.getBoundingClientRect().left - radius}px`;
        circle.style.top = `${e.clientY - this.getBoundingClientRect().top - radius}px`;
        circle.classList.add("start");

        const start = this.getElementsByClassName("start")[0];
        if (start) start.remove();

        if (this) {
            this.appendChild(circle);
        }
    });

});


// =====================
// Swiper Sliders
// =====================
if (typeof Swiper !== "undefined") {

    if (document.querySelector(".mySwiper")) {
        new Swiper(".mySwiper", {
            autoplay: true,
            loop: true,
            autoplay: {
                delay: 1400,
            },
        });
    }

    if (document.querySelector(".evCTASlider")) {
        new Swiper(".evCTASlider", {
            loop: true,
            autoplay: {
                delay: 2500,
            },
            speed: 800,
            effect: "slide"
        });
    }

    if (document.querySelector(".upcomingEvent")) {
        new Swiper(".upcomingEvent", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 2000,
            },
            pagination: {
                el: ".upcomingEvent .swiper-pagination",
                clickable: true
            },
            navigation: {
                nextEl: ".upcomingEvent .swiper-button-next",
                prevEl: ".upcomingEvent .swiper-button-prev"
            },
            breakpoints: {
                576: { slidesPerView: 2 },
                992: { slidesPerView: 3 }
            }
        });
    }
}


// =====================
// Chat
// =====================
document.addEventListener("DOMContentLoaded", () => {
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

// map
let map;
// let markers = [];
let markerGroup; 
let debounceTimer;

document.getElementById('open-map').addEventListener('click', () => {
    document.getElementById('map-modal').style.display = 'block';

    navigator.geolocation.getCurrentPosition(function (position) {

        if (map) {
            map.remove();
        }

        map = L.map('map').setView([39.4810, -0.3625], 13);

        setTimeout(() => {
            map.invalidateSize();
        }, 200);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        map.on('moveend', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(loadEvents, 400);
        });
        markerGroup = L.markerClusterGroup();
        map.addLayer(markerGroup);          
        loadEvents();
    });
});

function loadEvents() {
    if (!map) return;

    // markers.forEach(m => map.removeLayer(m));
    // markers = [];
    markerGroup.clearLayers();

    var bounds = map.getBounds();

    var url = `events-by-bounds?minLat=${bounds.getSouth()}&maxLat=${bounds.getNorth()}&minLng=${bounds.getWest()}&maxLng=${bounds.getEast()}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            data.forEach(event => {
                let lat = parseFloat(event.latitude);
                let lng = parseFloat(event.longitude);
                // let marker = L.marker([lat, lng])
                //     .addTo(map) 
                //     .bindPopup(event.title);

                // markers.push(marker);
                let marker = L.marker([lat, lng])
                    .bindPopup(event.title);

                markerGroup.addLayer(marker);
            });
        })
        .catch(err => console.error(err));
}

document.getElementById('close-map').addEventListener('click', () => {
    document.getElementById('map-modal').style.display = 'none';
});