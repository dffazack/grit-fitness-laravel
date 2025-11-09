{{-- Header --}}
<div class="mb-4">
    <h1 class="h3 fw-bold" style="color: var(--admin-primary);">Kelola Data Master</h1>
    <p class="text-muted">Kelola data Membership, Fasilitas, Trainers, Homepage, dan Notifikasi</p>
</div>

{{-- Tab Navigasi --}}
<div class="overflow-x-auto">
    <ul class="nav nav-tabs flex-nowrap mb-4" style="border-bottom-color: var(--admin-border);">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.memberships.*') ? 'active' : '' }}"
               href="{{ route('admin.memberships.index') }}"
               style="{{ request()->routeIs('admin.memberships.*') ? 'color: var(--admin-primary); border-bottom: 2px solid var(--admin-primary);' : 'color: var(--admin-text-light);' }}">
                Paket Membership
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}"
               href="{{ route('admin.facilities.index') }}"
               style="{{ request()->routeIs('admin.facilities.*') ? 'color: var(--admin-primary); border-bottom: 2px solid var(--admin-primary);' : 'color: var(--admin-text-light);' }}">
                Fasilitas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.trainers.*') ? 'active' : '' }}"
               href="{{ route('admin.trainers.index') }}"
               style="{{ request()->routeIs('admin.trainers.*') ? 'color: var(--admin-primary); border-bottom: 2px solid var(--admin-primary);' : 'color: var(--admin-text-light);' }}">
                Trainers
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}"
               href="{{ route('admin.homepage.index') }}"
               style="{{ request()->routeIs('admin.homepage.*') ? 'color: var(--admin-primary); border-bottom: 2px solid var(--admin-primary);' : 'color: var(--admin-text-light);' }}">
                Homepage
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}"
               href="{{ route('admin.notifications.index') }}" 
               style="{{ request()->routeIs('admin.notifications.*') ? 'color: var(--admin-primary); border-bottom: 2px solid var(--admin-primary);' : 'color: var(--admin-text-light);' }}">
                Notifikasi
            </a>
        </li>
    </ul>
</div>

<style>
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        margin-right: 1rem;
    }
    .nav-tabs .nav-link.active {
        background-color: transparent;
    }
</style>
