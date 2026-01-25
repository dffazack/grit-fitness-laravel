@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4 p-md-5">

                    {{-- Icon --}}
                    <div class="mb-4">
                        <i class="bi bi-key-fill" style="font-size: 4rem; color: var(--grit-accent);"></i>
                    </div>

                    {{-- Title --}}
                    <h2 class="fw-bold text-primary mb-3">Lupa Password?</h2>

                    {{-- Instructions --}}
                    <p class="text-muted mb-4">
                        Tidak masalah. Masukkan alamat email Anda di bawah ini dan kami akan mengirimkan link untuk mengatur ulang password Anda.
                    </p>

                    {{-- Session Status --}}
                    @if (session('status'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>
                                {{ session('status') }}
                            </div>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">
                            <i class="bi bi-send me-2"></i> Kirim Link Reset Password
                        </button>
                    </form>

                    {{-- Back to Login --}}
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="small text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke halaman Login
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
