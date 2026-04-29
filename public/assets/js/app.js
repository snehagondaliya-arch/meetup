// Scrolling-Animation
AOS.init();


// =====================
// Side Menu (Category)
// =====================
const categoryBtn = document.getElementById('categoryButton');
const categoryCloseBtn = document.getElementById('menuClose');
const sideCategoryMenu = document.getElementById('side-menu-section');
const categoryOverlay = document.getElementById('sideMenuOverlay');

function openCategoryMenu() {
    sideCategoryMenu.classList.add('categoryOpen');
    categoryOverlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeCategoryMenu() {
    sideCategoryMenu.classList.remove('categoryOpen');
    categoryOverlay.classList.remove('show');
    document.body.style.overflow = '';
}

if (categoryBtn) categoryBtn.addEventListener('click', openCategoryMenu);
if (categoryCloseBtn) categoryCloseBtn.addEventListener('click', closeCategoryMenu);
if (categoryOverlay) categoryOverlay.addEventListener('click', closeCategoryMenu);


// =====================
// Header Side Menu
// =====================
const headerMenuBtn = document.getElementById('menuToggle');
const headerMenuCloseBtn = document.getElementById('menuClose'); // ⚠️ if same ID, consider renaming in HTML
const headerSideMenu = document.getElementById('sideMenu');
const headerOverlay = document.getElementById('menuOverlay');

function openHeaderMenu() {
    headerSideMenu.classList.add('open');
    headerOverlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeHeaderMenu() {
    headerSideMenu.classList.remove('open');
    headerOverlay.classList.remove('show');
    document.body.style.overflow = '';
}

if (headerMenuBtn) headerMenuBtn.addEventListener('click', openHeaderMenu);
if (headerMenuCloseBtn) headerMenuCloseBtn.addEventListener('click', closeHeaderMenu);
if (headerOverlay) headerOverlay.addEventListener('click', closeHeaderMenu);
$(window).on('scroll', function () {
    if ($(window).scrollTop() > 320) {
        $('.header-s1').addClass('scrolled');
    } else {
        $('.header-s1').removeClass('scrolled');
    }
});

// Apply start animation to ALL buttons with class "animation-btn"
document.querySelectorAll('.animation-btn').forEach(button => {

    button.addEventListener('click', function (e) {
        const circle = document.createElement("span");
        const diameter = Math.max(this.clientWidth, this.clientHeight);
        const radius = diameter / 2;

        circle.style.width = circle.style.height = `${diameter}px`;
        circle.style.left = `${e.clientX - this.getBoundingClientRect().left - radius}px`;
        circle.style.top = `${e.clientY - this.getBoundingClientRect().top - radius}px`;
        circle.classList.add("start");

        const start = this.getElementsByClassName("start")[0];
        if (start) {
            start.remove();
        }
        this.appendChild(circle);
    });

});

// Hero-Slider
var swiper = new Swiper(".mySwiper", {
    autoplay: true,
    loop: true,
    autoplay: {
        delay: 1400,
    },
});



// Event Info CTA Slider
var swiper = new Swiper(".evCTASlider", {
    loop: true,
    autoplay: {
        delay: 2500,
    },
    speed: 800,
    effect: "slide"
});

// Upcoming-Event Slider 
var reviewSwiper = new Swiper(".upcomingEvent", {
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
        576: {
            slidesPerView: 2
        },
        992: {
            slidesPerView: 3
        }
    }
});