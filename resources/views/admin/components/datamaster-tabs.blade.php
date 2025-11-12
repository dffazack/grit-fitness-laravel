{{-- ========================================================================= --}}
{{-- 1. resources/views/admin/components/datamaster-tabs.blade.php --}}
{{-- ========================================================================= --}}

{{-- Header --}}
<div class="mb-4">
    <h1 class="h3 h2-sm fw-bold" style="color: var(--admin-primary);">Kelola Data Master</h1>
    <p class="text-muted mb-0 small">Kelola data Trainers, Homepage, dan Notifikasi</p>
</div>

{{-- Tab Navigasi - Responsive --}}
<div class="nav-tabs-wrapper mb-4">
    <ul class="nav nav-tabs flex-nowrap overflow-auto" style="border-bottom-color: var(--admin-border); -webkit-overflow-scrolling: touch;">
        <li class="nav-item flex-shrink-0">
            <a class="nav-link {{ request()->routeIs('admin.trainers.*') ? 'active' : '' }}"
               href="{{ route('admin.trainers.index') }}"
               style="{{ request()->routeIs('admin.trainers.*') ? 'color: var(--admin-primary); border-bottom: 2px solid var(--admin-primary);' : 'color: var(--admin-text-light);' }}">
                <i class="bi bi-person-badge me-1 d-none d-sm-inline"></i>
                <span>Trainers</span>
            </a>
        </li>
        <li class="nav-item flex-shrink-0">
            <a class="nav-link {{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}"
               href="{{ route('admin.homepage.index') }}"
               style="{{ request()->routeIs('admin.homepage.*') ? 'color: var(--admin-primary); border-bottom: 2px solid var(--admin-primary);' : 'color: var(--admin-text-light);' }}">
                <i class="bi bi-house-door me-1 d-none d-sm-inline"></i>
                <span>Homepage</span>
            </a>
        </li>
        <li class="nav-item flex-shrink-0">
            <a class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}"
               href="{{ route('admin.notifications.index') }}" 
               style="{{ request()->routeIs('admin.notifications.*') ? 'color: var(--admin-primary); border-bottom: 2px solid var(--admin-primary);' : 'color: var(--admin-text-light);' }}">
                <i class="bi bi-bell me-1 d-none d-sm-inline"></i>
                <span>Notifikasi</span>
            </a>
        </li>
    </ul>
</div>

<style>
    /* Tab Navigation Responsive */
    .nav-tabs-wrapper {
        position: relative;
    }
    
    .nav-tabs {
        border-bottom: 2px solid var(--admin-border);
        gap: 0.5rem;
    }
    
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        padding: 0.75rem 1rem;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    
    .nav-tabs .nav-link.active {
        background-color: transparent;
        font-weight: 600;
    }
    
    .nav-tabs .nav-link:hover {
        color: var(--admin-primary) !important;
        border-bottom-color: var(--admin-primary);
    }
    
    /* Mobile: Smaller tabs */
    @media (max-width: 576px) {
        .nav-tabs .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
    }
    
    /* Responsive heading */
    @media (max-width: 576px) {
        .h2-sm {
            font-size: 1.5rem !important;
        }
    }
</style>
{{-- Modified by: User-Interfaced Team -- }}