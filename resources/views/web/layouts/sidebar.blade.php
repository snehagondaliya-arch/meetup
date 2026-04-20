<div class="main-tool-page">
    <!-- Category-Side-menu -->
    <div class="side-menu-section" id="side-menu-section">
        <div class="side-menu-card card">
            <div class="card-body p-0">
                <div class="card-content h-100 d-flex flex-column">
                    <div class="side-menu-list">
                        <ul class="navbar-nav side-menu-nav ms-auto my-3 gap-1">
                            @foreach ($categories as $category)
                                <li class="nav-item">
                                    {{-- <a class="nav-link {{ request()->segment(2) == $category->slug ? 'active' : '' }}"
                                        href="{{ url('/events/' . $category->slug) }}">
                                        <span class="link-icon"><i class="fa fa-{{ $category->icon }}"></i></span>
                                        {{ $category->name }} --}}

                                        <a href="#"
                                            class="nav-link {{ request()->segment(2) == $category->slug ? 'active' : '' }}"
                                            data-slug="{{ $category->slug }}">
                                            <i class="fa-solid fa-{{ $category->icon }}"></i>
                                            <span>{{ $category->name }}</span>
                                        </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="side-menu-overlay d-lg-none d-block" id="sideMenuOverlay"></div>
</div>
<!-- Category-Side-menu-end -->