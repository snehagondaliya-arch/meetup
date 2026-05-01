<footer class="section-s1padding footer-s1 bg-white pb-3">
    <div class="container">
        <div class="row g-3">
            <div class="col-12 col-lg-6 mb-lg-0 mb-2">
                <a class="fw-600 nav-logo navbar-brand" href="#">
                    <!-- <img src="javascript:void(0);" alt="logo" class="logo-lg"> -->
                    <h3 class="fs-24px gt-text-theme fw-600 mb-0">EventTime</h3>
                </a>
                <p class="mt-sm-3 mt-2 mb-0 gt-text-dcdcdc"><a href="javascript:void(0);">eventtime.com</a> Tools gives
                    simple, fast, and free online tools to edit images, video compress, convert files, and manage social
                    media content directly in your browser.</p>
            </div>

            <div class="col-sm-4 col-lg-2 ms-lg-auto">
                <h5>Discover</h5>
                <ul class="list-unstyled d-inline-block mb-0">
                    <li class="py-1"><a href="{{ route('event-list') }}" class="nav-link">Events</a></li>
                    {{-- <li class="py-1"><a href="javascript:void(0);" class="nav-link">Cities</a></li>
                    <li class="py-1"><a href="javascript:void(0);" class="nav-link">Online Events</a></li> --}}
                </ul>
            </div>

            <div class="col-sm-4 col-lg-2 ms-lg-auto">
                <h5>Support</h5>
                <ul class="list-unstyled d-inline-block mb-0">
                    <li class="py-1"><a href="{{ route('index') }}" class="nav-link">Home</a></li>
                    {{-- <li class="py-1"><a href="javascript:void(0);" class="nav-link">Blogs</a></li> --}}
                    <li class="py-1"><a href="{{ route('faq') }}" class="nav-link">FAQs</a></li>
                </ul>
            </div>
            <div class="col-sm-4 col-lg-2">
                <h5>Legal Pages</h5>
                <ul class="list-unstyled d-inline-block mb-0">
                    <li class="py-1"><a href="{{ route('about') }}" class="nav-link">About Us</a></li>
                    <li class="py-1"><a href="{{ route('contact') }}" class="nav-link">Contact Us</a></li>
                    <li class="py-1"><a href="{{ route('privacy-policy') }}" class="nav-link">Privacy Policy</a></li>
                    <li class="py-1"><a href="{{ route('term-condition') }}" class="nav-link">Terms and Conditions</a></li>
                </ul>
            </div>
        </div>
        <div class="row footer-bottom pt-3 mt-3 border-top">
            <div class="col-12 text-center">
                <p class="mb-0 small">© 2026 <a href="javascript:void(0);" class="gt-text-theme">eventtime.com</a>. All
                    rights reserved.</p>
            </div>
        </div>
    </div>
</footer>