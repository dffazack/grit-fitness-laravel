@extends('layouts.app')

@section('title', 'Profil Saya')

@php $user = Auth::user(); @endphp

@section('content')
<div class="container py-5">
    
    {{-- Header (Sesuai ProfilePage.tsx) --}}
    <div class="mb-5">
        <h1 class="text-primary fw-bold">Kelola Profil</h1>
        <p class="text-muted">Perbarui informasi pribadi dan pengaturan akun Anda</p>
    </div>

    <div class="row g-4">
        {{-- Profile Photo Card (Sesuai ProfilePage.tsx) --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    {{-- Avatar Placeholder --}}
                    <div class="position-relative d-inline-block mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background-color: var(--grit-primary-light); color: var(--grit-primary);">
                            <i class="bi bi-person-fill" style="font-size: 60px;"></i>
                        </div>
                        <label for="photo-upload" class="btn btn-accent btn-sm rounded-circle position-absolute bottom-0 end-0 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; cursor: pointer;">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                        <input type="file" id="photo-upload" class="d-none" disabled> {{-- Disable dulu --}}
                    </div>
                    
                    <h4 class="text-primary mb-1">{{ $user->name }}</h4>
                    <p class="text-muted small mb-4">{{ $user->email }}</p>

                    {{-- Membership Status Badge (Sesuai ProfilePage.tsx) --}}
                    @if($user->membership_status == 'active')
                        <div class="alert alert-success border-0" style="background-color: var(--grit-success-light);">
                            <strong class="text-uppercase small" style="color: var(--grit-success);">{{ $user->membership_package }} Member</strong>
                            <p class="small text-dark mb-0">Aktif hingga {{ $user->membership_expiry ? $user->membership_expiry->format('d M Y') : '-' }}</p>
                        </div>
                    @elseif($user->membership_status == 'pending')
                         <div class="alert alert-warning border-0" style="background-color: var(--grit-warning-light);">
                            <strong class="text-uppercase small text-dark">Pending Approval</strong>
                            <p class="small text-muted mb-0">Menunggu validasi pembayaran</p>
                        </div>
                    @else
                        <div class="alert alert-secondary border-0">
                            <strong class="text-uppercase small text-dark">Non-Member</strong>
                            <a href="{{ route('membership') }}" class="btn btn-accent btn-sm text-white mt-2">Upgrade Membership</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form Cards (Sesuai ProfilePage.tsx) --}}
        <div class="col-lg-8">
            {{-- Personal Information Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="text-primary">Informasi Pribadi</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('member.profile.update') }}" method="POST">
                        @csrf
                        {{-- Sesuaikan @method jika route kamu PUT/PATCH --}}
                        {{-- @method('PUT') --}} 
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-2"></i> Simpan Perubahan Profil
                        </button>
                    </form>
                </div>
            </div>

            {{-- Change Password Card --}}
            <div class="card border-0 shadow-sm">
                 <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="text-primary">Ubah Password</h4>
                </div>
                <div class="card-body p-4">
                    {{-- Pastikan route 'member.profile.updatePassword' ada di web.php --}}
                    <form action="{{ route('member.profile.updatePassword') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                             @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 8 karakter">
                             @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-shield-lock me-2"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
{{-- Modified by: User-Interfaced Team --}}