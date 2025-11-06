@extends('layouts.admin')

@section('title', 'Edit Homepage Content')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold" style="color: var(--admin-primary);">Edit Homepage Content</h1>
            <p class="text-muted">Kelola konten homepage secara real-time</p>
        </div>
        <a href="{{ route('admin.homepage.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
    
    <!-- Tabs -->
    {{-- Kita gunakan Alpine.js untuk tab dan mendeteksi hash URL (#hero, #stats, dll) --}}
    <div x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'hero' }">
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <button @click="activeTab = 'hero'; window.location.hash = 'hero';" 
                        :class="{ 'active': activeTab === 'hero' }"
                        class="nav-link">
                    Hero Section
                </button>
            </li>
            <li class="nav-item">
                <button @click="activeTab = 'stats'; window.location.hash = 'stats';" 
                        :class="{ 'active': activeTab === 'stats' }"
                        class="nav-link">
                    Statistics
                </button>
            </li>
            <li class="nav-item">
                <button @click="activeTab = 'benefits'; window.location.hash = 'benefits';" 
                        :class="{ 'active': activeTab === 'benefits' }"
                        class="nav-link">
                    Benefits
                </button>
            </li>
            <li class="nav-item">
                <button @click="activeTab = 'testimonials'; window.location.hash = 'testimonials';" 
                        :class="{ 'active': activeTab === 'testimonials' }"
                        class="nav-link">
                    Testimonials
                </button>
            </li>
        </ul>
        
        <!-- Hero Tab -->
        <div x-show="activeTab === 'hero'" x-cloak>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title-custom">Edit Hero Section</h5>
                </div>
                <form action="{{ route('admin.homepage.hero') }}" method="POST">
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        
                        <x-form-input 
                            label="Hero Title" 
                            name="title"
                            :value="$hero->title ?? ''"
                            placeholder="Transform Your Body, Transform Your Life"
                            required
                        />
                        
                        <div class="mb-3">
                            <label class="form-label">Hero Subtitle</label>
                            <textarea name="subtitle" 
                                      class="form-control" 
                                      rows="3" 
                                      required>{{ $hero->subtitle ?? '' }}</textarea>
                        </div>
                        
                        <x-form-input 
                            label="Background Image URL" 
                            name="image"
                            :value="$hero->image ?? ''"
                            placeholder="https://images.unsplash.com/photo-..."
                            required
                        />
                        
                        <!-- Image Preview -->
                        @if(isset($hero->image) && $hero->image)
                        <div class="mb-3">
                            <label class="form-label">Preview</label>
                            <img src="{{ $hero->image }}" alt="Hero Preview" class="img-fluid rounded" style="max-height: 200px; border: 1px solid var(--admin-border);">
                        </div>
                        @endif
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-accent">
                            Update Hero Section
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Stats Tab -->
        <div x-show="activeTab === 'stats'" x-cloak>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title-custom">Edit Statistics Section</h5>
                </div>
                <form action="{{ route('admin.homepage.stats') }}" method="POST">
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        
                        @for($i = 0; $i < 4; $i++)
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <x-form-input 
                                    label="Stat {{ $i + 1 }} - Value" 
                                    name="stats[{{ $i }}][value]"
                                    :value="$stats[$i]->value ?? ''"
                                    placeholder="500+"
                                    required
                                />
                            </div>
                            <div class="col-md-8">
                                <x-form-input 
                                    label="Stat {{ $i + 1 }} - Label" 
                                    name="stats[{{ $i }}][label]"
                                    :value="$stats[$i]->label ?? ''"
                                    placeholder="Member Aktif"
                                    required
                                />
                            </div>
                        </div>
                        @endfor
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-accent">
                            Update Statistics
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Benefits Tab -->
        <div x-show="activeTab === 'benefits'" x-cloak>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title-custom">Edit Benefits Section</h5>
                </div>
                <form action="{{ route('admin.homepage.benefits') }}" method="POST">
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        
                        @for($i = 0; $i < 4; $i++)
                        <div class="border-bottom pb-4 mb-4">
                            <h6 class="fw-semibold">Benefit {{ $i + 1 }}</h6>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Icon</label>
                                        <select name="benefits[{{ $i }}][icon]" class="form-select" required>
                                            <option value="dumbbell" {{ ($benefits[$i]->icon ?? '') === 'dumbbell' ? 'selected' : '' }}>Dumbbell</option>
                                            <option value="people" {{ ($benefits[$i]->icon ?? '') === 'people' ? 'selected' : '' }}>People</option>
                                            <option value="calendar" {{ ($benefits[$i]->icon ?? '') === 'calendar' ? 'selected' : '' }}>Calendar</option>
                                            <option value="trophy" {{ ($benefits[$i]->icon ?? '') === 'trophy' ? 'selected' : '' }}>Trophy</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <x-form-input 
                                        label="Title" 
                                        name="benefits[{{ $i }}][title]"
                                        :value="$benefits[$i]->title ?? ''"
                                        required
                                    />
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="benefits[{{ $i }}][description]" 
                                          class="form-control" 
                                          rows="2" 
                                          required>{{ $benefits[$i]->description ?? '' }}</textarea>
                            </div>
                        </div>
                        @endfor
                        <script>
document.addEventListener('DOMContentLoaded', function() {
    // Cari semua select icon di form Benefits
    document.querySelectorAll('select[name^="benefits"]').forEach(select => {
        // Buat elemen <i> untuk preview
        const preview = document.createElement('i');
        preview.classList.add('bi', `bi-${select.value}`, 'ms-2', 'fs-4', 'text-accent');
        select.parentNode.appendChild(preview);

        // Update preview kalau pilihan berubah
        select.addEventListener('change', e => {
            preview.className = `bi bi-${e.target.value} ms-2 fs-4 text-accent`;
        });
    });
});
</script>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-accent">
                            Update Benefits
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Testimonials Tab -->
        <div x-show="activeTab === 'testimonials'" x-cloak>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title-custom">Edit Testimonials Section</h5>
                </div>
                <form action="{{ route('admin.homepage.testimonials') }}" method="POST">
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        
                        @for($i = 0; $i < 3; $i++)
                        <div class="border-bottom pb-4 mb-4">
                            <h6 class="fw-semibold">Testimonial {{ $i + 1 }}</h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form-input 
                                        label="Name" 
                                        name="testimonials[{{ $i }}][name]"
                                        :value="$testimonials[$i]->name ?? ''"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-form-input 
                                        label="Role/Package" 
                                        name="testimonials[{{ $i }}][role]"
                                        :value="$testimonials[$i]->role ?? ''"
                                        placeholder="Member Premium"
                                        required
                                    />
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Testimonial Text</label>
                                <textarea name="testimonials[{{ $i }}][text]" 
                                          class="form-control" 
                                          rows="3" 
                                          required>{{ $testimonials[$i]->text ?? '' }}</textarea>
                            </div>
                            
                            <div class="mb-3">
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
                        @endfor
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-accent">
                            Update Testimonials
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Preview Button -->
    <div class="mt-4 text-center">
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary">
            <i class="bi bi-eye me-2"></i>
            Preview Homepage
        </a>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
