{{-- File: resources/views/member/profile.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-5">
    <div class="mb-5">
        <h1 class="text-primary fw-bold">Kelola Profil</h1>
        <p class="text-muted">Perbarui informasi pribadi dan pengaturan akun Anda</p>
    </div>

    {{-- FORM UTAMA (Profil + Foto) --}}
    <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-5 justify-content-center">

            {{-- KOLOM KIRI: Avatar + Membership (Sticky saat scroll di desktop) --}}
            <div class="col-lg-4 order-lg-1">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4 p-lg-5">
                        <div class="position-relative d-inline-block mb-4">
                            <img id="avatar-preview"
                                 src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : asset('images/placeholder-avatar.svg') }}"
                                 alt="Foto Profil"
                                 class="rounded-circle shadow-sm"
                                 style="width: 140px; height: 140px; object-fit: cover; border: 5px solid #f8f9fa;">

                            <label for="profile_photo"
                                   class="btn btn-accent btn-sm rounded-circle position-absolute bottom-0 end-0 d-flex align-items-center justify-content-center shadow"
                                   style="width: 40px; height: 40px; cursor: pointer;"
                                   title="Ubah foto profil">
                                <i class="bi bi-camera-fill fs-5"></i>
                            </label>
                            <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/png, image/jpeg, image/jpg">
                        </div>

                        @error('profile_photo')
                            <div class="alert alert-danger p-2 small mt-3">{{ $message }}</div>
                        @enderror

                        <h3 class="mt-3 mb-1 fw-bold text-primary">{{ $user->name }}</h3>
                        <p class="text-muted mb-4">{{ $user->email }}</p>

                        {{-- Membership Status --}}
                        @if($user->membership_status == 'active')
                            <div class="alert alert-success border-0 py-3 mb-0" style="background: linear-gradient(135deg, #d4edda, #a8e6b1);">
                                <strong class="d-block text-success fw-bold text-uppercase">{{ $user->membership_package ?? 'Premium' }} Member</strong>
                                <small class="text-success">Aktif hingga {{ $user->membership_expiry?->format('d M Y') ?? '-' }}</small>
                            </div>
                        @elseif($user->membership_status == 'pending')
                            <div class="alert alert-warning border-0 py-3 mb-0">
                                <strong class="d-block text-warning fw-bold">Pending Approval</strong>
                                <small>Menunggu validasi pembayaran</small>
                            </div>
                        @else
                            <div class="alert alert-light border py-3 text-center mb-0">
                                <strong class="d-block mb-2">Non-Member</strong>
                                <a href="{{ route('membership') }}" class="btn btn-accent btn-sm px-4">Upgrade Membership</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Form Informasi Pribadi + Ubah Password --}}
            <div class="col-lg-8 order-lg-2">

                {{-- Informasi Pribadi --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-4 px-4">
                        <h4 class="text-primary mb-0 fw-bold">
                            <i class="bi bi-person-circle me-2"></i> Informasi Pribadi
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">No. Telepon</label>
                                <input type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                       name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-check2-circle me-2"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </div>
                </div>
                </form>

                {{-- Ubah Password --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-4 px-4">
                        <h4 class="text-primary mb-0 fw-bold">
                            <i class="bi bi-shield-lock me-2"></i> Ubah Password
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('member.profile.updatePassword') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Password Saat Ini</label>
                                    <input type="password" class="form-control form-control-lg @error('current_password') is-invalid @enderror"
                                           name="current_password">
                                    @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Password Baru</label>
                                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           name="password" placeholder="Minimal 8 karakter">
                                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control form-control-lg" name="password_confirmation">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="bi bi-shield-check me-2"></i> Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection

{{-- SCRIPT: Preview Foto Saat Upload --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('profile_photo');
    const avatarPreview = document.getElementById('avatar-preview');

    if (photoInput && avatarPreview) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    avatarPreview.src = ev.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
