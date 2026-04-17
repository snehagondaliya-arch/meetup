<nav class="navbar header-s2 navbar-expand-lg navbar-light py-3">
    <div class="container">
        <a class="navbar-brand fw-600" href="index.html">
            <!-- <img src="#" alt="logo" style="max-width: 200px;" class="d-sm-block d-none">
                    <img src="#" alt="logo" style="max-width: 40px;" class="d-block d-sm-none"> -->
            <h3 class="fs-24px gt-text-theme fw-600 mb-0">EventTime</h3>
        </a>
        <div class="d-lg-block d-none mx-auto">
            <ul class="navbar-nav custom-scroll px-lg-0 px-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index')}}">
                        Home
                    </a>
                </li>
                @if (!trim($__env->yieldContent('no-event-blog')))
                     <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('event-list') ? 'active' : '' }}" href="{{ route('event-list')}}">
                            Event
                        </a>
                    </li>
                     <li class="nav-item">
                    <a class="nav-link" href="#">
                        Blog
                    </a>
                </li>
                @endif
               @if (!trim($__env->yieldContent('no-faq-disclaimer')))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq')}}">
                            Faq's
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('disclaimer') ? 'active' : '' }}" href="{{ route('disclaimer')}}">
                            Disclaimer
                        </a>
                    </li>
                 @endif
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                        Contact Us
                    </a>
                </li>
            </ul>
        </div>

        <div class="d-flex align-items-center gap-2 ms-lg-0 ms-auto">
            <!-- -After-signIn-profileIcon -->
            <!-- <ul class="list-unstyled user-profile mb-0 ms-lg-2">
                        <li class="nav-item dropdown ">
                            <a href="javascript:void(0);" class="nav-link d-flex align-items-center gap-2" id="notification-drop" data-bs-toggle="dropdown">
                                <div class="profile-image">
                                    <img src="./assets/images/user-profile.png" style="width: 50px; height: 50px;" class="img-fluid" alt="user">
                                </div>
                            </a>
                            <div class="p-0 sub-drop dropdown-menu dropdown-s1 dropdown-menu-end" aria-labelledby="notification-drop">
                                <div class="m-0 card ">
                                    <div class="p-0 card-body">
                                        <a href="javascript:void(0);" class="iq-sub-card">
                                            <div class="d-flex align-items-center p-3 border-bottom">
                                                <div class="gt-bg-theme hw-50px d-flex align-items-center justify-content-center  px-3 py-2 rounded-pill">
                                                    <i class="fa-solid fa-user text-white fs-20px"></i>
                                                </div>
                                                <div class="ms-2 flex-grow-1 text-start gt-text-ffffff">
                                                    <h6 class="mb-0 gt-text-title">Profile</h6>
                                                    <p class="mb-0 fs-14px text-muted">View Profile</p>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="iq-sub-card d-flex justify-content-center p-3">
                                            <a href="javascript:void(0);" class="btn btn-outline-primary w-100">
                                                Sign out
                                                <i class="fa-solid fa-arrow-right-from-bracket ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul> -->

            <!-- Login-btn -->
            <a href="javascript:void(0);" class="btn btn-primary header-btn" data-bs-toggle="modal"
                data-bs-target="#loginBackdrop">Sign In</a>

            <!-- Side-menu-toggle-btn -->
            <button class="navbar-toggler header-s1toggle-btn topbar-click-menu shadow-none border-0" type="button"
                id="categoryButton">
                <div class="position-relative w-100">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
        </div>
    </div>
</nav>