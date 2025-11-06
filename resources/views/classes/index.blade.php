@extends('layouts.app')

@section('title', 'Kelas & Trainer - GRIT Fitness')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="text-center mb-5">
        <h1 class="text-primary fw-bold">Jadwal Kelas & Trainer</h1>
        <p class="text-muted">Temukan kelas yang cocok dan kenali trainer profesional kami.</p>
    </div>

    {{-- Tabs (Optional - bisa ditambahkan jika ingin memisahkan view) --}}
    {{-- <ul class="nav nav-tabs mb-4 justify-content-center">
        <li class="nav-item">
            <a class="nav-link active" href="#">Jadwal Kelas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Tim Trainer</a>
        </li>
    </ul> --}}

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
                            {{-- Tambahkan tipe lain jika ada --}}
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
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title text-primary">{{ $schedule->name }}</h5>
                                        <span class="badge {{ $schedule->quota < $schedule->max_quota ? ($schedule->quota / $schedule->max_quota < 0.5 ? 'bg-success' : 'bg-warning') : 'bg-danger' }}">
                                            Sisa {{ $schedule->max_quota - $schedule->quota }}
                                        </span>
                                    </div>
                                    <p class="card-subtitle mb-2 text-muted small">
                                        <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        <br>
                                        <i class="bi bi-person me-1"></i> {{ $schedule->trainer->name ?? 'N/A' }}
                                    </p>
                                    <p class="card-text small text-muted flex-grow-1">{{ Str::limit($schedule->description, 80) }}</p>
                                    <div class="mt-auto">
                                        {{-- Tombol Booking (Tampilkan jika user adalah member) --}}
                                        @auth
                                            @if(Auth::user()->isMember() || Auth::user()->hasActiveMembership())
                                                <button class="btn btn-accent btn-sm w-100 mt-2" {{ $schedule->quota >= $schedule->max_quota ? 'disabled' : '' }}>
                                                    {{ $schedule->quota >= $schedule->max_quota ? 'Kelas Penuh' : 'Booking Kelas' }}
                                                </button>
                                            @else
                                                {{-- Tampilkan info jika guest/pending --}}
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
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="card-body">
                            <img src="{{ $trainer->image ? asset('storage/' . $trainer->image) : 'https://via.placeholder.com/150' }}"
                                 class="rounded-circle mb-3 shadow" alt="{{ $trainer->name }}" style="width: 120px; height: 120px; object-fit: cover;">
                            <h5 class="fw-bold mb-1 text-primary">{{ $trainer->name }}</h5>
                            <p class="small text-accent mb-2">{{ $trainer->specialization }}</p>
                            <p class="small text-muted mb-3">{{ $trainer->experience }} Pengalaman | {{ $trainer->clients }} Klien</p>
                            <p class="small text-muted">{{ Str::limit($trainer->bio, 100) }}</p>
                            {{-- <button class="btn btn-sm btn-outline-primary rounded-pill mt-3">Lihat Profil Lengkap</button> --}}
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
@endsection