@extends('layouts.master')
@section('title', config('app.name'))
@section('no-sidebar', true)
@php
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
@section('content')
    <div class="d-flex position-relative" style="height: 80vh;">

        <!-- MAP -->
        <div id="map" class="w-75"></div>

        <!-- TIMELINE FILTER -->
            <div class="timeline-wrapper">

                <div class="timeline-container shadow">

                    {{-- <div class="timeline-arrow" id="scrollLeft">‹</div> --}}

                    <div id="timelineScroll" class="timeline-scroll">
                        @foreach($months as $item)
                            <div class="timeline-chip 
                                                {{ $item->month == $currentMonth && $item->year == $currentYear ? 'active' : '' }}"
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

                <div class="mb-2">
                    <input type="text" id="search" class="form-control rounded-3"
                        placeholder="Search by location, event name...">
                </div>

                <div class="small text-muted mb-2">
                    <a href="">All Events <span class="fw-semibold"></span></a>
                </div>
            </div>

            <div id="event-container">
                @include('web.partials.map-events')
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(function () {
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

            let $activeChip = $('.timeline-chip.active');
            scrollChipIntoView($activeChip);

            $('.timeline-chip').on('click', function () {

                $('.timeline-chip').removeClass('active');
                $(this).addClass('active');

                scrollChipIntoView($(this));

                selectedMonth = $(this).data('month');
                selectedYear = $(this).data('year');

                // console.log('Selected Month:', selectedMonth);
                // console.log('Selected Year:', selectedYear);

                loadData();
            });
            // serach
            $(document).on('input', '#search', function () {
                search = $(this).val() || '';
                loadData();
            });
            // scroll
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

            // map
            let map;
            let markerGroup;
            let debounceTimer;

            navigator.geolocation.getCurrentPosition(function (position) {

                let lat = position.coords.latitude;
                let lng = position.coords.longitude;
                // console.log(lat,lng);

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

                // Initialize map
                map = L.map('map').setView([lat, lng], 13);

                // Add user marker
                var userMarker = L.marker([lat, lng], {
                    icon: userIcon
                }).addTo(map);

                setTimeout(() => {
                    map.invalidateSize();
                }, 200);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Marker cluster group
                markerGroup = L.markerClusterGroup();
                map.addLayer(markerGroup);

                // Map move event
                map.on('moveend', function () {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(loadData, 400);
                });
                loadData();
            });

            function loadData() {

                if (!map) return;

                markerGroup.clearLayers();

                let bounds = map.getBounds();

                $.ajax({
                    url: "{{ route('map.data') }}",
                    type: "GET",
                    data: {
                        minLat: bounds.getSouth(),
                        maxLat: bounds.getNorth(),
                        minLng: bounds.getWest(),
                        maxLng: bounds.getEast(),
                        month: selectedMonth || '',
                        year: selectedYear || '',
                        search: search || ''
                    },
                    success: function (response) {

                        $('#event-container').html(response.sidebar);

                        response.events.forEach(function (event) {

                            // console.log(event);

                            let popupContent = `
                            <a href="event-detail/${event.slug}" class="event-card-link">
                                <div class="event-card-popup">

                                    <img src="${event.image_url}" 
                                         alt="${event.title}" 
                                         class="event-img" />

                                    <div class="event-body">
                                        <h3>${event.title}</h3>

                                        <p>
                                            <strong>📍 Location:</strong> ${event.venue_name ?? 'N/A'}
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
                    }
                });
            }

        });
    </script>
@endsection

 {{-- <section class="search-section-s1 section-s1padding  position-relative">
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
                            <p class="text-muted change-fs-20px-18px">Whatever your interest, from hiking
                                and cooking to
                                tech and art — there’s a community waiting for you. Explore events happening
                                every day, meet
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
        </section> --}}



        <!-- Chat Box -->
        {{-- <div id="chatBox" class="chat-box">

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

        </div> --}}
        <!-- End Chat Box -->

{{-- 
        <script>
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

            // new messages    
            messageForm.on("submit", function (e) {

                e.preventDefault();

                if (!@json(Auth::guard('organization')->check() || Auth::guard('web')->check())) {
                    $("#loginBackdrop").modal("show");
                }

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

                let isMe = Number(msg.messageable_id) === Number(currentUserId) && msg.messageable_type === currentUserType;

                let isOrg = msg.messageable_type.includes("Organization");
                console.log(isOrg);

                let name = isOrg
                    ? (msg.messageable?.organization_name || "Organization")
                    : (msg.messageable?.name || "User");

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

                    if (!@json(Auth::guard('organization')->check() || Auth::guard('web')->check())) {
                        $("#loginBackdrop").modal("show");
                    }
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
            // let category = null;
            // let search = '';

            $('.event-select-s1').select2({
                dropdownCssClass: "event-select-s1Dropdown",
            });

            

            // SEARCH
            // $(document).on('input', '#search', function () {
            //     search = $(this).val();
            //     loadData();
            // });

            // $('select').on('change', function () {
            //     loadData();
            // });

            // let userLatitude = null;
            // let userLongitude = null;
            // navigator.geolocation.getCurrentPosition(function (position) {
            //     userLatitude = position.coords.latitude;
            //     userLongitude = position.coords.longitude;

            //     loadData(userLatitude, userLongitude);
            // });

        //     function loadData(latitude = userLatitude, longitude = userLongitude) {

        //         const date_filter = $('select[name="date_filter"]').val();
        //         const event_type = $('select[name="event_type"]').val();
        //         const distance = $('select[name="distance"]').val();
        //         $.ajax({
        //             url: "{{ route('index') }}",
        //             type: "GET",
        //             data: {
        //                 category: typeof category !== 'undefined' ? category : '',
        //                 search: typeof search !== 'undefined' ? search : '',
        //                 latitude: latitude,
        //                 longitude: longitude,
        //                 date_filter: date_filter,
        //                 event_type: event_type,
        //                 distance: distance ? distance : null
        //             },
        //             success: function (html) {
        //                 document.getElementById('event-container').innerHTML = html;

        //                 if (typeof AOS !== 'undefined') {
        //                     AOS.refreshHard();
        //                 }
        //             },
        //             error: function (xhr) {
        //                 console.log(xhr.responseText);
        //             }
        //         });
        //     }
        // });


        </script> --}}