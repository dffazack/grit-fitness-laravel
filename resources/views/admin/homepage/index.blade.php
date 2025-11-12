{{-- ========================================================================= --}}
{{-- 7. resources/views/admin/homepage/index.blade.php --}}
{{-- ========================================================================= --}}

@extends('layouts.admin')

@section('title', 'Data Master - Homepage')

@section('content')

    @include('admin.components.datamaster-tabs')

    <div class="d-flex flex-column gap-3 gap-md-4">

        {{-- Hero Banner --}}
        <div class="card border-0 shadow-sm hover-card">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; background: rgba(43, 50, 130, 0.1);">
                                <i class="bi bi-image" style="color: var(--admin-primary);"></i>
                            </div>
                            <h5 class="card-title-custom mb-0">Hero Banner</h5>
                        </div>
                        <p class="text-muted mb-0 small">Headline, subheadline, dan gambar utama di halaman depan.</p>
                    </div>
                    <a href="{{ route('admin.homepage.edit') }}#hero" class="btn btn-outline-primary flex-shrink-0">
                        <i class="bi bi-pencil-fill me-1"></i> 
                        <span class="d-none d-sm-inline">Edit Banner</span>
                        <span class="d-sm-none">Edit</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Features & Benefits --}}
        <div class="card border-0 shadow-sm hover-card">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; background: rgba(229, 27, 131, 0.1);">
                                <i class="bi bi-stars" style="color: var(--admin-accent);"></i>
                            </div>
                            <h5 class="card-title-custom mb-0">Features & Benefits</h5>
                        </div>
                        <p class="text-muted mb-0 small">4 poin keunggulan (misal: Expert Trainers, Modern Equipment).</p>
                    </div>
                    <a href="{{ route('admin.homepage.edit') }}#benefits" class="btn btn-outline-primary flex-shrink-0">
                        <i class="bi bi-pencil-fill me-1"></i> 
                        <span class="d-none d-sm-inline">Edit Features</span>
                        <span class="d-sm-none">Edit</span>
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Statistics --}}
        <div class="card border-0 shadow-sm hover-card">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; background: rgba(25, 135, 84, 0.1);">
                                <i class="bi bi-graph-up" style="color: #198754;"></i>
                            </div>
                            <h5 class="card-title-custom mb-0">Statistics</h5>
                        </div>
                        <p class="text-muted mb-0 small">4 poin statistik (misal: Member Aktif, Tahun Berdiri).</p>
                    </div>
                    <a href="{{ route('admin.homepage.edit') }}#stats" class="btn btn-outline-primary flex-shrink-0">
                        <i class="bi bi-pencil-fill me-1"></i> 
                        <span class="d-none d-sm-inline">Edit Stats</span>
                        <span class="d-sm-none">Edit</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Testimonials --}}
        <div class="card border-0 shadow-sm hover-card">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; background: rgba(255, 193, 7, 0.1);">
                                <i class="bi bi-chat-quote" style="color: #ffc107;"></i>
                            </div>
                            <h5 class="card-title-custom mb-0">Testimonials</h5>
                        </div>
                        <p class="text-muted mb-0 small">3 testimoni dari member.</p>
                    </div>
                    <a href="{{ route('admin.homepage.edit') }}#testimonials" class="btn btn-outline-primary flex-shrink-0">
                        <i class="bi bi-pencil-fill me-1"></i> 
                        <span class="d-none d-sm-inline">Edit Testimonials</span>
                        <span class="d-sm-none">Edit</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush
{{-- Modified by: User-Interfaced Team -- }}