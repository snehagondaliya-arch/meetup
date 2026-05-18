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
// Auth Jquery
// =====================
    $(document).ready(function () {
        $('#registrationForm').on('submit', function (e) {
            e.preventDefault(); 
            $('.error-text').text('');
            $.ajax({
                url: window.appUrls.register,
                type: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // $('#registrationForm')[0].reset();
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;

                    // Clear previous errors
                    $('.error-text').text('');

                    $.each(errors, function (key, value) {
                        $('#' + key + '_error').text(value[0]);
                    });
                }
            });
        });
        $('#loginForm').on('submit', function (e) {
            e.preventDefault();
            $('.error-text').text('');
             $.ajax({
                url: window.appUrls.login,
                type: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // $('#loginForm')[0].reset();
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                },
               error: function (xhr) {

                    if (xhr.status === 419) {
                        $('#login-error').text('Session expired. Refresh page.');
                        return;
                    }

                    if (xhr.status === 401 && xhr.responseJSON.message) {
                        $('#login-error').text(xhr.responseJSON.message);
                    }

                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            $('#' + key + '_error').text(value[0]);
                        });
                    }
                }
            });
        });
    });
