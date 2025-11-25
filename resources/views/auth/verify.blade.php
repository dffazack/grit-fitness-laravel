@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4 p-md-5">

                    {{-- Icon --}}
                    <div class="mb-4">
                        <i class="bi bi-envelope-check-fill" style="font-size: 4rem; color: var(--grit-accent);"></i>
                    </div>

                    {{-- Title --}}
                    <h2 class="fw-bold text-primary mb-3">Verifikasi Email Anda</h2>

                    {{-- Success Message --}}
                    @if (session('resent'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>
                                Link verifikasi baru telah berhasil dikirim ke alamat email Anda.
                            </div>
                        </div>
                    @endif

                    {{-- Instructions --}}
                    <p class="text-muted">
                        Sebelum melanjutkan, silakan periksa email Anda untuk menemukan link verifikasi. Proses ini penting untuk mengamankan akun Anda.
                    </p>
                    <p class="text-muted mt-3">
                        Jika Anda tidak menerima email, klik tombol di bawah ini.
                    </p>

                    {{-- Resend Form --}}
                    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg mt-3 px-5">
                           <i class="bi bi-send me-2"></i> Kirim Ulang Link
                        </button>
                    </form>

                    {{-- Back to Home --}}
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="small text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
