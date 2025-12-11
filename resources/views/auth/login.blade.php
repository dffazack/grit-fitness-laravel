@extends('layouts.app')

@section('title', 'Login - GRIT Fitness')

@section('content')
<div class="container py-5" style="min-height: 80vh;">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            {{-- Header Teks --}}
            <div class="text-center mb-4">
                <h1 class="text-primary fw-bold">Selamat Datang</h1>
                <p class="text-muted">Masuk untuk melanjutkan ke GRIT Fitness</p>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Input Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small text-uppercase">Email Address</label>
                            <input type="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="name@example.com"
                                required
                                autofocus>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Password --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label fw-semibold small text-uppercase">Password</label>
                            </div>
                            <input type="password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Remember Me & Forgot Password --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small text-muted" for="remember">
                                    Ingat Saya
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none fw-semibold">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>

                        {{-- Tombol Login Utama --}}
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                Masuk
                            </button>
                        </div>

                        {{-- Divider "ATAU" --}}
                        <div class="d-flex align-items-center mb-3">
                            <hr class="flex-grow-1 my-0 text-muted">
                            <span class="px-3 text-muted small">ATAU</span>
                            <hr class="flex-grow-1 my-0 text-muted">
                        </div>

                        {{-- Tombol Login Google --}}
                        <div class="d-grid mb-4">
                            <a href="{{ route('auth.google') }}" class="btn btn-outline-secondary btn-lg d-flex align-items-center justify-content-center position-relative">
                                {{-- Ikon Google SVG (Pasti muncul tanpa install font tambahan) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48" class="me-2">
                                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                                    <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                                    <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                                    <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                                </svg>
                                <span class="fw-semibold text-dark">Masuk dengan Google</span>
                            </a>
                        </div>

                        {{-- Footer Link --}}
                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-bold ms-1">
                                    Daftar Sekarang
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- Copyright kecil di bawah --}}
            <div class="text-center mt-4 text-muted small">
                &copy; {{ date('Y') }} GRIT Fitness. All rights reserved.
            </div>
        </div>
    </div>
</div>
@endsection