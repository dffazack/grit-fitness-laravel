{{-- File: resources/views/components/navbar.blade.php --}}

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom shadow-sm" style="z-index: 1050;">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/Logo.png') }}" alt="GRIT Fitness Logo" style="height: 40px; width: auto;">
        </a>

        {{-- Mobile Toggle --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Nav Links --}}
        <div class="collapse navbar-collapse" id="mainNavbar">
            {{-- Center Links (Public) --}}
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                @php
                    $publicLinks = [
                        ['name' => 'Beranda', 'route' => 'home'],
                        ['name' => 'Kelas & Trainer', 'route' => 'classes'],
                        ['name' => 'Membership', 'route' => 'membership'],
                    ];
                @endphp

                @auth('web')
                    <li class="nav-item">
                        @php
                            $dashboardRoute = Route::has('member.dashboard') ? route('member.dashboard') : route('home');
                            $isActive = request()->routeIs('member.dashboard');
                        @endphp
                        <a class="nav-link {{ $isActive ? 'active fw-semibold' : '' }}"
                           style="{{ $isActive ? 'color: var(--grit-accent);' : 'color: var(--grit-text);' }}"
                           href="{{ $dashboardRoute }}">
                           <i class="bi bi-layout-text-sidebar-reverse me-1"></i> Dashboard
                        </a>
                    </li>
                @endauth

                @foreach ($publicLinks as $link)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs($link['route']) ? 'active fw-semibold' : '' }}"
                           style="{{ request()->routeIs($link['route']) ? 'color: var(--grit-accent);' : 'color: var(--grit-text);' }}"
                           href="{{ route($link['route']) }}">
                           {{ $link['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Right Actions --}}
            <div class="d-flex align-items-center">

                @auth('admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary me-2">
                        <i class="bi bi-person-gear me-1"></i> Admin Panel
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                        </button>
                    </form>

                                    @elseauth('web')
                        {{-- Member is logged in --}}
                        <div class="dropdown">
                            <button class="btn border-0 p-0 rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: var(--grit-primary); color: white;">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                @endif
                            </button>

                            <ul class="dropdown-menu shadow-lg border-0 rounded-3" 
                                style="
                                    position: fixed !important;
                                    top: 50% !important;
                                    left: 50% !important;
                                    transform: translate(-50%, -50%) !important;
                                    min-width: 280px;
                                    max-width: 90vw;
                                    z-index: 2000;
                                ">
                                <div class="text-center py-3 border-bottom">
                                    <a href="{{ route('member.profile') }}">
                                        @if(Auth::user()->profile_photo)
                                            <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="rounded-circle mx-auto mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: var(--grit-primary); color: white;">
                                                <i class="bi bi-person-fill fs-3"></i>
                                            </div>
                                        @endif
                                    </a>
                                    <h6 class="mb-0 fw-bold">{{ Auth::user()->name }}</h6>
                                    <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
                                </div>

                                <li><a class="dropdown-item py-3" href="{{ route('member.dashboard') }}">
                                    <i class="bi bi-layout-text-sidebar-reverse me-3"></i> Dashboard Saya
                                </a></li>
                                <li><a class="dropdown-item py-3" href="{{ route('member.profile') }}">
                                    <i class="bi bi-person me-3"></i> Profil Saya
                                </a></li>
                                <li><a class="dropdown-item py-3" href="{{ route('member.payment') }}">
                                    <i class="bi bi-credit-card me-3"></i> Riwayat Pembayaran
                                </a></li>
                                <li><a class="dropdown-item py-3" href="{{ route('member.bookings.index') }}">
                                    <i class="bi bi-calendar-check me-3"></i> Booking Saya
                                </a></li>
                                <li><hr class="dropdown-divider mx-4"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-3 text-danger w-100">
                                            <i class="bi bi-box-arrow-right me-3"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                @else
                    {{-- 3. Guest (belum login) --}}
                    <a href="{{ route('login') }}" class="btn btn-link text-decoration-none me-3" style="color: var(--grit-primary);">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-accent text-white px-4">
                        Daftar Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>