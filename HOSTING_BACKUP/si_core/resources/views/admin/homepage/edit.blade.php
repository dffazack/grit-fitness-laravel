{{-- ========================================================================= --}}
{{-- 8. resources/views/admin/homepage/edit.blade.php - RESPONSIVE --}}
{{-- ========================================================================= --}}

@extends('layouts.admin')

@section('title', 'Edit Homepage Content')

@section('page-title', 'Edit Homepage Content')
@section('page-subtitle', 'Kelola konten homepage secara real-time')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.homepage.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i>
            <span class="d-none d-sm-inline">Kembali</span>
        </a>
    </div>
    
    {{-- Tabs - Responsive --}}
    <div x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'hero' }">
        <div class="overflow-auto mb-4" style="-webkit-overflow-scrolling: touch;">
            <ul class="nav nav-tabs flex-nowrap">
                <li class="nav-item">
                    <button @click="activeTab = 'hero'; window.location.hash = 'hero';" 
                            :class="{ 'active': activeTab === 'hero' }"
                            class="nav-link">
                        <i class="bi bi-image-fill me-1 d-none d-sm-inline"></i>
                        Hero Section
                    </button>
                </li>
                <li class="nav-item">
                    <button @click="activeTab = 'stats'; window.location.hash = 'stats';" 
                            :class="{ 'active': activeTab === 'stats' }"
                            class="nav-link">
                        <i class="bi bi-graph-up me-1 d-none d-sm-inline"></i>
                        Statistics
                    </button>
                </li>
                <li class="nav-item">
                    <button @click="activeTab = 'benefits'; window.location.hash = 'benefits';" 
                            :class="{ 'active': activeTab === 'benefits' }"
                            class="nav-link">
                        <i class="bi bi-stars me-1 d-none d-sm-inline"></i>
                        Benefits
                    </button>
                </li>
                <li class="nav-item">
                    <button @click="activeTab = 'testimonials'; window.location.hash = 'testimonials';" 
                            :class="{ 'active': activeTab === 'testimonials' }"
                            class="nav-link">
                        <i class="bi bi-chat-quote me-1 d-none d-sm-inline"></i>
                        Testimonials
                    </button>
                </li>
            </ul>
        </div>
        
        {{-- Hero Tab --}}
        <div x-show="activeTab === 'hero'" x-cloak>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title-custom mb-0">Edit Hero Section</h5>
                </div>
                <form action="{{ route('admin.homepage.hero') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body p-3 p-md-4">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Hero Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $hero->title ?? '' }}" placeholder="Transform Your Body, Transform Your Life" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Hero Subtitle</label>
                            <textarea name="subtitle" class="form-control" rows="3" required>{{ $hero->subtitle ?? '' }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Current Hero Image</label>
                            @if(isset($hero->image) && $hero->image)
                                @php
                                    $imageUrl = Str::startsWith($hero->image, ['http://', 'https://']) ? $hero->image : asset($hero->image);
                                @endphp
                                <img src="{{ $imageUrl }}" alt="Hero Preview" class="img-fluid rounded mb-2" style="max-height: 200px; border: 1px solid var(--admin-border);">
                                <p class="text-muted small">Current: {{ $hero->image }}</p>
                            @else
                                <p class="text-muted">No image set.</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image URL (Optional)</label>
                            <input type="url" name="image_url" class="form-control" value="" placeholder="https://images.unsplash.com/photo-...">
                            <small class="form-text text-muted">Enter a URL for the hero image. If a file is uploaded below, it will take precedence.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Upload Image File (Optional)</label>
                            <input type="file" name="image_file" class="form-control">
                            <small class="form-text text-muted">Upload an image file (max 5MB). If a URL is provided above, this file will take precedence.</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3 text-end">
                        <button type="submit" class="btn btn-accent">
                            <i class="bi bi-save me-1"></i>
                            <span class="d-none d-sm-inline">Update Hero Section</span>
                            <span class="d-sm-none">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Stats Tab --}}
        <div x-show="activeTab === 'stats'" x-cloak>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title-custom mb-0">Edit Statistics Section</h5>
                </div>
                <form action="{{ route('admin.homepage.stats') }}" method="POST">
                    <div class="card-body p-3 p-md-4">
                        
                    <div class="alert alert-warning d-flex align-items-center mt-4" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                            <div>
                                 Tip: Otomatis akan ditambahkan tanda '+' di belakang nilai!
                             </div>
                    </div>

                        @csrf
                        @method('PUT')
                        
                        @for($i = 0; $i < 4; $i++)
                        <div class="row g-3 mb-4 pb-3 {{ $i < 3 ? 'border-bottom' : '' }}">
                            <div class="col-12">
                                <h6 class="fw-semibold text-muted">Stat {{ $i + 1 }}</h6>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Value</label>
                                <input type="number" 
                                       name="stats[{{ $i }}][value]" 
                                       class="form-control" 
                                       value="{{ preg_replace('/[^0-9]/', '', $stats[$i]->value ?? '') }}" 
                                       placeholder="500" 
                                       min="0" 
                                       required>
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label">Label</label>
                                <input type="text" 
                                       name="stats[{{ $i }}][label]" 
                                       class="form-control" 
                                       value="{{ $stats[$i]->label ?? '' }}" 
                                       placeholder="Member Aktif" 
                                       required>
                            </div>
                        </div>
                        @endfor
                    </div>
                    <div class="card-footer bg-white border-0 py-3 text-end">
                        <button type="submit" class="btn btn-accent">
                            <i class="bi bi-save me-1"></i>
                            <span class="d-none d-sm-inline">Update Statistics</span>
                            <span class="d-sm-none">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Benefits Tab --}}
        <div x-show="activeTab === 'benefits'" x-cloak>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title-custom mb-0">Edit Benefits Section</h5>
                </div>
                <form action="{{ route('admin.homepage.benefits') }}" method="POST">
                    <div class="card-body p-3 p-md-4">
                        @csrf
                        @method('PUT')
                        
                        @for($i = 0; $i < 4; $i++)
                        <div class="border rounded p-3 mb-3">
                            <h6 class="fw-semibold mb-3">Benefit {{ $i + 1 }}</h6>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Icon</label>
                                    <select name="benefits[{{ $i }}][icon]" class="form-select" required>
                                        <option value="people" {{ ($benefits[$i]->icon ?? '') === 'people' ? 'selected' : '' }}>People</option>
                                        <option value="calendar" {{ ($benefits[$i]->icon ?? '') === 'calendar' ? 'selected' : '' }}>Calendar</option>
                                        <option value="trophy" {{ ($benefits[$i]->icon ?? '') === 'trophy' ? 'selected' : '' }}>Trophy</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-8">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="benefits[{{ $i }}][title]" class="form-control" value="{{ $benefits[$i]->title ?? '' }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="benefits[{{ $i }}][description]" class="form-control" rows="2" required>{{ $benefits[$i]->description ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    <div class="card-footer bg-white border-0 py-3 text-end">
                        <button type="submit" class="btn btn-accent">
                            <i class="bi bi-save me-1"></i>
                            <span class="d-none d-sm-inline">Update Benefits</span>
                            <span class="d-sm-none">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Testimonials Tab --}}
        <div x-show="activeTab === 'testimonials'" x-cloak>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title-custom mb-0">Edit Testimonials Section</h5>
                </div>
                <form action="{{ route('admin.homepage.testimonials') }}" method="POST">
                    <div class="card-body p-3 p-md-4">
                        @csrf
                        @method('PUT')
                        
                        @for($i = 0; $i < 3; $i++)
                        <div class="border rounded p-3 mb-3">
                            <h6 class="fw-semibold mb-3">Testimonial {{ $i + 1 }}</h6>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="testimonials[{{ $i }}][name]" class="form-control" value="{{ $testimonials[$i]->name ?? '' }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Role/Package</label>
                                    <input type="text" name="testimonials[{{ $i }}][role]" class="form-control" value="{{ $testimonials[$i]->role ?? '' }}" placeholder="Member Premium" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Testimonial Text</label>
                                    <textarea name="testimonials[{{ $i }}][text]" class="form-control" rows="3" required>{{ $testimonials[$i]->text ?? '' }}</textarea>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Rating (1-5)</label>
                                    <select name="testimonials[{{ $i }}][rating]" class="form-select" required>
                                        @for($r = 1; $r <= 5; $r++)
                                        <option value="{{ $r }}" {{ ($testimonials[$i]->rating ?? 5) == $r ? 'selected' : '' }}>
                                            {{ $r }} Star{{ $r > 1 ? 's' : '' }}
                                        </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    <div class="card-footer bg-white border-0 py-3 text-end">
                        <button type="submit" class="btn btn-accent">
                            <i class="bi bi-save me-1"></i>
                            <span class="d-none d-sm-inline">Update Testimonials</span>
                            <span class="d-sm-none">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Preview Button --}}
    <div class="mt-4 text-center">
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary">
            <i class="bi bi-eye me-2"></i>
            Preview Homepage
        </a>
    </div>

@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    
    .nav-tabs {
        border-bottom: 2px solid var(--admin-border);
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
        color: var(--admin-primary);
        border-bottom-color: var(--admin-primary);
        font-weight: 600;
    }
    
    @media (max-width: 576px) {
        .nav-tabs .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush