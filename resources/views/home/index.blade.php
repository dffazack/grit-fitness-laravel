@extends('layouts.app')

@section('title', $homepage['title'] ?? 'GRIT Fitness - Transform Your Body')

@section('content')

    {{-- Hero Section --}}
    <section class="hero-section position-relative d-flex align-items-center justify-content-center text-white" style="min-height: 600px; background: linear-gradient(rgba(43, 50, 130, 0.7), rgba(43, 50, 130, 0.7)), url('{{ $homepage['image'] ?? asset('images/hero-bg.jpg') }}') center/cover no-repeat;">
        <div class="container text-center animate-fade-in">
            <h1 class="display-4 fw-bold text-white mb-4 px-3 px-md-5" style="line-height: 1.1;">
                {!! nl2br(e($homepage['title'] ?? 'Transform Your Body, Transform Your Life')) !!}
            </h1>
            <p class="lead fs-5 mb-5 text-white mx-auto px-3" style="opacity: 0.9; max-width: 700px;">
                {{ $homepage['subtitle'] ?? 'Bergabunglah dengan GRIT Fitness dan mulai perjalanan transformasi Anda bersama komunitas yang supportif' }}
            </p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center px-3">
                <a href="{{ route('membership') }}" class="btn btn-accent btn-lg px-4 px-md-5 py-3">
                    Lihat Paket Membership
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
                <a href="{{ route('classes') }}" class="btn btn-light btn-lg px-4 px-md-5 py-3" style="color: var(--grit-primary);">
                    Lihat Jadwal Kelas
                </a>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="bg-white py-5 shadow-sm">
        <div class="container">
            <div class="row g-4 text-center">
                @if(!empty($stats) && is_array($stats))
                    @foreach($stats as $stat)
                        <div class="col-6 col-md-3">
                            <h2 class="display-4 fw-bold mb-1" style="color: var(--grit-accent);">{{ $stat['value'] ?? 'N/A' }}</h2>
                            <p class="text-muted small text-uppercase mb-0">{{ $stat['label'] ?? 'N/A' }}</p>
                        </div>
                    @endforeach
                @else
                     <p class="text-muted text-center mb-0">Statistik belum tersedia.</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Benefits Section - FIXED --}}
    <section class="py-5 mt-4">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3 fw-bold" style="color: var(--grit-primary);">Mengapa Memilih GRIT Fitness?</h2>
                <p class="text-muted mx-auto px-3" style="max-width: 700px;">
                    Kami berkomitmen memberikan pengalaman fitness terbaik dengan fasilitas lengkap dan dukungan profesional.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                @if(!empty($benefits) && is_array($benefits))
                    @foreach($benefits as $benefit)
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="card border-0 shadow-sm text-center h-100 card-hover">
                                <div class="card-body d-flex flex-column p-4">
                                    {{-- Icon Container --}}
                                    <div class="d-flex justify-content-center mb-4">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 70px; height: 70px; background: rgba(229, 27, 131, 0.1); flex-shrink: 0;">
                                             @php
                                                $rawIcon = null;
                                                if (is_array($benefit)) {
                                                    $rawIcon = $benefit['icon'] ?? null;
                                                } elseif (is_object($benefit)) {
                                                    $rawIcon = $benefit->icon ?? null;
                                                }
                                                
                                                $icon = strtolower(trim((string) ($rawIcon ?? 'dumbbell')));
                                                
                                                $iconMap = [
                                                    'dumbbell'       => 'bi-dumbbell',
                                                    'dumbell'        => 'bi-dumbbell',
                                                    'people'         => 'bi-people-fill',
                                                    'users'          => 'bi-people-fill',
                                                    'calendar'       => 'bi-calendar-check-fill',
                                                    'calendar-check' => 'bi-calendar-check-fill',
                                                    'trophy'         => 'bi-trophy-fill',
                                                ];
                                                
                                                $iconClass = $iconMap[$icon] ?? 'bi-dumbbell';
                                            @endphp
                                            <i class="bi {{ $iconClass }} fs-2" style="color: var(--grit-accent);"></i>
                                        </div>
                                    </div>
                                    
                                    {{-- Title --}}
                                    <h5 class="card-title fw-semibold mb-3" style="color: var(--grit-primary); min-height: 48px;">
                                        {{ $benefit['title'] ?? 'Benefit Title' }}
                                    </h5>
                                    
                                    {{-- Description --}}
                                    <p class="card-text small text-muted flex-grow-1 mb-0" style="line-height: 1.6;">
                                        {{ $benefit['description'] ?? 'Benefit description.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <p class="text-muted text-center mb-0">Informasi benefit belum tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Popular Packages Section --}}
    @if(isset($popularPackages) && $popularPackages->isNotEmpty())
    <section class="bg-light-custom py-5 mt-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3 fw-bold" style="color: var(--grit-primary);">Paket Terpopuler</h2>
                <p class="text-muted mx-auto" style="max-width: 700px;">
                    Pilihan terbaik yang paling banyak diminati oleh member kami.
                </p>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach($popularPackages as $package)
                    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
                        <div class="card membership-card h-100 {{ $package->is_popular ? 'featured' : '' }}">
                            <div class="card-body d-flex flex-column text-center">
                                <h3 class="mb-3" style="color: {{ $package->type == 'student' ? 'var(--grit-accent)' : 'var(--grit-primary)' }};">{{ $package->name }}</h3>
                                <h1 class="display-5 fw-bold mb-2 membership-price" style="color: {{ $package->type == 'student' ? 'var(--grit-accent)' : 'var(--grit-primary)' }};">
                                    {{ $package->getFormattedPrice() }}
                                </h1>
                                <p class="text-muted mb-4">{{ $package->duration_months }} Bulan</p>

                                <ul class="list-unstyled text-start mb-4 flex-grow-1">
                                    @if(is_array($package->features))
                                        @foreach($package->features as $feature)
                                            <li class="mb-2 membership-feature">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>{{ $feature }}
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>

                                <div class="mt-auto">
                                    <a href="{{ route('membership') }}" class="btn {{ $package->type == 'student' ? 'btn-accent' : 'btn-primary' }} w-100 btn-lg">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Testimonials Section --}}
    <section class="bg-light-custom py-5 mt-5">
    {{-- About Us Section - NEW --}}
    <section class="py-5 mt-5 bg-white">
        <div class="container">
            <div class="row align-items-center g-5">
                {{-- Text Content (Kiri di Desktop, Atas di Mobile) --}}
                <div class="col-12 col-lg-6 order-2 order-lg-1">
                    {{-- Section Label --}}
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width: 50px; height: 3px; background-color: var(--grit-accent);"></div>
                        <h6 class="text-uppercase fw-bold mb-0" style="color: var(--grit-accent); letter-spacing: 2px;">
                            TENTANG KAMI
                        </h6>
                    </div>
                    
                    {{-- Main Heading --}}
                    <h2 class="display-5 fw-bold mb-4" style="color: var(--grit-primary);">
                        Pusat Kebugaran Modern di Jantung Kota Malang
                    </h2>
                    
                    {{-- Description --}}
                    <p class="lead mb-4" style="line-height: 1.8; opacity: 0.9;">
                        Terletak strategis di Plaza Begawan, Tlogomas, Grit Fitness Malang hadir sebagai pusat kebugaran modern yang menawarkan lebih dari sekadar gym.
                    </p>
                    
                    <p class="mb-4" style="line-height: 1.8; opacity: 0.85;">
                        Kami menyediakan berbagai fasilitas seperti kelas olahraga, layanan pemulihan, pelatihan pribadi, hingga healthy bar untuk mendukung gaya hidup sehat Anda.
                    </p>
                    
                    <p class="mb-4" style="line-height: 1.8; opacity: 0.85;">
                        Buka setiap hari, kami siap membantu Anda mencapai tujuan kebugaran dengan dukungan instruktur berpengalaman dan lingkungan yang nyaman.
                    </p>
                    
                    <p class="fw-semibold mb-0" style="font-size: 1.1rem; opacity: 0.95;">
                        <i class="bi bi-quote"></i>
                        Grit Fitness bukan sekadar tempat latihan—ini adalah komunitas untuk berkembang dan menjadi versi terbaik dari diri Anda.
                    </p>
                </div>
                
                {{-- Image (Kanan di Desktop, Atas di Mobile) --}}
                <div class="col-12 col-lg-6 order-1 order-lg-2">
                    <div class="position-relative">
                        {{-- Decorative Element --}}
                        <div class="position-absolute top-0 end-0 bg-accent" 
                             style="width: 100px; height: 100px; border-radius: 20px; transform: translate(20px, -20px); opacity: 0.5; z-index: 1;">
                        </div>
                        
                        {{-- Main Image --}}
                        <img src="{{ asset('images/gym-about.jpg') }}" 
                             alt="GRIT Fitness Gym" 
                             class="img-fluid rounded-3 shadow-lg position-relative" 
                             style="z-index: 2; border: 5px solid rgba(229, 27, 131, 0.2);">
                        
                        {{-- Decorative Element 2 --}}
                        <div class="position-absolute bottom-0 start-0 bg-primary" 
                             style="width: 80px; height: 80px; border-radius: 20px; transform: translate(-20px, 20px); opacity: 0.5; z-index: 1;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials Section - FIXED --}}
    <section class="bg-light-custom py-5 mt-4">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3 fw-bold" style="color: var(--grit-primary);">Apa Kata Mereka?</h2>
                <p class="text-muted mx-auto px-3" style="max-width: 700px;">
                    Dengar langsung dari member kami yang telah merasakan transformasi.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                 @if(!empty($testimonials) && is_array($testimonials))
                    @foreach($testimonials as $testimonial)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm h-100 card-hover">
                                <div class="card-body d-flex flex-column p-4">
                                    {{-- Rating Stars --}}
                                    <div class="mb-3">
                                        @for($i = 0; $i < ($testimonial['rating'] ?? 5); $i++)
                                            <i class="bi bi-star-fill" style="color: #FFC107;"></i>
                                        @endfor
                                    </div>
                                    
                                    {{-- Testimonial Text --}}
                                    <p class="card-text text-muted fst-italic mb-4 flex-grow-1" style="line-height: 1.7;">
                                        "{{ $testimonial['text'] ?? 'Testimonial text.' }}"
                                    </p>
                                    
                                    {{-- User Info --}}
                                    <div class="d-flex align-items-center gap-3 mt-auto">
                                        {{-- Avatar --}}
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width: 48px; height: 48px; background: rgba(43, 50, 130, 0.1); color: var(--grit-primary); font-weight: 600;">
                                            {{ strtoupper(substr($testimonial['name'] ?? 'N', 0, 1)) }}
                                        </div>
                                        
                                        {{-- Name & Role --}}
                                        <div class="text-start">
                                            <p class="fw-semibold mb-0 small" style="color: var(--grit-primary);">
                                                {{ $testimonial['name'] ?? 'Member Name' }}
                                            </p>
                                            <p class="text-muted mb-0 small">
                                                {{ $testimonial['role'] ?? 'Member Role' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <p class="text-muted text-center mb-0">Testimoni belum tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-5 text-center text-white" style="background: linear-gradient(135deg, var(--grit-primary), #1f2461);">
        <div class="container px-3">
            <h2 class="text-white mb-3 fw-bold">Siap Memulai Transformasi Anda?</h2>
            <p class="lead text-white mb-4 mx-auto" style="opacity: 0.9; max-width: 600px;">
                Dapatkan konsultasi gratis dan program training yang disesuaikan dengan kebutuhan Anda.
            </p>
            <a href="{{ route('register') }}" class="btn btn-accent btn-lg px-4 px-md-5 py-3">
                Mulai Sekarang
                <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </section>

@endsection

@push('styles')
<style>
/* Card Hover Effect */
.card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
}

/* Fade In Animation */
.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
    opacity: 0;
}
@keyframes fadeIn {
    to {
        opacity: 1;
    }
}

/* About Section Styling */
.bg-dark {
    background-color: #1a1a1a !important;
}

/* Responsive Adjustments */
@media (max-width: 767px) {
    .display-4 {
        font-size: 2rem;
    }
    .lead {
        font-size: 1rem;
    }
}

/* About Section Responsive */
@media (max-width: 991px) {
    .display-5 {
        font-size: 1.75rem;
    }
    
    /* Hide decorative elements on mobile */
    .position-absolute.bg-accent,
    .position-absolute.bg-primary {
        display: none;
    }
}

.h-100 {
    height: 100% !important;
}

/* Icon consistency */
.bi {
    line-height: 1;
}
</style>
@endpush

