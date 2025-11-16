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
            <a class="nav-link {{ request()->routeIs('admin.masterdata.trainers.*') ? 'active' : '' }}"
               href="{{ route('admin.masterdata.trainers.index') }}"
               style="{{ request()->routeIs('admin.masterdata.trainers.*') ? 'color: var(--admin-primary); border-bottom: 2px solid var(--admin-primary);' : 'color: var(--admin-text-light);' }}">
                Trainers
            </a>
        </li>
        {{-- Special case for homepage, it only has an edit page --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.masterdata.homepage.*') ? 'active' : '' }}"
               href="{{ route('admin.masterdata.homepage.edit') }}"
               style="{{ request()->routeIs('admin.masterdata.homepage.*') ? 'color: var(--admin-primary); border-bottom: 2px solid var(--admin-primary);' : 'color: var(--admin-text-light);' }}">
                Homepage
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.masterdata.notifications.*') ? 'active' : '' }}"
               href="{{ route('admin.masterdata.notifications.index') }}" 
               style="{{ request()->routeIs('admin.masterdata.notifications.*') ? 'color: var(--admin-primary); border-bottom: 2px solid var(--admin-primary);' : 'color: var(--admin-text-light);' }}">
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
