{{-- File: resources/views/home/index.blade.php --}}
{{-- Diadaptasi dari HomePage.tsx --}}

@extends('layouts.app')

@section('title', $hero->title ?? 'GRIT Fitness - Transform Your Body')

@section('content')

    {{-- Notification Banner (Jika ada notifikasi aktif) --}}
    @include('components.notification-banner') {{-- Pastikan komponen ini ada --}}

    {{-- Hero Section --}}
    <section class="hero-section position-relative d-flex align-items-center justify-content-center text-white" style="min-height: 600px; background: linear-gradient(rgba(43, 50, 130, 0.7), rgba(43, 50, 130, 0.7)), url('{{ asset('images/hero-bg.jpg') }}') center/cover no-repeat;">
        <div class="container text-center animate-fade-in">
            <h1 class="display-4 fw-bold text-white mb-4 px-5" style="line-height: 1.1;">
             {{-- Tampilkan title dengan line break jika ada \n --}}
        {!! nl2br(e($hero->title ?? 'Transform Your Body, Transform Your Life')) !!}
            </h1>
            <p class="lead fs-5 mb-5 text-white mx-auto" style="opacity: 0.9; max-width: 700px;">
                {{ $hero->subtitle ?? 'Bergabunglah dengan GRIT Fitness dan mulai perjalanan transformasi Anda bersama komunitas yang supportif' }}
            </p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="{{ route('membership') }}" class="btn btn-accent btn-lg px-5 py-3">
                    Lihat Paket Membership
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
                <a href="{{ route('classes') }}" class="btn btn-light btn-lg px-5 py-3" style="color: var(--grit-primary);">
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
                            <p class="text-muted small text-uppercase">{{ $stat['label'] ?? 'N/A' }}</p>
                        </div>
                    @endforeach
                @else
                     <p class="text-muted text-center">Statistik belum tersedia.</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Benefits Section --}}
    <section class="container py-5 mt-5">
        <div class="text-center mb-5">
            <h2 class="mb-3 fw-bold" style="color: var(--grit-primary);">Mengapa Memilih GRIT Fitness?</h2>
            <p class="text-muted mx-auto" style="max-width: 700px;">
                Kami berkomitmen memberikan pengalaman fitness terbaik dengan fasilitas lengkap dan dukungan profesional.
            </p>
        </div>

        <div class="row g-4">
            @if(!empty($benefits) && is_array($benefits))
                @foreach($benefits as $benefit)
                    <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch">
                        <div class="card border-0 shadow-sm text-center h-100 card-hover p-4">
                            <div class="card-body d-flex flex-column">
                                <div class="rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center"
                                     style="width: 70px; height: 70px; background: rgba(229, 27, 131, 0.1);">
                                     {{-- Icon mapping --}}
                                     @php
                                         $iconClass = match($benefit['iconName'] ?? 'dumbbell') {
                                             'Users' => 'bi-people-fill',
                                             'Calendar' => 'bi-calendar-check-fill',
                                             'Trophy' => 'bi-trophy-fill',
                                             default => 'bi-dumbbell',
                                         };
                                     @endphp
                                    <i class="bi {{ $iconClass }} fs-2" style="color: var(--grit-accent);"></i>
                                </div>
                                <h5 class="card-title fw-semibold mb-3" style="color: var(--grit-primary);">{{ $benefit['title'] ?? 'Benefit Title' }}</h5>
                                <p class="card-text small text-muted flex-grow-1">{{ $benefit['description'] ?? 'Benefit description.' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted text-center">Informasi benefit belum tersedia.</p>
            @endif
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section class="bg-light-custom py-5 mt-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="mb-3 fw-bold" style="color: var(--grit-primary);">Apa Kata Mereka?</h2>
                <p class="text-muted mx-auto" style="max-width: 700px;">
                    Dengar langsung dari member kami yang telah merasakan transformasi.
                </p>
            </div>

            <div class="row g-4">
                 @if(!empty($testimonials) && is_array($testimonials))
                    @foreach($testimonials as $testimonial)
                        <div class="col-12 col-md-4 d-flex align-items-stretch">
                            <div class="card border-0 shadow-sm h-100 card-hover">
                                <div class="card-body">
                                    <div class="mb-3">
                                        @for($i = 0; $i < ($testimonial['rating'] ?? 5); $i++)
                                            <i class="bi bi-star-fill" style="color: #FFC107;"></i>
                                        @endfor
                                    </div>
                                    <p class="card-text text-muted fst-italic mb-4">
                                        "{{ $testimonial['text'] ?? 'Testimonial text.' }}"
                                    </p>
                                    <div class="d-flex align-items-center gap-3">
                                        {{-- Placeholder Avatar --}}
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 48px; height: 48px; background: rgba(43, 50, 130, 0.1); color: var(--grit-primary); font-weight: 600;">
                                            {{ strtoupper(substr($testimonial['name'] ?? 'N A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="fw-semibold mb-0 small" style="color: var(--grit-primary);">{{ $testimonial['name'] ?? 'Member Name' }}</p>
                                            <p class="text-muted mb-0 small">{{ $testimonial['role'] ?? 'Member Role' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Testimoni belum tersedia.</p>
                @endif
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-5 text-center text-white" style="background: linear-gradient(135deg, var(--grit-primary), #1f2461);">
        <div class="container">
            <h2 class="text-white mb-3 fw-bold">Siap Memulai Transformasi Anda?</h2>
            <p class="lead text-white mb-4 mx-auto" style="opacity: 0.9; max-width: 600px;">
                Dapatkan konsultasi gratis dan program training yang disesuaikan dengan kebutuhan Anda.
            </p>
            <a href="{{ route('register') }}" class="btn btn-accent btn-lg px-5 py-3">
                Mulai Sekarang
                <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </section>

@endsection

{{-- Styling tambahan jika diperlukan --}}
<style>
.card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: var(--grit-shadow-lg);
}
.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
    opacity: 0;
}
@keyframes fadeIn {
    to {
        opacity: 1;
    }
}
</style>