{{-- File: resources/views/components/navbar.blade.php --}}
{{-- Diadaptasi dari Navigation.tsx --}}

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom shadow-sm">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('home') }}">
            {{-- Pastikan logo.png ada di folder public/images/ --}}
            <img src="{{ asset('images/Logo.png') }}" alt="GRIT Fitness Logo" style="height: 40px; width: auto;">
            {{-- Atur 'height' sesuai kebutuhan --}}
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
                    // Definisikan link publik (sesuai Navigation.tsx)
                    $publicLinks = [
                        ['name' => 'Beranda', 'route' => 'home'],
                        ['name' => 'Kelas & Trainer', 'route' => 'classes'], // Sesuaikan nama route jika berbeda
                        ['name' => 'Membership', 'route' => 'membership'],
                        // Tambahkan link lain seperti 'Tentang Kami' jika ada route-nya
                        // ['name' => 'Tentang Kami', 'route' => 'about'],
                    ];
                @endphp

                {{-- Link Dashboard untuk Member (Sesuai Navigation.tsx) --}}
                @auth
                    @if(Auth::user()->isMember())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('member.dashboard') ? 'active fw-semibold' : '' }}"
                               style="{{ request()->routeIs('member.dashboard') ? 'color: var(--grit-accent);' : 'color: var(--grit-text);' }}"
                               href="{{ route('member.dashboard') }}">
                               <i class="bi bi-layout-text-sidebar-reverse me-1"></i> Dashboard
                            </a>
                        </li>
                    @endif
                @endauth

                {{-- Link Publik --}}
                @foreach ($publicLinks as $link)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs($link['route']) ? 'active fw-semibold' : '' }}"
                           style="{{ request()->routeIs($link['route']) ? 'color: var(--grit-accent);' : 'color: var(--grit-text);' }}"
                           href="{{ route($link['route']) }}">
                           {{-- Ganti ikon jika diperlukan --}}
                           {{-- <i class="bi bi-house me-1"></i> --}}
                           {{ $link['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Right Actions (Sesuai Navigation.tsx) --}}
            <div class="d-flex align-items-center">
                @guest
                    {{-- Tombol Login & Register untuk Guest --}}
                    <a href="{{ route('login') }}" class="btn btn-link text-decoration-none me-2" style="color: var(--grit-primary);">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-accent text-white">
                        Daftar Sekarang
                    </a>
                @else
                    {{-- Dropdown Profil untuk Member atau Tombol Admin --}}
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-person-gear me-1"></i> Admin Panel
                        </a>
                    @else {{-- Anggap selain admin adalah member --}}
                        <div class="dropdown">
                            <button class="btn border-0 d-flex align-items-center p-0" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{-- Avatar Placeholder --}}
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; background-color: var(--grit-primary); color: white;">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="profileDropdown">
                                {{-- Menu dari Navigation.tsx memberLinks & logout --}}
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('member.dashboard') }}">
                                        <i class="bi bi-layout-text-sidebar-reverse me-2 text-muted"></i> Dashboard Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('member.profile') }}">
                                        <i class="bi bi-person me-2 text-muted"></i> Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('member.payment') }}">
                                        <i class="bi bi-credit-card me-2 text-muted"></i> Riwayat Pembayaran
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider mx-3"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Styling Tambahan --}}
<style>
    .navbar-nav .nav-link.active {
        color: var(--grit-accent) !important;
    }
    .navbar-nav .nav-link {
         transition: color 0.2s ease-in-out;
         padding-left: 1rem;
         padding-right: 1rem;
    }
    .navbar-nav .nav-link:hover {
        color: var(--grit-accent) !important;
    }
    .dropdown-item {
        font-size: 0.9rem;
    }
    .dropdown-item i {
        width: 1.25rem; /* Align icons */
        text-align: center;
    }
    .navbar-toggler:focus {
        box-shadow: none; /* Remove default blue focus ring */
    }
</style>