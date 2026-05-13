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
            $(document).ready(function () {
                let baseUrl = "{{ url('/') }}";

                let currentUserId = @json(
                    Auth::guard('organization')->check()
                    ? Auth::guard('organization')->id()
                    : auth()->id()
                );

                let allMessages = [];

                const messagesBox = $("#messages");
                const messageForm = $("#message-form");
                const messageInput = $("#message-input");


                // get messages
                function fetchMessages() {

                    $.ajax({
                        url: baseUrl + "/messages",
                        type: "GET",

                        success: function (response) {

                            allMessages = response;

                            renderMessages();
                        },

                        error: function (error) {
                            console.log(error);
                        }
                    });

                }

                // first load
                fetchMessages();

                // auto refresh every 2 seconds
                // setInterval(fetchMessages, 2000);

                // =========================
                // SEND NEW MESSAGE
                // =========================

                messageForm.on("submit", function (e) {

                    e.preventDefault();

                    let message = messageInput.val().trim();

                    if (message == "") {
                        return;
                    }

                    $.ajax({
                        url: baseUrl + "/messages",
                        type: "POST",

                        data: {
                            _token: "{{ csrf_token() }}",
                            message: message,
                            parent_id: null
                        },

                        success: function () {

                            messageInput.val("");

                            fetchMessages();
                        },

                        error: function (error) {
                            console.log(error);
                        }
                    });

                });

                // tree for parent and child
                function buildTree(messages) {

                    let map = {};
                    let roots = [];

                    // create map
                    $.each(messages, function (index, msg) {

                        msg.replies = [];

                        map[msg.id] = msg;
                    });

                    // add replies
                    $.each(messages, function (index, msg) {

                        if (msg.parent_id) {

                            if (map[msg.parent_id]) {

                                map[msg.parent_id].replies.push(msg);
                            }

                        } else {

                            roots.push(msg);
                        }

                    });

                    return roots;
                }

                // all messages
                function renderMessages() {

                    messagesBox.html("");

                    if (allMessages.length == 0) {

                        messagesBox.append(`
                                <div class="no-messages">
                                    No messages yet. Start conversation 👋
                                </div>
                            `);

                        return;
                    }

                    let tree = buildTree(allMessages);

                    $.each(tree, function (index, msg) {

                        messagesBox.append(createMessage(msg));

                    });

                    messagesBox.scrollTop(messagesBox[0].scrollHeight);
                }

                // single message
                function createMessage(msg, level = 0) {

                    let isMe = Number(msg.user_id) === Number(currentUserId);

                    let isOrg = msg.user_type === "organization";

                    let name = isOrg
                        ? (msg.organization?.organization_name || "Organization")
                        : (msg.user?.name || "User");

                    let firstLetter = name.charAt(0).toUpperCase();

                    let time = new Date(msg.created_at).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    let html = $(`
                    <div class="chat-message ${isMe ? 'me' : 'other'}" 
                         style="margin-left:${level * 16}px">

                        <div class="msg-row">

                            <div class="avatar">${firstLetter}</div>

                            <div class="msg-content">

                                <div class="msg-header">

                                    <span class="msg-name">${name}</span>

                                    <span class="msg-time">${time}</span>

                                </div>

                                <div class="msg-line">

                                    <div class="msg-text">${msg.message}</div>

                                    ${level === 0
                            ? `<span class="reply-btn">↩ Reply</span>`
                            : ''
                        }

                                </div>

                               ${level === 0 ? `
                                    <div class="reply-box" style="display:none;">

                                        <div class="reply-input">

                                            <input type="text" 
                                                class="reply-text"
                                                placeholder="Write a reply..." />

                                            <button class="send-reply-btn">
                                                Send
                                            </button>

                                        </div>

                                    </div>
                                ` : ''} 

                                <div class="replies"></div>

                            </div>

                        </div>

                    </div>
                `);

                    // toggle of reply button
                    html.find(".reply-btn").click(function () {

                        html.find(".reply-box").toggle();

                    });

                    // send reply ajax
                    html.find(".send-reply-btn").click(function () {

                        let replyText = html.find(".reply-text").val().trim();

                        if (replyText == "") {
                            return;
                        }

                        $.ajax({

                            url: baseUrl + "/messages",

                            type: "POST",

                            data: {
                                _token: "{{ csrf_token() }}",
                                message: replyText,
                                parent_id: msg.id
                            },

                            success: function () {

                                fetchMessages();

                            },

                            error: function (error) {

                                console.log(error);
                            }

                        });

                    });

                    // show reply
                    if (msg.replies && msg.replies.length > 0) {

                        let repliesContainer = html.find(".replies");

                        let expanded = false;

                        function renderReplies() {

                            repliesContainer.html("");

                            if (expanded) {

                                $.each(msg.replies, function (index, reply) {

                                    repliesContainer.append(
                                        createMessage(reply, level + 1)
                                    );

                                });

                            }

                            let toggleBtn = $(`
                            <div class="view-more" style="cursor:pointer;">
                                ${expanded
                                    ? 'Hide replies'
                                    : 'View replies (' + msg.replies.length + ')'
                                }
                            </div>
                        `);

                            toggleBtn.click(function () {

                                expanded = !expanded;

                                renderReplies();

                            });

                            repliesContainer.append(toggleBtn);

                        }

                        renderReplies();
                    }

                    return html;
                }

                // chat toggle
                $("#chatToggle").click(function () {
                    $("#chatBox").toggleClass("show");
                });

                $("#closeChat").click(function () {
                    $("#chatBox").removeClass("show");
                });


                // Event-Filter-Select-2
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