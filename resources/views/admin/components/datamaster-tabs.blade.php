{{-- Header --}}
<div class="mb-4">
    <h1 class="h3 fw-bold" style="color: var(--admin-primary);">Kelola Data Master</h1>
    <p class="text-muted">Kelola data Trainers, Homepage, dan Notifikasi</p>
</div>

{{-- Tab Navigasi --}}
<ul class="nav nav-tabs mb-4" style="border-bottom-color: var(--admin-border);">
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

