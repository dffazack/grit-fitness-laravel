@extends('layouts.app')

@section('title', 'Login - GRIT Fitness')

@section('content')
<div class="container py-5" style="min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="text-center mb-4">
                {{-- Logo bisa ditambahkan di sini jika ada --}}
                {{-- <img src="{{ asset('images/logo.png') }}" alt="GRIT Fitness" style="height: 60px;" class="mb-3"> --}}
                <h1 class="text-primary fw-bold">Selamat Datang Kembali</h1>
                <p class="text-muted">Masuk ke akun member Anda</p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="Masukkan email Anda"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small" for="remember">
                                    Ingat Saya
                                </label>
                            </div>
                            {{-- Tautan Lupa Password bisa ditambahkan jika ada fiturnya --}}
                            {{-- <a href="#" class="small text-primary text-decoration-none">Lupa Password?</a> --}}
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Masuk
                            </button>
                        </div>

                        <p class="text-center mt-3 mb-0 small">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold">
                                Daftar di sini
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- Modified by: User-Interfaced Team -- }}