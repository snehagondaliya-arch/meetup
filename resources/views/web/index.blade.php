@extends('layouts.master')
@section('title', config('app.name'))
@section('no-sidebar', false)
{{-- vite --}}
{{-- @vite('resources/js/app.js') --}}

@section('content')

    @php
        if (session('show_login_modal')) {
            abort(403);
        }
        $monthNames = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];
        $currentMonth = now()->month;
        $currentYear = now()->year;
    @endphp

    <!-- Main Content -->
    <div class="main-page-content">

        <!-- Search Section -->
        <section>
            <div class="container">
                <div class="row row-gap-3">
                    <div class="col-12">

                        <div class="d-flex position-relative" style="height: 80vh;">

                            <!-- MAP -->
                            <div id="map" class="w-75"></div>

                            <!-- TIMELINE FILTER -->
                            <div class="timeline-wrapper">

                                <div class="timeline-container shadow">

                                    {{-- <div class="timeline-arrow" id="scrollLeft">‹</div> --}}

                                    <div id="timelineScroll" class="timeline-scroll">
                                        @foreach($months as $item)
                                            <div class="timeline-chip {{ ($item->month == $currentMonth && $item->year == $currentYear) ? 'active' : '' }}"
                                                data-month="{{ $item->month }}" data-year="{{ $item->year }}">

                                                {{ $monthNames[$item->month] }} {{ $item->year }}
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- <div class="timeline-arrow" id="scrollRight">›</div> --}}

                                </div>

                            </div>

                            <!-- SIDEBAR -->
                            <div class="w-25 bg-white border-start overflow-auto">

                                <div class="p-3">
                                    <h5 class="fw-semibold mb-3">Upcoming Events</h5>
                                    <hr>

                                    <!-- Search -->
                                    <div class="mb-3">
                                        <input type="text" id="search" class="form-control rounded-3"
                                            placeholder="Search by location, event name...">
                                    </div>

                                    <!-- Filter -->
                                    <div class="small text-muted mb-3">
                                        <a href="{{ url()->current() }}" class="text-decoration-none">
                                            All Events
                                        </a>
                                        <span id="total_events"></span>
                                    </div>

                                    <!-- Sidebar Events -->
                                     <!-- Sidebar Events -->
                                    <div id="sidebar-event-container">
                                        @include('web.partials.map-events')
                                    </div>
                                </div>

                            </div>
                            <!-- END SIDEBAR -->

                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- End Search Section -->

        <div id="chatBox" class="chat-box">

            <!-- Header -->
            <div class="chat-header">
                <h6 class="mb-0">Messages</h6>
                <span id="closeChat" class="close-btn">&times;</span>
            </div>

            <!-- Body -->
            <div class="chat-body">
                <div id="messages" class="messages-container"></div>
            </div>

            <!-- Footer -->
            <form id="message-form">
                <div class="chat-footer">
                    <input id="message-input" type="text" placeholder="Type your message...">

                    <button type="submit" class="send-btn">
                        ➤
                    </button>
                </div>
            </form>

        </div>
        <!-- Trending Events Section -->
        <section class="event-section section-s1padding pt-0 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title-s2 mb-4 mx-0">
                            <h2>Trending Events</h2>
                            <p>Discover what's happening in your area</p>
                        </div>
                    </div>
                </div>
                <div class="row row-gap-3" id="event-container">
                    @include('web.partials.events')
                </div>
            </div>
        </section>
        <!-- End Trending Events Section -->

        <!-- Chat Button -->
        <button id="chatToggle" class="chat-btn" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="white" viewBox="0 0 16 16">
                <path
                    d="M8 2C4.686 2 2 4.239 2 7c0 1.418.74 2.703 1.94 3.633-.088.64-.36 1.366-.87 1.91-.22.235-.03.61.29.57 1.02-.13 1.94-.57 2.53-.95.67.2 1.39.307 2.11.307 3.314 0 6-2.239 6-5s-2.686-5-6-5z" />
            </svg>
        </button>
        <!-- End Main Content -->

@endsection
@section('js')
<script>
$(function () {

    let baseUrl = "{{ url('/') }}";

    let currentUserId = @json(
        Auth::guard('organization')->check()
        ? Auth::guard('organization')->id()
        : Auth::id()
    );

    let currentUserType = @json(
        Auth::guard('organization')->check()
        ? 'App\\Models\\Organization'
        : 'App\\Models\\User'
    );

    let allMessages = [];

    const messagesBox = $("#messages");
    const messageForm = $("#message-form");
    const messageInput = $("#message-input");

    // escape html
    function escapeHtml(text) {
        return $('<div>').text(text || '').html();
    }

    // =========================
    // FETCH MESSAGES
    // =========================
    function fetchMessages() {

        $.ajax({
            url: baseUrl + "/messages",
            type: "GET",

            success: function (response) {

                allMessages = Array.isArray(response)
                    ? response
                    : [];

                renderMessages();
            },

            error: function (xhr, status, error) {

                console.error("Message fetch failed:", error);

                messagesBox.html(`
                    <div class="error-message">
                        Failed to load messages.
                    </div>
                `);
            }
        });
    }

    fetchMessages();

    // =========================
    // SEND MESSAGE
    // =========================
    messageForm.on("submit", function (e) {

        e.preventDefault();

        if (!@json(Auth::guard('organization')->check() || Auth::guard('web')->check())) {

            $("#loginBackdrop").modal("show");
            return;
        }

        let message = messageInput.val().trim();

        if (message === "") {
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

            error: function (xhr, status, error) {

                console.error(error);
            }
        });
    });

    // =========================
    // BUILD TREE
    // =========================
    function buildTree(messages) {

        let map = {};
        let roots = [];

        $.each(messages, function (index, msg) {

            msg.replies = [];
            map[msg.id] = msg;
        });

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

    // =========================
    // RENDER MESSAGES
    // =========================
    function renderMessages() {

        messagesBox.html("");

        if (allMessages.length === 0) {

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

    // =========================
    // SINGLE MESSAGE
    // =========================
    function createMessage(msg, level = 0) {

        let isMe =
            Number(msg.messageable_id) === Number(currentUserId) &&
            msg.messageable_type === currentUserType;

        let isOrg =
            (msg.messageable_type || '').includes("Organization");

        let name = isOrg
            ? (msg.messageable?.organization_name || "Organization")
            : (msg.messageable?.name || "User");

        let firstLetter = name.charAt(0).toUpperCase();

        let time = '';

        if (msg.created_at) {

            time = new Date(msg.created_at).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        let html = $(`
            <div class="chat-message ${isMe ? 'me' : 'other'}"
                 style="margin-left:${level * 16}px">

                <div class="msg-row">

                    <div class="avatar">
                        ${escapeHtml(firstLetter)}
                    </div>

                    <div class="msg-content">

                        <div class="msg-header">

                            <span class="msg-name">
                                ${escapeHtml(name)}
                            </span>

                            <span class="msg-time">
                                ${time}
                            </span>

                        </div>

                        <div class="msg-line">

                            <div class="msg-text">
                                ${escapeHtml(msg.message)}
                            </div>

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

        // toggle reply
        html.find(".reply-btn").click(function () {

            html.find(".reply-box").toggle();
        });

        // send reply
        html.find(".send-reply-btn").click(function () {

            if (!@json(Auth::guard('organization')->check() || Auth::guard('web')->check())) {

                $("#loginBackdrop").modal("show");
                return;
            }

            let replyText = html.find(".reply-text").val().trim();

            if (replyText === "") {
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

                error: function (xhr, status, error) {

                    console.error(error);
                }
            });
        });

        // replies
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

    // =========================
    // CHAT TOGGLE
    // =========================
    $("#chatToggle").click(function () {
        $("#chatBox").toggleClass("show");
    });

    $("#closeChat").click(function () {
        $("#chatBox").removeClass("show");
    });

    // =========================
    // TIMELINE SCROLL
    // =========================
    function scrollChipIntoView(el) {

        if (!el || el.length === 0) return;

        el[0].scrollIntoView({
            behavior: 'smooth',
            inline: 'center',
            block: 'nearest'
        });
    }

    let currentDate = new Date();

    let selectedMonth = currentDate.getMonth() + 1;
    let selectedYear = currentDate.getFullYear();

    let search = '';
    let category = '';

    $(document).on('click', '.nav-link[data-slug]', function (e) {

        e.preventDefault();

        category = $(this).data('slug');

        $('.nav-link[data-slug]').removeClass('active');

        $(this).addClass('active');

        loadData();
    });

    let $activeChip = $('.timeline-chip.active');

    scrollChipIntoView($activeChip);

    $('.timeline-chip').on('click', function () {

        $('.timeline-chip').removeClass('active');

        $(this).addClass('active');

        scrollChipIntoView($(this));

        selectedMonth = $(this).data('month');
        selectedYear = $(this).data('year');

        loadData();
    });

    // search
    $(document).on('input', '#search', function () {

        search = $(this).val() || '';
        loadData();
    });

    // horizontal scroll
    const scrollContainer = document.getElementById('timelineScroll');

    if (scrollContainer) {

        scrollContainer.addEventListener('wheel', function (e) {

            e.preventDefault();

            this.scrollBy({
                left: e.deltaY,
                behavior: 'smooth'
            });
        });
    }

    // =========================
    // MAP
    // =========================
    let map;
    let markerGroup;
    let debounceTimer;

    navigator.geolocation.getCurrentPosition(

        function (position) {

            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            initializeMap(lat, lng);
        },

        function (error) {

            console.log("Location access denied:", error);

            initializeMap(19.0760, 72.8777);
        }
    );

    function initializeMap(lat, lng) {

        if (map) {
            map.remove();
        }

        var userIcon = L.divIcon({

            className: "custom-user-marker",

            html: `
                <div class="user-marker">
                    <div class="pulse"></div>
                </div>
            `,

            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        map = L.map('map').setView([lat, lng], 13);

        L.marker([lat, lng], {
            icon: userIcon
        }).addTo(map);

        setTimeout(() => {
            map.invalidateSize();
        }, 100);

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                attribution: '&copy; OpenStreetMap contributors'
            }
        ).addTo(map);

        markerGroup = L.markerClusterGroup();

        map.addLayer(markerGroup);

        map.on('moveend', function () {

            clearTimeout(debounceTimer);

            // loadData();
            debounceTimer = setTimeout(loadData, 100);
        });

        loadData();
    }

    // =========================
    // LOAD DATA
    // =========================
    function loadData() {

        if (!map || !markerGroup) return;

        markerGroup.clearLayers();

        let bounds = map.getBounds();

        $.ajax({

            url: "{{ route('map.data') }}",
            type: "GET",

            data: {
                category: category,
                minLat: bounds.getSouth(),
                maxLat: bounds.getNorth(),
                minLng: bounds.getWest(),
                maxLng: bounds.getEast(),
                month: selectedMonth,
                year: selectedYear,
                search: search
            },

            success: function (response) {

                if (!response) return;

                $('#event-container').html(response.events || '');

                if (typeof AOS !== 'undefined') {
                    AOS.refreshHard();
                }

                if (
                    !response.events_map ||
                    response.events_map.length === 0
                ) {

                    $('#sidebar-event-container').html(`
                        <div class="no-data">
                            No Data Found
                        </div>
                    `);

                    $('#total_events').html('(0)');

                    markerGroup.clearLayers();

                    return;
                }

                $('#sidebar-event-container').html(response.sidebar || '');

                $('#total_events').html(
                    '(' + response.events_map.length + ')'
                );

                markerGroup.clearLayers();

                response.events_map.forEach(function (event) {

                    if (
                        !event.latitude ||
                        !event.longitude
                    ) {
                        return;
                    }

                    let popupContent = `
                        <a href="${baseUrl}/event-detail/${event.slug ?? '#'}"
                           class="event-card-link">

                            <div class="event-card-popup">

                                <img src="${event.image_url ?? ''}"
                                     alt="${escapeHtml(event.title ?? 'Untitled Event')}"
                                     class="event-img" />

                                <div class="event-body">

                                    <h3>
                                        ${escapeHtml(event.title ?? 'Untitled Event')}
                                    </h3>

                                    <p>
                                        <strong>📍 Location:</strong>
                                        ${escapeHtml(event.venue_name ?? 'N/A')}
                                    </p>

                                    <p>
                                        <strong>🕒 Date:</strong>
                                        ${event.formatted_date ?? ''}
                                        ${event.formatted_time ?? ''}
                                    </p>

                                </div>

                            </div>

                        </a>
                    `;

                    let marker = L.marker([
                        parseFloat(event.latitude),
                        parseFloat(event.longitude)
                    ]).bindPopup(popupContent);

                    markerGroup.addLayer(marker);
                });
            },

            error: function (xhr, status, error) {

                console.error("Map data load failed:", error);

                $('#sidebar-event-container').html(`
                    <div class="no-data">
                        Failed to load data
                    </div>
                `);
            }
        });
    }

});
</script>
@endsection