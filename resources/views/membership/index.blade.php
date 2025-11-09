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
                            ⭐ Recommended
                        </div>
                        @endif

                        {{-- Package Duration --}}
                        <div class="package-duration mb-3">
                            {{ $package->duration_months }} Bulan
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
                            ⭐ Recommended
                        </div>
                        @endif

                        {{-- Package Duration --}}
                        <div class="package-duration mb-3">
                            {{ $package->duration_months }} Bulan
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

@push('styles')
<style>
/* ============================================
   MEMBERSHIP PAGE V2 - Sesuai Gambar
   ============================================ */

/* Membership Card V2 */
.membership-card-v2 {
    background: #FFFFFF;
    border: 1.5px solid #E0E0E0;
    border-radius: 16px;
    padding: 28px 24px;
    position: relative;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.membership-card-v2:hover {
    border-color: var(--grit-primary);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px);
}

/* Recommended Badge */
.membership-card-v2.recommended {
    border-color: var(--grit-accent);
}

.recommended-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--grit-accent);
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    z-index: 10;
}

/* Package Header with Icon */
.package-header {
    margin-bottom: 32px;
}

.package-icon {
    font-size: 48px;
    line-height: 1;
}

.package-header h2 {
    color: var(--grit-text);
    font-size: 32px;
}

/* Package Duration */
.package-duration {
    text-align: center;
    font-size: 15px;
    font-weight: 500;
    color: var(--grit-text-light);
    padding-bottom: 12px;
    border-bottom: 1px solid #E0E0E0;
}

/* Package Price */
.package-price {
    text-align: center;
    padding: 16px 0;
}

.price-amount {
    font-size: 28px;
    font-weight: 700;
    color: var(--grit-text);
    line-height: 1.2;
}

.price-period {
    font-size: 14px;
    margin-top: 4px;
}

/* Package Features */
.package-features {
    list-style: none;
    padding: 0;
    margin: 0;
    flex-grow: 1;
}

.package-features li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 8px 0;
    font-size: 14px;
    color: var(--grit-text);
}

.package-features li i {
    font-size: 16px;
    flex-shrink: 0;
    margin-top: 2px;
}

.package-features li span {
    flex: 1;
    line-height: 1.5;
}

/* Buttons */
.btn-lg {
    padding: 14px 24px;
    font-size: 15px;
    font-weight: 600;
    border-radius: 10px;
}

/* Section Spacing */
.package-section {
    margin-bottom: 64px;
}

.package-section:last-child {
    margin-bottom: 0;
}

/* Empty State */
.empty-state {
    padding: 48px 24px;
}

.empty-state-icon {
    font-size: 64px;
    color: var(--grit-text-light);
    display: block;
    margin-bottom: 16px;
}

.empty-state-text {
    color: var(--grit-text-light);
    font-size: 18px;
    margin: 0;
}

/* Responsive */
@media (max-width: 992px) {
    .package-icon {
        font-size: 40px;
    }
    
    .package-header h2 {
        font-size: 28px;
    }
    
    .package-header p {
        font-size: 14px;
    }
}

@media (max-width: 768px) {
    .price-amount {
        font-size: 24px;
    }
    
    .membership-card-v2 {
        padding: 24px 20px;
    }
    
    .package-header {
        margin-bottom: 24px;
    }
    
    .package-header h2 {
        font-size: 24px;
    }
    
    .package-icon {
        font-size: 36px;
    }
    
    .recommended-badge {
        font-size: 12px;
        padding: 5px 12px;
    }
}

@media (max-width: 576px) {
    .package-header .d-flex {
        flex-direction: column;
        text-align: center;
        align-items: center !important;
    }
    
    .package-header p {
        text-align: center;
    }
}
</style>
@endpush