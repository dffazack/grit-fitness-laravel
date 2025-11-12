@extends('layouts.app')

@section('title', 'Paket Membership - GRIT Fitness')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="text-center mb-5">
        <h1 class="text-primary fw-bold">Paket Membership</h1>
        <p class="text-muted mx-auto" style="max-width: 700px;">
            Pilih paket yang sesuai dengan kebutuhan dan target fitness Anda. Semua paket sudah termasuk akses penuh ke fasilitas gym.
        </p>
    </div>

    {{-- Membership Packages --}}
    <div class="row g-4 justify-content-center mb-5">
        @forelse($packages as $package)
            <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
                <div class="card membership-card h-100 {{ $package->type == 'premium' ? 'featured' : '' }}">
                    <div class="card-body d-flex flex-column text-center">
                        <h3 class="mb-3" style="color: {{ $package->type == 'premium' ? 'var(--grit-primary)' : ($package->type == 'vip' ? 'var(--grit-accent)' : '#6C757D') }};">
                            {{ $package->name }}
                        </h3>
                        <h1 class="display-5 fw-bold mb-2 membership-price" style="color: {{ $package->type == 'premium' ? 'var(--grit-primary)' : ($package->type == 'vip' ? 'var(--grit-accent)' : '#6C757D') }};">
                            {{ 'Rp ' . number_format($package->price / 1000000, 1, ',', '') . 'JT' }}
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

                        {{-- Tombol Pilih Paket --}}
                        <div class="mt-auto">
                            @guest
                                <a href="{{ route('register') }}" class="btn {{ $package->type == 'premium' ? 'btn-primary' : ($package->type == 'vip' ? 'btn-accent' : 'btn-secondary') }} w-100 btn-lg">
                                    Daftar & Pilih Paket
                                </a>
                            @else
                                {{-- Jika user sudah login, arahkan ke payment atau tampilkan info --}}
                                @if(Auth::user()->membership_status == 'non-member' || Auth::user()->membership_status == 'expired')
                                    <a href="{{ route('member.payment', ['package' => $package->type]) }}" class="btn {{ $package->type == 'premium' ? 'btn-primary' : ($package->type == 'vip' ? 'btn-accent' : 'btn-secondary') }} w-100 btn-lg">
                                        Pilih Paket Ini
                                    </a>
                                @elseif(Auth::user()->membership_package == $package->type)
                                    <button class="btn btn-success w-100 btn-lg" disabled>Paket Anda Saat Ini</button>
                                @else
                                     <button class="btn btn-outline-secondary w-100 btn-lg" disabled>Upgrade (Segera Hadir)</button>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                 <div class="alert alert-info text-center">
                    Paket membership belum tersedia.
                 </div>
            </div>
        @endforelse
    </div>

    {{-- Facilities Section --}}
    <div class="mt-5 pt-5 border-top">
        <div class="text-center mb-5">
            <h2 class="text-primary fw-bold">Fasilitas Premium Kami</h2>
            <p class="text-muted">Nikmati fasilitas terbaik untuk mendukung latihan Anda</p>
        </div>
        <div class="row g-4">
            @forelse($facilities as $facility)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 text-center p-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="{{ $facility->icon ?? 'bi-building' }} fs-1 text-accent"></i>
                            </div>
                            <h5 class="fw-semibold mb-2">{{ $facility->name }}</h5>
                            <p class="text-muted small mb-0">{{ $facility->description }}</p>
                        </div>
                    </div>
                </div>
            @empty
                 <div class="col-12">
                     <div class="alert alert-info text-center">
                        Informasi fasilitas belum tersedia.
                     </div>
                 </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
{{-- Modified by: User-Interfaced Team --}}