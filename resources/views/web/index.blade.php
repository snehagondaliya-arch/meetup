@extends('layouts.master')
@section('title', config('app.name'))
@section('no-sidebar', false)
{{-- vite --}}
{{-- @vite('resources/js/app.js') --}}
@section('content')
    <!-- Main-Content -->
    <div class="main-page-content">
        <!-- Start Search Section -->
        <section class="search-section-s1 section-s1padding  position-relative">
            <div class="container">
                <div class="row row-gap-3">
                    <div class="col-12">
                        <div
                            class="search-content d-flex flex-column justify-content-center align-items-center text-center max-w-800px mx-auto">
                            <div class="search-badge-icon mb-1 d-flex align-items-center gt-text-ffffff gap-2"
                                data-aos="fade-down" data-aos-delay="0" data-aos-duration="900">= <i
                                    class="fa-solid fa-people-line"></i> =</div>
                            <h1 class="search-title-s1 gt-text-title fw-600 mb-3" data-aos="fade-down" data-aos-delay="0"
                                data-aos-duration="800">The <span class="gt-text-theme">people platform,</span> where
                                interests become friendships.</h1>
                            <p class="text-muted change-fs-20px-18px">Whatever your interest, from hiking and cooking to
                                tech and art — there’s a community waiting for you. Explore events happening every day, meet
                                new people, and build meaningful connections through shared experiences.</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="search-input-content d-flex flex-sm-row flex-column gap-2">
                            <div class="search-input-box position-relative w-100">
                                <input type="text" class="form-control input-field-s1 canvase-search" id="search"
                                    name="search" placeholder="Search events, categories, or locations...">

                                <input type="hidden" name="category" value="{{ request('category') }}">
                                <span class="gt-text-theme rounded-pill"><i
                                        class="fa-solid fa-magnifying-glass  fs-18px"></i></span>
                            </div>
                            <div class="event-filter-content select-content">
                                <select class="form-control event-select-s1" name="date_filter">
                                    <option value="">Any Day</option>
                                    <option value="starting_soon">Starting soon</option>
                                    <option value="today">Today</option>
                                    <option value="tomorrow">Tomorrow</option>
                                    <option value="this_week">This week</option>
                                    <option value="this_weekend">This weekend</option>
                                    <option value="next_week">Next week</option>
                                </select>
                            </div>
                            <div class="event-filter-content select-content">
                                <select class="form-control event-select-s1" name="event_type">
                                    <option value="">Any type</option>
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                </select>
                            </div>

                            <div class="event-filter-content select-content">
                                <select class="form-control event-select-s1" name="distance">
                                    <option value="">All distances</option>
                                    <option value="5">5 kilometers</option>
                                    <option value="10">10 kilometers</option>
                                    <option value="25">25 kilometers</option>
                                    <option value="30">30 kilometers</option>
                                    <option value="50">50 kilometers</option>
                                    <option value="100">100 kilometers</option>
                                    <option value="150">150 kilometers</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- End Search Section -->
        <!-- Start Event-Card Section -->
        <section class="event-section section-s1padding pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title-s2  mb-4 mx-0">
                            <h2>Trending Event</h2>
                            <p>Discover what's happening in your area</p>
                        </div>
                    </div>
                </div>
                <div class="row row-gap-3" id="event-container">
                    @include('web.partials.events')
                </div>
            </div>
        </section>
        <!-- End Event-Card Section -->

        <!-- Chat Button -->
        <button id="chatToggle" class="chat-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="white" viewBox="0 0 16 16">
                <path
                    d="M8 2C4.686 2 2 4.239 2 7c0 1.418.74 2.703 1.94 3.633-.088.64-.36 1.366-.87 1.91-.22.235-.03.61.29.57 1.02-.13 1.94-.57 2.53-.95.67.2 1.39.307 2.11.307 3.314 0 6-2.239 6-5s-2.686-5-6-5z" />
            </svg>
        </button>

        <!-- Chat Box -->
        <div id="chatBox" class="chat-box">

            <!-- Header -->
            <div class="chat-header">
                <div>
                    <h6 class="mb-0">Messages</h6>
                </div>
                <span id="closeChat" class="close-btn">&times;</span>
            </div>

            <!-- Body -->
            <div class="chat-body">
                <div id="messages" class="messages-container"></div>
            </div>

            <!-- Footer -->
            <form id="message-form">
                <div class="chat-footer">
                    <input id="message-input" type="text" placeholder="Type your message..." />
                    <button type='submit' class="send-btn">➤</button>
                </div>
            </form>

        </div>
@endsection
    @section('js')
        <script>
            // chat

            document.addEventListener('DOMContentLoaded', () => {
                window.baseUrl = "{{ url('/') }}";
                window.currentUserId = @json(
                    Auth::guard('organization')->check()
                    ? Auth::guard('organization')->id()
                    : auth()->id()
                );
                const el = {
                    messages: document.getElementById('messages'),
                    form: document.getElementById('message-form'),
                    input: document.getElementById('message-input')
                };

                if (!el.messages || !el.form || !el.input) return;

                let allMessages = [];

                function buildTree(messages) {

                    const map = {};
                    const roots = [];

                    messages.forEach(m => {
                        m.replies = [];
                        map[m.id] = m;
                    });

                    messages.forEach(m => {

                        if (m.parent_id) {

                            if (map[m.parent_id]) {
                                map[m.parent_id].replies.push(m);
                            }

                        } else {
                            roots.push(m);
                        }

                    });

                    return roots;
                }

                async function fetchMessages() {

                    try {

                        const response = await $.get(window.baseUrl + '/messages');

                        const newData = JSON.stringify(response);
                        const oldData = JSON.stringify(allMessages);

                        if (newData !== oldData) {

                            allMessages = response;

                            renderAll();
                        }

                    } catch (error) {

                        console.log(error);
                    }
                }

                fetchMessages();

                // setInterval(fetchMessages, 2000);

                el.form.addEventListener('submit', async (e) => {

                    e.preventDefault();

                    const message = el.input.value.trim();

                    if (!message) return;

                    try {

                        await $.post(window.baseUrl + '/messages', {
                            message: message,
                            parent_id: null
                        });

                        el.input.value = '';

                        fetchMessages();

                    } catch (error) {
                        console.log(error);
                    }
                });

                function renderAll() {

                    el.messages.innerHTML = '';

                    if (allMessages.length === 0) {

                        const noMsg = document.createElement('div');

                        noMsg.className = 'no-messages';

                        noMsg.innerHTML = `
                                No messages yet. Start conversation 👋
                            `;

                        el.messages.appendChild(noMsg);

                        return;
                    }

                    const tree = buildTree(allMessages);

                    tree.forEach(msg => {
                        el.messages.appendChild(createMessageNode(msg));
                    });

                    el.messages.scrollTop = el.messages.scrollHeight;
                }

                function createMessageNode(m, level = 0) {

                    const div = document.createElement('div');

                    const isOrg = m.user_type === 'organization';

                    const isMe = Number(m.user_id) === Number(window.currentUserId);

                    const name = isOrg
                        ? m.organization?.organization_name
                        : m.user?.name;

                    const time = new Date(m.created_at).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    div.className = `chat-message ${isMe ? 'me' : 'other'}`;
                    div.style.marginLeft = level * 16 + 'px';

                    const firstLetter = (name || 'U')[0].toUpperCase();

                    div.innerHTML = `
            <div class="msg-row">

                <div class="avatar">${firstLetter}</div>

                <div class="msg-content">

                    <div class="msg-header">
                        <span class="msg-name">${name || 'User'}</span>
                        <span class="msg-time">${time}</span>
                    </div>

                    <div class="msg-line">
                        <div class="msg-text">${m.message}</div>

                        ${level === 0 ? `<span class="reply-btn">↩ Reply</span>` : ''}
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
                    const replyInput = div.querySelector('.reply-input input');
                    const replySend = div.querySelector('.reply-input button');
                    const repliesContainer = div.querySelector('.replies');

                    // toggle reply box
                    if (replyBtn) {
                        replyBtn.onclick = () => {
                            replyBox.style.display =
                                replyBox.style.display === 'none' ? 'block' : 'none';
                        };
                    }

                    // send reply
                    if (replySend) {
                        replySend.onclick = async () => {

                            const text = replyInput.value.trim();
                            if (!text) return;

                            await $.post(window.baseUrl + '/messages', {
                                message: text,
                                parent_id: m.id
                            });

                            fetchMessages();
                        };
                    }

                    if (m.replies && m.replies.length > 0) {

                        let expanded = false;

                        const renderReplies = () => {

                            repliesContainer.innerHTML = '';

                            if (expanded) {

                                m.replies.forEach(reply => {
                                    repliesContainer.appendChild(
                                        createMessageNode(reply, level + 1)
                                    );
                                });
                            }

                            const toggle = document.createElement('div');
                            toggle.className = 'view-more';

                            toggle.innerHTML = expanded
                                ? 'Hide replies'
                                : `View replies (${m.replies.length})`;

                            toggle.style.cursor = 'pointer';

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

                // chat toogle
                const chatToggle = document.getElementById('chatToggle');
                const chatBox = document.getElementById('chatBox');
                const closeChat = document.getElementById('closeChat');

                if (chatToggle && chatBox) {

                    chatToggle.addEventListener('click', () => {
                        chatBox.classList.toggle('show');
                    });
                }

                if (closeChat && chatBox) {

                    closeChat.addEventListener('click', () => {
                        chatBox.classList.remove('show');
                    });
                }

            });
            // Event-Filter-Select-2
            $(document).ready(function () {
                let category = null;
                let search = '';

                $('.event-select-s1').select2({
                    dropdownCssClass: "event-select-s1Dropdown",
                });

                $(document).on('click', '.side-menu-nav .nav-link', function (e) {
                    e.preventDefault();

                    category = $(this).data('slug');

                    $('.side-menu-nav .nav-link').removeClass('active');
                    $(this).addClass('active');

                    loadData();
                });

                // SEARCH
                $(document).on('input', '#search', function () {
                    search = $(this).val();
                    loadData();
                });

                $('select').on('change', function () {
                    loadData();
                });

                let userLatitude = null;
                let userLongitude = null;
                navigator.geolocation.getCurrentPosition(function (position) {
                    userLatitude = position.coords.latitude;
                    userLongitude = position.coords.longitude;

                    loadData(userLatitude, userLongitude);
                });

                function loadData(latitude = userLatitude, longitude = userLongitude) {

                    const date_filter = $('select[name="date_filter"]').val();
                    const event_type = $('select[name="event_type"]').val();
                    const distance = $('select[name="distance"]').val();
                    $.ajax({
                        url: "{{ route('index') }}",
                        type: "GET",
                        data: {
                            category: typeof category !== 'undefined' ? category : '',
                            search: typeof search !== 'undefined' ? search : '',
                            latitude: latitude,
                            longitude: longitude,
                            date_filter: date_filter,
                            event_type: event_type,
                            distance: distance ? distance : null
                        },
                        success: function (html) {
                            document.getElementById('event-container').innerHTML = html;

                            if (typeof AOS !== 'undefined') {
                                AOS.refreshHard();
                            }
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            });

        </script>
    @endsection