{{-- Fixing --}}
@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <!-- Card Utama -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Header dengan gradient -->
                    <div class="card-header bg-gradient-primary text-white text-center py-4 border-0">
    <h3 class="mb-0 fw-bold d-flex align-items-center justify-content-center gap-3">
        <i class="bi bi-shield-lock-fill fs-1 text-white"></i>
        <span class="text-white">Reset Password</span>
    </h3>
    <p class="mb-0 mt-2 small opacity-90">
        Masukkan email dan password baru Anda
    </p>
</div>

                    <!-- Body Form -->
                    <div class="card-body p-4 p-lg-5">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-envelope me-2"></i>Email Address
                                </label>
                                <input id="email" 
                                       type="email" 
                                       class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ $email ?? old('email') }}" 
                                       required 
                                       autocomplete="email" 
                                       autofocus 
                                       placeholder="contoh@email.com">
                                @error('email')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-lock-fill me-2"></i>Password Baru
                                </label>
                                <input id="password" 
                                       type="password" 
                                       class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Minimal 8 karakter">
                                @error('password')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="password-confirm" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-lock-fill me-2"></i>Konfirmasi Password
                                </label>
                                <input id="password-confirm" 
                                       type="password" 
                                       class="form-control form-control-lg rounded-3" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Ketik ulang password">
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm fw-semibold">
                                    <i class="bi bi-check2-circle me-2"></i>
                                    Reset Password
                                </button>
                            </div>

                            <!-- Back to Login -->
                            <div class="text-center mt-4">
                                <a href="{{ route('login') }}" class="text-muted small text-decoration-none hover-text-primary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Kembali ke Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Footer Credit (opsional) -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        © {{ date('Y') }} GRIT Fitness. All rights reserved.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--grit-primary), #1e3a8a) !important;
    }
    
    .min-vh-100 {
        min-height: 100vh;
    }

    /* Hover effect */
    .hover-text-primary:hover {
        color: var(--grit-primary) !important;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .card-body {
            padding: 1.5rem !important;
        }
        .btn-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
    }
</style>
@endpush