@extends('layouts.guest') {{-- <-- GANTI LAYOUT KE guest --}}

@section('title', 'Admin Portal - GRIT Fitness')

@section('content')
<div class="container">
    {{-- Kita gunakan flexbox untuk menengahkan form secara vertikal --}}
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-12 col-md-8 col-lg-5 col-xl-4">
            
            <div class="text-center mb-4">
                {{-- Ikon Gembok --}}
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background-color: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    <i class="bi bi-lock-fill fs-3" style="color: var(--grit-primary);"></i>
                </div>
                
                <h1 class="h3 fw-bold mb-1" style="color: var(--grit-primary);">Admin Portal</h1>
                <p class="text-muted small">Login untuk mengakses dashboard admin</p>
            </div>

            {{-- Kartu Form Login --}}
            <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
                <div class="card-body p-4 p-md-5">

                    {{-- Tampilkan pesan error kustom jika ada --}}
                    @if (session('error'))
                        <div class="alert alert-danger small" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    {{-- Tampilkan error validasi email (bawaan Laravel) --}}
                    @error('email')
                        <div class="alert alert-danger small" role="alert">
                            {{ $message }}
                        </div>
                    @enderror

                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf

                        {{-- Input Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold">Email</label>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   style="border-radius: 0.5rem; padding: 0.75rem 1rem;">
                        </div>

                        {{-- Input Password --}}
                        <div class="mb-4">
                            <label for="password" class="form-label small fw-semibold">Password</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       placeholder="Masukkan password"
                                       required
                                       style="border-radius: 0.5rem 0 0 0.5rem; padding: 0.75rem 1rem; border-right: 0;">
                                {{-- Tombol Show/Hide Password --}}
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-radius: 0 0.5rem 0.5rem 0; border-left: 0;">
                                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Tombol Login --}}
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 0.5rem; padding: 0.75rem;">
                                Login
                            </button>
                        </div>
                    </form>
                    
                    {{-- Bagian Demo Credentials (DIHAPUS SESUAI PERMINTAAN) --}}
                    
                </div>
            </div>
            
            {{-- Link Kembali ke Beranda --}}
            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none small">
                    ← Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
</div>

{{-- Skrip untuk toggle show/hide password --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');

        toggleButton.addEventListener('click', function () {
            // Ganti tipe input
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Ganti ikon
            if (type === 'password') {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
</script>
@endsection