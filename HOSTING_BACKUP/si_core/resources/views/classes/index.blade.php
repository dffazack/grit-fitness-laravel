@extends('layouts.app')

@section('title', 'Kelas & Trainer - GRIT Fitness')

@push('styles')
<style>
    .tab-nav-container {
        border-bottom: 2px solid #dee2e6;
    }
    .tab-nav-button {
        background: none;
        border: none;
        padding: 0.8rem 1.5rem;
        font-weight: 600;
        color: #6c757d;
        position: relative;
        transition: color 0.3s ease;
    }
    .tab-nav-button::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: var(--grit-accent);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    .tab-nav-button.active {
        color: var(--grit-primary);
    }
    .tab-nav-button.active::after {
        transform: scaleX(1);
    }
    .tab-content-panel {
        display: none;
        animation: fadeIn 0.5s ease-out;
    }
    .tab-content-panel.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    /* Responsive tweaks spesifik mobile untuk spacing/ukuran */
    @media (max-width: 576px) {
        .card .card-body { padding: 0.9rem; }
        .card .card-title { font-size: 1rem; }
        .card .card-subtitle { font-size: 0.85rem; }
        .card .card-text { font-size: 0.85rem; }
        .card img.rounded-circle { width: 100px !important; height: 100px !important; }
        .badge { font-size: .75rem; padding: .35em .5em; }
        .btn { font-size: 0.95rem; padding: .45rem .6rem; }
    }
    @media (min-width: 992px) {
        .card .card-title { font-size: 1.05rem; }
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="text-primary fw-bold">Jadwal Kelas & Trainer</h1>
        <p class="text-muted">Temukan kelas yang cocok dan kenali trainer profesional kami.</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="d-flex justify-content-center mb-5 tab-nav-container" data-aos="fade-up" data-aos-delay="100">
        <button class="tab-nav-button active" data-tab="schedule">Jadwal Kelas</button>
        <button class="tab-nav-button" data-tab="trainers">Daftar Trainer</button>
    </div>

    {{-- Tab Content --}}
    <div>
        {{-- Schedule Panel --}}
        <div id="schedule" class="tab-content-panel active">
            {{-- Filter Section --}}
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body">
                    <form action="{{ route('classes') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="filterDay" class="form-label small">Filter Hari:</label>
                                <select id="filterDay" name="day" class="form-select form-select-sm">
                                    <option value="all">Semua Hari</option>
                                    <option value="Senin" {{ ($filterDay ?? '') === 'Senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="Selasa" {{ ($filterDay ?? '') === 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="Rabu" {{ ($filterDay ?? '') === 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="Kamis" {{ ($filterDay ?? '') === 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="Jumat" {{ ($filterDay ?? '') === 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="Sabtu" {{ ($filterDay ?? '') === 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                    <option value="Minggu" {{ ($filterDay ?? '') === 'Minggu' ? 'selected' : '' }}>Minggu</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filterType" class="form-label small">Filter Tipe Kelas:</label>
                                <select id="filterType" name="class_type" class="form-select form-select-sm">
                                    <option value="all">Semua Tipe</option>
                                    <option value="Cardio" {{ ($filterType ?? '') === 'Cardio' ? 'selected' : '' }}>Cardio</option>
                                    <option value="Strength" {{ ($filterType ?? '') === 'Strength' ? 'selected' : '' }}>Strength</option>
                                    <option value="Yoga" {{ ($filterType ?? '') === 'Yoga' ? 'selected' : '' }}>Yoga</option>
                                    <option value="HIIT" {{ ($filterType ?? '') === 'HIIT' ? 'selected' : '' }}>HIIT</option>
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
                    <div class="col-12" data-aos="fade-up" data-aos-delay="300">
                        <h3 class="mb-3 text-primary">{{ $day }}</h3>
                        <div class="row g-3">
                            @foreach($daySchedules as $schedule)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card h-100 border-0 shadow-sm card-hover">
                                        <div class="card-body d-flex flex-column p-3 p-md-4">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title text-primary mb-0">{{ $schedule->custom_class_name ?? $schedule->classList->name ?? 'N/A' }}</h5>
                                                @php
                                                    $remaining = max(0, ($schedule->max_quota ?? 0) - ($schedule->quota ?? 0));
                                                    $ratio = ($schedule->max_quota > 0) ? ($schedule->quota / $schedule->max_quota) : 0;
                                                    $badgeClass = $remaining <= 0 ? 'bg-danger' : ($ratio < 0.5 ? 'bg-success' : 'bg-warning');
                                                @endphp
                                                <span class="badge {{ $badgeClass }} text-white small">Sisa {{ $remaining }}</span>
                                            </div>
                                            <p class="card-subtitle mb-2 text-muted small">
                                                <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                <br class="d-inline d-md-none">
                                                <i class="bi bi-person me-1"></i> {{ $schedule->trainer->name ?? 'N/A' }}
                                            </p>
                                            <p class="card-text small text-muted flex-grow-1 d-none d-md-block">{{ Str::limit($schedule->description ?? '-', 140) }}</p>
                                            <div class="d-block d-md-none">
                                                <a class="btn btn-link p-0 small" data-bs-toggle="collapse" href="#sched-desc-{{ $schedule->id }}" role="button" aria-expanded="false" aria-controls="sched-desc-{{ $schedule->id }}">Lihat Detail</a>
                                                <div class="collapse mt-2" id="sched-desc-{{ $schedule->id }}">
                                                    <p class="small text-muted mb-2">{{ $schedule->description ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="mt-auto pt-3">
                                                @auth
                                                    @if(Auth::user()->hasActiveMembership())
                                                        @php
                                                            $isBooked = in_array($schedule->id, $userBookings);
                                                            $isFull = ($schedule->quota ?? 0) >= ($schedule->max_quota ?? 0);
                                                        @endphp
                                                        <form action="{{ route('member.bookings.store', $schedule->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-accent btn-sm w-100" {{ $isFull || $isBooked ? 'disabled' : '' }}>
                                                                @if($isFull) Kelas Penuh
                                                                @elseif($isBooked) Sudah Dibooking
                                                                @else Booking Kelas
                                                                @endif
                                                            </button>
                                                        </form>
                                                    @else
                                                        <a href="{{ route('membership') }}" class="btn btn-secondary btn-sm w-100">Upgrade Membership</a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100">Login untuk Booking</a>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="col-12" data-aos="fade-up" data-aos-delay="300">
                        <div class="alert alert-info text-center">Belum ada jadwal kelas yang tersedia.</div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Trainers Panel --}}
        <div id="trainers" class="tab-content-panel">
            <div class="row g-4">
                @forelse($trainers as $trainer)
                    <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 100 }}">
                        <div class="card border-0 shadow-sm text-center h-100 card-hover">
                            <div class="card-body d-flex flex-column align-items-center p-3 p-md-4">
                                <img src="{{ $trainer->image ? asset('storage/' . $trainer->image) : 'https://via.placeholder.com/150' }}"
                                     class="rounded-circle mb-3 shadow" alt="{{ $trainer->name }}"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                                <h5 class="fw-bold mb-1 text-primary">{{ $trainer->name }}</h5>
                                <p class="small text-accent mb-2">{{ $trainer->specialization }}</p>
                                <p class="small text-muted mb-2">{{ $trainer->experience ?? '-' }} Pengalaman | {{ $trainer->clients ?? 0 }} Klien</p>
                                <p class="small text-muted text-center mb-3 d-none d-md-block">{{ Str::limit($trainer->bio ?? '-', 120) }}</p>
                                <div class="d-block d-md-none w-100">
                                    <p class="small text-muted text-center mb-2">{{ Str::limit($trainer->bio ?? '-', 80) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">Data trainer belum tersedia.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-nav-button');
    const tabPanels = document.querySelectorAll('.tab-content-panel');

    // Function to switch tabs
    const switchTab = (tabId) => {
        // Remove active state from all buttons and panels
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabPanels.forEach(panel => panel.classList.remove('active'));

        // Add active state to the clicked button and corresponding panel
        const activeButton = document.querySelector(`.tab-nav-button[data-tab="${tabId}"]`);
        const activePanel = document.getElementById(tabId);

        if (activeButton) activeButton.classList.add('active');
        if (activePanel) activePanel.classList.add('active');
        
        // Store the active tab in URL hash
        window.location.hash = tabId;
    };

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');
            switchTab(tabId);
        });
    });

    // Check for a tab hash in the URL on page load
    const currentHash = window.location.hash.substring(1);
    if (currentHash === 'trainers') {
        switchTab('trainers');
    } else {
        // Default to schedule tab
        switchTab('schedule');
    }
});
</script>
@endpush