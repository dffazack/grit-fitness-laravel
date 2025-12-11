@extends('layouts.app')

@section('title', 'Daftar Akun - GRIT Fitness')

@section('content')
<div class="container py-5" style="min-height: 80vh;">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            {{-- Header Teks --}}
            <div class="text-center mb-4">
                <h1 class="text-primary fw-bold">Buat Akun Baru</h1>
                <p class="text-muted">Bergabunglah dengan GRIT Fitness sekarang</p>
            </div>
            
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold small text-uppercase">Nama Lengkap</label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Nama lengkap Anda"
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small text-uppercase">Alamat Email</label>
                            <input type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="name@example.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- No. Telepon --}}
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold small text-uppercase">No. Telepon</label>
                            <input type="tel" 
                                   class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   placeholder="Contoh: 08123456789"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Password --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold small text-uppercase">Password</label>
                                <input type="password" 
                                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Minimal 8 karakter"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- Konfirmasi Password --}}
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold small text-uppercase">Konfirmasi</label>
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Ulangi password"
                                       required>
                            </div>
                        </div>
                        
                        {{-- Tombol Daftar Utama --}}
                        <div class="d-grid mb-3 mt-2">
                            {{-- Saya ganti ke btn-primary agar konsisten dengan login, ubah ke btn-accent jika perlu --}}
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                Buat Akun
                            </button>
                        </div>

                         {{-- Divider "ATAU" --}}
                         <div class="d-flex align-items-center mb-3">
                            <hr class="flex-grow-1 my-0 text-muted">
                            <span class="px-3 text-muted small">ATAU</span>
                            <hr class="flex-grow-1 my-0 text-muted">
                        </div>

                        {{-- Tombol Daftar Google --}}
                        <div class="d-grid mb-4">
                            <a href="{{ route('auth.google') }}" class="btn btn-outline-secondary btn-lg d-flex align-items-center justify-content-center">
                                {{-- Ikon Google SVG --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48" class="me-2">
                                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                                    <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                                    <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                                    <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                                </svg>
                                <span class="fw-semibold text-dark">Daftar dengan Google</span>
                            </a>
                        </div>
                        
                        <p class="text-center mt-3 mb-0 text-muted">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">
                                Masuk di sini
                            </a>
                        </p>
                    </form>
                </div>
            </div>
            
            <div class="alert alert-info mt-4 shadow-sm border-0 d-flex align-items-center">
                <i class="fa fa-info-circle me-3 fs-4"></i>
                <div>
                    Setelah membuat akun, Anda dapat langsung memilih paket membership di halaman Membership.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection