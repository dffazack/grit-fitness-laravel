@extends('layouts.app')

@section('title', 'Kelas & Trainer - GRIT Fitness')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="text-center mb-5">
        <h1 class="text-primary fw-bold">Jadwal Kelas & Trainer</h1>
        <p class="text-muted">Temukan kelas yang cocok dan kenali trainer profesional kami.</p>
    </div>

    {{-- Filter Section --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form>
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="filterDay" class="form-label small">Filter Hari:</label>
                        <select id="filterDay" class="form-select form-select-sm">
                            <option value="all">Semua Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filterType" class="form-label small">Filter Tipe Kelas:</label>
                        <select id="filterType" class="form-select form-select-sm">
                            <option value="all">Semua Tipe</option>
                            <option value="Cardio">Cardio</option>
                            <option value="Strength">Strength</option>
                            <option value="Yoga">Yoga</option>
                            <option value="HIIT">HIIT</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Terapkan Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Schedule Grid --}}
    <div class="row g-4">
        @forelse($schedules as $day => $daySchedules)
            <div class="col-12">
                <h3 class="mb-3 text-primary">{{ $day }}</h3>
                <div class="row g-3">
                    @foreach($daySchedules as $schedule)
                        {{-- Responsive: xs=12 (1 per row), md=6 (2 per row), lg=4 (3 per row) --}}
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body d-flex flex-column p-3 p-md-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title text-primary mb-0">{{ $schedule->custom_class_name ?? $schedule->classList->name ?? 'N/A' }}</h5>

                                        @php
                                            $remaining = max(0, ($schedule->max_quota ?? 0) - ($schedule->quota ?? 0));
                                            $ratio = ($schedule->max_quota > 0) ? ($schedule->quota / $schedule->max_quota) : 0;
                                            $badgeClass = $remaining <= 0 ? 'bg-danger' : ($ratio < 0.5 ? 'bg-success' : 'bg-warning');
                                        @endphp
                                        <span class="badge {{ $badgeClass }} text-white small">
                                            Sisa {{ $remaining }}
                                        </span>
                                    </div>

                                    <p class="card-subtitle mb-2 text-muted small">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        <br class="d-inline d-md-none">
                                        <i class="bi bi-person me-1"></i> {{ $schedule->trainer->name ?? 'N/A' }}
                                    </p>

                                    {{-- Deskripsi: tampil penuh di desktop, collapse di mobile --}}
                                    <p class="card-text small text-muted flex-grow-1 d-none d-md-block">
                                        {{ Str::limit($schedule->description ?? '-', 140) }}
                                    </p>

                                    {{-- Mobile: collapse detail --}}
                                    <div class="d-block d-md-none">
                                        <a class="btn btn-link p-0 small" data-bs-toggle="collapse" href="#sched-desc-{{ $schedule->id }}" role="button" aria-expanded="false" aria-controls="sched-desc-{{ $schedule->id }}">
                                            Lihat Detail
                                        </a>
                                        <div class="collapse mt-2" id="sched-desc-{{ $schedule->id }}">
                                            <p class="small text-muted mb-2">{{ $schedule->description ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-auto">
                                        {{-- Tombol Booking --}}
                                        @auth
                                            @if(method_exists(Auth::user(), 'isMember') ? Auth::user()->isMember() || Auth::user()->hasActiveMembership() : (Auth::user()->role === 'member'))
                                                <button class="btn btn-accent btn-sm w-100 mt-2" {{ ($schedule->quota ?? 0) >= ($schedule->max_quota ?? 0) ? 'disabled' : '' }}>
                                                    {{ ($schedule->quota ?? 0) >= ($schedule->max_quota ?? 0) ? 'Kelas Penuh' : 'Booking Kelas' }}
                                                </button>
                                            @else
                                                <a href="{{ route('membership') }}" class="btn btn-secondary btn-sm w-100 mt-2">Upgrade Membership</a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100 mt-2">Login untuk Booking</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Belum ada jadwal kelas yang tersedia.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Trainers Section --}}
    <div class="mt-5 pt-5 border-top">
        <div class="text-center mb-5">
            <h2 class="text-primary fw-bold">Tim Trainer Profesional Kami</h2>
            <p class="text-muted">Didukung oleh pelatih bersertifikasi dengan passion membara.</p>
        </div>

        <div class="row g-4">
            @forelse($trainers as $trainer)
                {{-- xs=12 (1 per row mobile), md=6 (2 per row), lg=3 (4 per row) --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="card-body d-flex flex-column align-items-center p-3 p-md-4">
                            <img src="{{ $trainer->image ? asset('storage/' . $trainer->image) : 'https://via.placeholder.com/150' }}"
                                 class="rounded-circle mb-3 shadow" alt="{{ $trainer->name }}"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                            <h5 class="fw-bold mb-1 text-primary">{{ $trainer->name }}</h5>
                            <p class="small text-accent mb-2">{{ $trainer->specialization }}</p>
                            <p class="small text-muted mb-2">{{ $trainer->experience ?? '-' }} Pengalaman | {{ $trainer->clients ?? 0 }} Klien</p>
                            <p class="small text-muted text-center mb-3 d-none d-md-block">{{ Str::limit($trainer->bio ?? '-', 120) }}</p>

                            {{-- Mobile: ringkasan bio dan tombol lihat profil --}}
                            <div class="d-block d-md-none w-100">
                                <p class="small text-muted text-center mb-2">{{ Str::limit($trainer->bio ?? '-', 80) }}</p>
                            </div>

                            {{-- Optional: tombol aksi --}}
                            {{-- <a href="#" class="btn btn-outline-primary btn-sm rounded-pill mt-auto">Lihat Profil Lengkap</a> --}}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Data trainer belum tersedia.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Responsive tweaks spesifik mobile untuk spacing/ukuran --}}
<style>
    /* Fokus pada mobile (≤576px) */
    @media (max-width: 576px) {
        .card .card-body {
            padding: 0.9rem;
        }
        .card .card-title {
            font-size: 1rem;
        }
        .card .card-subtitle {
            font-size: 0.85rem;
        }
        .card .card-text {
            font-size: 0.85rem;
        }
        /* trainer image sedikit lebih kecil di mobile */
        .card img.rounded-circle {
            width: 100px !important;
            height: 100px !important;
        }
        /* badge kecil lebih proporsional */
        .badge {
            font-size: .75rem;
            padding: .35em .5em;
        }
        /* tombol full width di mobile (sudah w-100), beri ukuran touch-friendly */
        .btn {
            font-size: 0.95rem;
            padding: .45rem .6rem;
        }
    }

    /* Desktop adjustments */
    @media (min-width: 992px) {
        .card .card-title {
            font-size: 1.05rem;
        }
    }
</style>
@endsection
{{-- Modified by: User-Interfaced Team -- }}
