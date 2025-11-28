@extends('layouts.app')

@section('title', 'Paket Membership - GRIT Fitness')

@section('content')

<div class="membership-page">
    <div class="container py-5">
        
        {{-- Student Packages Section --}}
        @if($studentPackages->isNotEmpty())
        <div class="package-section mb-5">
            {{-- Section Header --}}
            <div class="package-header mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="package-icon">
                        🎓
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold">Paket Student</h2>
                        <p class="text-muted mb-0">Khusus untuk pelajar & mahasiswa dengan kartu pelajar aktif</p>
                    </div>
                </div>
            </div>

            {{-- Student Package Cards --}}
            <div class="row g-4">
                @foreach($studentPackages as $package)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="membership-card-v2 h-100 {{ $package->is_popular ? 'recommended' : '' }}">
                        
                        {{-- Recommended Badge --}}
                        @if($package->is_popular)
                        <div class="recommended-badge">
                            🔥 Populer
                        </div>
                        @endif

                        {{-- Package Duration --}}
                        <div class="package-duration mb-3">
                            {{ ucfirst($package->type) }} - {{ $package->duration_months }} Bulan
                        </div>

                        {{-- Package Price --}}
                        <div class="package-price mb-3">
                            <h3 class="price-amount mb-0">{{ $package->getFormattedPrice() }}</h3>
                            <p class="price-period text-muted mb-0">Bulanan</p>
                        </div>

                        {{-- Package Features --}}
                        <ul class="package-features mb-4">
                            @if(is_array($package->features))
                                @foreach($package->features as $feature)
                                <li>
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    <span>{{ $feature }}</span>
                                </li>
                                @endforeach
                            @endif
                        </ul>

                        {{-- CTA Button --}}
                        <div class="mt-auto">
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-accent w-100 btn-lg">
                                    Pilih Paket
                                </a>
                            @else
                                @if(Auth::user()->membership_status == 'non-member' || Auth::user()->membership_status == 'expired')
                                    <a href="{{ route('member.payment', ['package_id' => $package->id]) }}" class="btn btn-accent w-100 btn-lg">
                                        Pilih Paket
                                    </a>
                                @elseif(Auth::user()->membership_package_id == $package->id)
                                    <button class="btn btn-success w-100 btn-lg" disabled>
                                        Paket Anda Saat Ini
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary w-100 btn-lg" disabled>
                                        Upgrade (Segera Hadir)
                                    </button>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Regular Packages Section --}}
        @if($regularPackages->isNotEmpty())
        <div class="package-section">
            {{-- Section Header --}}
            <div class="package-header mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="package-icon">
                        👑
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold">Paket Reguler</h2>
                        <p class="text-muted mb-0">Untuk umum dengan fasilitas lengkap dan personal trainer</p>
                    </div>
                </div>
            </div>

            {{-- Regular Package Cards --}}
            <div class="row g-4">
                @foreach($regularPackages as $package)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="membership-card-v2 h-100 {{ $package->is_popular ? 'recommended' : '' }}">
                        
                        {{-- Recommended Badge --}}
                        @if($package->is_popular)
                        <div class="recommended-badge">
                            🔥 Populer
                        </div>
                        @endif

                        {{-- Package Duration --}}
                        <div class="package-duration mb-3">
                            {{ ucfirst($package->type) }} - {{ $package->duration_months }} Bulan
                        </div>

                        {{-- Package Price --}}
                        <div class="package-price mb-3">
                            <h3 class="price-amount mb-0">{{ $package->getFormattedPrice() }}</h3>
                            <p class="price-period text-muted mb-0">Bulanan</p>
                        </div>

                        {{-- Package Features --}}
                        <ul class="package-features mb-4">
                            @if(is_array($package->features))
                                @foreach($package->features as $feature)
                                <li>
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    <span>{{ $feature }}</span>
                                </li>
                                @endforeach
                            @endif
                        </ul>

                        {{-- CTA Button --}}
                        <div class="mt-auto">
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-primary w-100 btn-lg">
                                    Pilih Paket
                                </a>
                            @else
                                @if(Auth::user()->membership_status == 'non-member' || Auth::user()->membership_status == 'expired')
                                    <a href="{{ route('member.payment', ['package_id' => $package->id]) }}" class="btn btn-primary w-100 btn-lg">
                                        Pilih Paket
                                    </a>
                                @elseif(Auth::user()->membership_package_id == $package->id)
                                    <button class="btn btn-success w-100 btn-lg" disabled>
                                        Paket Anda Saat Ini
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary w-100 btn-lg" disabled>
                                        Upgrade (Segera Hadir)
                                    </button>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Empty State --}}
        @if($studentPackages->isEmpty() && $regularPackages->isEmpty())
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="bi bi-inbox empty-state-icon"></i>
                <p class="empty-state-text">Paket membership belum tersedia.</p>
            </div>
        </div>
        @endif

        {{-- Facilities Section (Optional) --}}
        @if($facilities->isNotEmpty())
        <div class="mt-5 pt-5 border-top">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary">Fasilitas Premium Kami</h2>
                <p class="text-muted">Nikmati fasilitas terbaik untuk mendukung latihan Anda</p>
            </div>
            <div class="row g-4">
                @foreach($facilities as $facility)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('storage/' . $facility->image) }}" 
                             alt="{{ $facility->name }}" 
                             class="card-img-top" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="fw-semibold text-center mb-3">{{ $facility->name }}</h5>
                            <ul class="list-unstyled text-start mb-0">
                                @foreach(explode(",", $facility->description ?? '') as $feature)
                                    @if(trim($feature))
                                        <li class="mb-2 d-flex align-items-start">
                                            <i class="bi bi-check-circle-fill text-success me-2 mt-1" style="flex-shrink: 0;"></i>
                                            <span class="small text-muted">{{ trim($feature) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
