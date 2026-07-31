@extends('layouts.master')
@section('title', config('app.name'))
@section('sidebar')
@endsection
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
        <section class="pt-0 mt-4">
            <div class="container">
                <div class="map-content-s1">
                    <div class="row row-gap-3 h-100">
                        <div class="col-xl-8 col-xxl-9">
                            <div class="map-view d-flex position-relative">
                                <!-- MAP -->
                                <div id="map" class="w-100 rounded-12" style="z-index: 50"></div>
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
                            </div>
                        </div>
                        <div class="col-xl-4 col-xxl-3">
                            <div class="map-info d-flex">
                                <!-- SIDEBAR -->
                                <div class="w-100 bg-white border rounded-12 overflow-hidden py-3 h-100">
                                    <div class="px-3">
                                        <h5 class="fw-semibold mb-3">Upcoming Events</h5>
                                        <hr>
                                        <div>
                                            <input type="text" id="search" class="form-control rounded-3"
                                                placeholder="Search by location, event name...">
                                        </div>
                                    </div>
                                    <div class="small text-muted p-3">
                                        <a href="{{ url()->current() }}" class="text-decoration-none">
                                            All Events
                                        </a>
                                        <span id="total_events"></span>
                                    </div>
                                    <div class="mapmenu-event-list-content pt-1 px-3 overflow-y-auto d-flex gap-2 justify-content-start align-items-xl-center flex-column" id="sidebar-event-container">
                                        @include('web.partials.map-events')
                                    </div>
                                </div>
                            </div>
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
                <span id="closeChat" class="close-btn"><i class="fa-solid fa-xmark fs-5"></i></span>
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
$(document).ready(function () {

        const baseUrl = "{{ url('/') }}";

        const currentUserId = @json(
            Auth::guard('organization')->check()
                ? Auth::guard('organization')->id()
                : Auth::id()
        );

        const currentUserType = @json(
            Auth::guard('organization')->check()
                ? 'App\\Models\\Organization'
                : 'App\\Models\\User'
        );

        const isAuthenticated = @json(
            Auth::guard('organization')->check() ||
            Auth::guard('web')->check()
        );

        let allMessages = [];


        const messagesBox = $("#messages");
        const messageForm = $("#message-form");
        const messageInput = $("#message-input");


        // =====================================================
        // HELPERS
        // =====================================================

        function escapeHtml(text) {
            return $('<div>').text(text || '').html();
        }

        function showLoginModal() {
            $("#loginBackdrop").modal("show");
        }

        function ajaxError(title, error) {
            console.error(title, error);
        }

        function isNearBottom() {

            const el = messagesBox[0];

            return (
                el.scrollHeight - el.scrollTop - el.clientHeight < 100
            );
        }


        // =====================================================
        // FETCH MESSAGES
        // =====================================================

        function fetchMessages() {

            $.ajax({

                url: "{{ route('fetchMessages') }}",

                type: "GET",

                success: function(response) {

                    if (!Array.isArray(response)) return;

                    if (response.length) {

                        response.forEach(newMsg => {

                            const exists = allMessages.some(
                                oldMsg => oldMsg.id === newMsg.id
                            );

                            if (!exists) {
                                allMessages.push(newMsg);
                            }
                        });
                        
                    }
                    renderMessages();
                },

                error: function(xhr, status, error) {

                    ajaxError("Fetch error:", error);
                }
            });
        }


        // =====================================================
        // SEND MESSAGE
        // =====================================================

        function sendMessage(message, parentId = null) {

            $.ajax({

                url: "{{ route('sendMessage') }}",

                type: "POST",

                data: {

                    _token: "{{ csrf_token() }}",

                    message: message,

                    parent_id: parentId
                },

                success: function(response) {

                    messageInput.val('');

                    const exists = allMessages.some(
                        msg => msg.id === response.id
                    );

                    if (!exists) {

                        allMessages.push(response);

                        // lastMessageId = Math.max(
                        //     lastMessageId,
                        //     response.id
                        // );

                        renderMessages();
                    }
                },

                error: function(xhr, status, error) {

                    ajaxError("Send error:", error);
                }
            });
        }


        // =====================================================
        // BUILD TREE
        // =====================================================

        function buildTree(messages) {

            let map = {};

            let roots = [];

            messages.forEach(msg => {

                msg.replies = [];

                map[msg.id] = msg;
            });

            messages.forEach(msg => {

                if (msg.parent_id && map[msg.parent_id]) {

                    map[msg.parent_id].replies.push(msg);

                } else {

                    roots.push(msg);
                }
            });

            return roots;
        }


        // =====================================================
        // RENDER MESSAGES
        // =====================================================

        function renderMessages() {

            const shouldScroll = isNearBottom();

            messagesBox.empty();

            if (!allMessages.length) {

                messagesBox.html(`
                    <div class="no-messages">
                        No messages yet 👋
                    </div>
                `);

                return;
            }

            const tree = buildTree(allMessages);

            tree.forEach(msg => {

                messagesBox.append(
                    createMessage(msg)
                );
            });

            if (shouldScroll) {

                messagesBox.scrollTop(
                    messagesBox[0].scrollHeight
                );
            }
        }


        // =====================================================
        // CREATE MESSAGE
        // =====================================================

        function createMessage(msg, level = 0) {

            const isMe =
                Number(msg.messageable_id) === Number(currentUserId)
                &&
                msg.messageable_type === currentUserType;

            const isOrg =
                (msg.messageable_type || '')
                .includes("Organization");

            const name = isOrg
                ? (msg.messageable?.organization_name || "Organization")
                : (msg.messageable?.name || "User");

            const firstLetter = name.charAt(0).toUpperCase();

            const time = msg.created_at
                ? new Date(msg.created_at).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                })
                : '';

            const html = $(`

                <div class="chat-message ${isMe ? 'me' : 'other'}"
                    style="margin-left:${level * 0}px">

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

                                ${level === 0 ? `
                                    <span class="reply-btn">
                                        ↩ Reply
                                    </span>
                                ` : ''}

                            </div>

                            ${level === 0 ? `

                                <div class="reply-box d-none">

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


            // =========================================
            // TOGGLE REPLY
            // =========================================

            html.find(".reply-btn").on("click", function () {

                html.find(".reply-box")
                    .toggleClass("d-none");
            });


            // =========================================
            // SEND REPLY
            // =========================================

            html.find(".send-reply-btn")
                .on("click", function () {

                if (!isAuthenticated) {

                    showLoginModal();

                    return;
                }

                const replyText = html
                    .find(".reply-text")
                    .val()
                    .trim();

                if (!replyText) return;

                sendMessage(replyText, msg.id);
            });


            // =========================================
            // REPLIES
            // =========================================

            if (msg.replies?.length) {

                const repliesContainer =
                    html.find(".replies");

                let expanded = false;

                function renderReplies() {

                    repliesContainer.empty();

                    // remove class first
                    html.removeClass("show-reply");

                    if (expanded) {

                        // add class in main parent chat-message
                        html.addClass("show-reply");

                        msg.replies.forEach(reply => {

                            repliesContainer.append(
                                createMessage(reply, level + 1)
                            );
                        });
                    }

                    const toggleBtn = $(`
                        <div class="view-more">
                            ${expanded
                                ? 'Hide replies'
                                : `View replies (${msg.replies.length})`
                            }
                        </div>
                    `);

                    toggleBtn.on("click", function () {

                        expanded = !expanded;

                        renderReplies();
                    });

                    repliesContainer.append(toggleBtn);
                }

                renderReplies();
            }

            return html;
        }


        // =====================================================
        // FORM SUBMIT
        // =====================================================

        messageForm.on("submit", function(e) {

            e.preventDefault();

            if (!isAuthenticated) {

                showLoginModal();

                return;
            }

            const message =
                messageInput.val().trim();

            if (!message) return;

            sendMessage(message);
        });


        // =====================================================
        // CHAT TOGGLE
        // =====================================================

        $("#chatToggle").on("click", function() {

            $("#chatBox")
                .toggleClass("show");
        });

        $("#closeChat").on("click", function() {

            $("#chatBox")
                .removeClass("show");
        });


        // =====================================================
        // INITIAL LOAD
        // =====================================================

        fetchMessages();


        // =====================================================
        // REALTIME POLLING
        // =====================================================

        // setInterval(() => {

        //     fetchMessages();

        // }, 2000);



    // =====================================================
    // TIMELINE
    // =====================================================
    let selectedMonth = new Date().getMonth() + 1;
    let selectedYear = new Date().getFullYear();

    let category = '';
    let search = '';

    let map;
    let markerGroup;
    let debounceTimer;

    function scrollChipIntoView(el) {

        if (!el?.length) return;

        el[0].scrollIntoView({
            behavior: 'smooth',
            inline: 'center',
            block: 'nearest'
        });
    }

    scrollChipIntoView($('.timeline-chip.active'));

    $('.timeline-chip').on('click', function () {

        $('.timeline-chip').removeClass('active');
        $(this).addClass('active');

        selectedMonth = $(this).data('month');
        selectedYear = $(this).data('year');

        scrollChipIntoView($(this));

        loadData();
    });

    // =====================================================
    // SEARCH
    // =====================================================

    $(document).on('input', '#search', function () {

        search = $(this).val() || '';

        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(loadData, 300);
    });

    // =====================================================
    // CATEGORY FILTER
    // =====================================================

    $(document).on('click', '.nav-link[data-slug]', function (e) {

        e.preventDefault();

        category = $(this).data('slug');

        $('.nav-link[data-slug]').removeClass('active');

        $(this).addClass('active');

        loadData();
    });

    // =====================================================
    // HORIZONTAL SCROLL
    // =====================================================

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

    // =====================================================
    // MAP
    // =====================================================

    navigator.geolocation.getCurrentPosition(

        function (position) {

            initializeMap(
                position.coords.latitude,
                position.coords.longitude
            );
        },

        function () {

            initializeMap(19.0760, 72.8777);
        }
    );

    function initializeMap(lat, lng) {

        if (map) {
            map.remove();
        }

        map = L.map('map').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
                attribution: '&copy; OpenStreetMap contributors'}
        ).addTo(map);

        markerGroup = L.markerClusterGroup();

        map.addLayer(markerGroup);

        map.on('moveend', function () {

            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(loadData, 300);
        });

        loadData();
    }

    // =====================================================
    // LOAD MAP DATA
    // =====================================================

    function loadData() {
        // console.log("Here");

        if (!map || !markerGroup) return;

        const bounds = map.getBounds();

        $.ajax({

            url: "{{ route('map.data') }}",
            type: "GET",

            data: {
                category,
                month: selectedMonth,
                year: selectedYear,
                search,
                minLat: bounds.getSouth(),
                maxLat: bounds.getNorth(),
                minLng: bounds.getWest(),
                maxLng: bounds.getEast()
            },

            success: function (response) {
                // if (!response.events) {
                //     $('#event-container').html(`
                //        <div class="col-12">
                //             <div class="text-center py-5">
                //                 <h4>No Data Found</h4>
                //             </div>
                //         </div>
                //     `);
                // } else {
                //     $('#event-container').html(response.events);
                // }

                // if (typeof AOS !== 'undefined') {
                    // AOS.refreshHard();
                // }
                markerGroup.clearLayers();

                if (
                    !response.events_map ||
                    response.events_map.length === 0
                ) {

                    $('#sidebar-event-container').html(`
                        <div class="mapmenu-event-no-data p-3">
                            <span class="fs-4"><i class="fa-regular fa-calendar-xmark"></i></span>
                            No Data Found
                        </div>
                    `);

                    $('#total_events').html('(0)');

                    markerGroup.clearLayers();

                    return;
                }
                $('#sidebar-event-container').html(
                    response.sidebar || ''
                );

                const events = response.events_map || [];

                $('#total_events').html(`(${events.length})`);

                if (!events.length) {
                    return;
                }
                events.forEach(function (event) {

                    if (!event.latitude || !event.longitude) {
                        return;
                    }
                    // const baseEventUrl = "{{ route('event-detail') }}";
                    // const url = event.slug ? `{{ route('event-detail') }}/${event.slug}` : baseEventUrl;
                    const popupContent = `
                        <a href="{{ route('event-detail') }}/${event.slug}"
                           class="event-card-link">

                            <div class="event-card-popup">

                                <img src="${event.image_url ?? ''}"
                                     class="event-img" />

                                <div class="event-body">

                                    <h3>
                                        ${escapeHtml(event.title)}
                                    </h3>

                                    <p>
                                        📍 ${escapeHtml(event.venue_name)}
                                    </p>

                                    <p>
                                        🕒 ${event.formatted_date ?? ''}
                                    </p>

                                </div>

                            </div>

                        </a>
                    `;

                    const marker = L.marker([
                        parseFloat(event.latitude),
                        parseFloat(event.longitude)
                    ]).bindPopup(popupContent);

                    markerGroup.addLayer(marker);
                });

            },

            error: function (xhr, status, error) {

                ajaxError("Map data load failed", error);

                $('#sidebar-event-container').html(`
                    <div class="mapmenu-event-no-data p-3">
                        <span class="fs-4"><i class="fa-solid fa-rotate-right"></i></span>
                        Failed to load data
                    </div>
                `);
            }
        });
    }

    // =====================================================
    // INIT
    // =====================================================

    // fetchMessages();

});


</script>
@endsection
