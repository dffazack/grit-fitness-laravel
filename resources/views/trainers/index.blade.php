@extends('layouts.app')

@section('title', 'Trainers - GRIT Fitness')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="text-center mb-5">
        <h1 class="text-primary fw-bold">Tim Trainer Profesional Kami</h1>
        <p class="text-muted">Didukung oleh pelatih bersertifikasi dengan passion membara.</p>
    </div>

    {{-- Filter Section --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('trainers') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label for="filterSpecialization" class="form-label small">Filter Spesialisasi:</label>
                        <select id="filterSpecialization" name="specialization" class="form-select form-select-sm">
                            <option value="all">Semua Spesialisasi</option>
                            @foreach($specializations as $spec)
                                <option value="{{ $spec }}" {{ ($filterSpecialization ?? '') === $spec ? 'selected' : '' }}>{{ $spec }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Terapkan Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Trainers Grid --}}
    <div class="row g-4">
        @forelse($trainers as $trainer)
            {{-- xs=12 (1 per row mobile), md=6 (2 per row), lg=3 (4 per row) --}}
            <div class="col-12 col-md-6 col-lg-4">
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
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Tidak ada trainer yang cocok dengan filter yang dipilih.
                </div>
            </div>
        @endforelse
    </div>

    @if($trainers->hasPages())
        <div class="mt-4">
            {{ $trainers->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
