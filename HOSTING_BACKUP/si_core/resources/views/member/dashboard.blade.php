@extends('layouts.app')

@section('title', 'Dashboard Member')

@php
    // Ambil data user yang sedang login
    $user = Auth::user();
    $isActiveMember = $user->membership_status === 'active';
    $isPendingMember = $user->membership_status === 'pending';
@endphp

@section('content')
<div class="container py-5">

    {{-- Welcome Banner (Sesuai MemberDashboard.tsx) --}}
    <div class="mb-4 p-5 rounded-3 text-white" style="background: linear-gradient(90deg, var(--grit-primary), var(--grit-accent));">
        <h1 class="display-5 fw-bold text-white">Dashboard Saya</h1>
        @if($isActiveMember)
            <p class="fs-5 opacity-90">Selamat datang kembali, {{ $user->name }}! 👋</p>
        @elseif($isPendingMember)
            <p class="fs-5 opacity-90">Pembayaran Anda sedang dalam proses verifikasi.</p>
        @else
            <p class="fs-5 opacity-90">Akses penuh tersedia untuk member aktif.</p>
        @endif
    </div>

    {{-- Pending Status Alert (Sesuai MemberDashboard.tsx) --}}
    @if($isPendingMember)
        <div class="alert alert-warning border-0 d-flex align-items-center p-4" role="alert" style="background-color: var(--grit-warning-light); color: #664d03;">
            <i class="bi bi-clock-history fs-2 me-3"></i>
            <div>
                <h4 class="alert-heading">Pembayaran Sedang Diverifikasi</h4>
                <p>Tim kami sedang memverifikasi pembayaran Anda (maks. 1x24 jam). Anda akan mendapat notifikasi setelah verifikasi selesai.</p>
                <a href="{{ route('member.payment') }}" class="btn btn-sm btn-warning">Lihat Status Pembayaran</a>
            </div>
        </div>
    @endif

    {{-- Non-Member/Expired Alert (Sesuai MemberDashboard.tsx) --}}
    @if(!$isActiveMember && !$isPendingMember)
        <div class="alert alert-info border-0 d-flex align-items-center p-4" role="alert" style="background-color: var(--grit-info-light); color: #0c5460;">
            <i class="bi bi-lock-fill fs-2 me-3"></i>
            <div>
                <h4 class="alert-heading">Akses Member Diperlukan</h4>
                <p>Bergabunglah sebagai member untuk mengakses fitur lengkap dashboard, booking kelas, dan benefit eksklusif lainnya.</p>
                <a href="{{ route('membership') }}" class="btn btn-accent text-white">Lihat Paket Membership</a>
                <a href="{{ route('member.profile') }}" class="btn btn-outline-primary ms-2">Edit Profil Saya</a>
            </div>
        </div>
    @endif

    {{-- Konten Dashboard (blur jika tidak aktif) --}}
    <div class="{{ !$isActiveMember ? 'opacity-25 pe-none' : '' }}">
        {{-- Stat Cards (Sesuai MemberDashboard.tsx) --}}
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <i class="bi bi-calendar-check stat-card-icon text-primary"></i>
                        <h3 class="stat-card-value">{{ $user->membership_expiry ? $user->membership_expiry->format('d M Y') : '-' }}</h3>
                        <p class="stat-card-label">Masa Aktif Hingga</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <i class="bi bi-patch-check-fill stat-card-icon text-success"></i>
                        <h3 class="stat-card-value text-capitalize">{{ $user->membership_package ?? 'Basic' }}</h3>
                        <p class="stat-card-label">Status Membership</p>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <i class="bi bi-ticket-detailed stat-card-icon text-accent"></i>
                        <h3 class="stat-card-value">{{ $user->remaining_sessions ?? '0' }}</h3>
                        <p class="stat-card-label">Sesi Tersisa (jika ada)</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Grid (Sesuai MemberDashboard.tsx) --}}
        <div class="row g-4">
            {{-- Upcoming Classes --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="text-primary">Riwayat Booking Kelas</h4>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="list-group list-group-flush">
                            @forelse($myBookings as $booking)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <div>
                                        @if($booking->classSchedule)
                                            <h6 class="mb-1 text-primary">{{ $booking->classSchedule->custom_class_name ?? $booking->classSchedule->classList->name ?? 'Kelas Dihapus' }}</h6>
                                            <p class="small text-muted mb-0">
                                                <i class="bi bi-calendar-event me-1"></i> {{ $booking->classSchedule->day }}, {{ $booking->classSchedule->start_time->format('H:i') }}
                                                <br>
                                                <i class="bi bi-person me-1"></i> {{ $booking->classSchedule->trainer->name ?? 'N/A' }}
                                            </p>
                                        @else
                                            <h6 class="mb-1 text-danger">Jadwal Kelas Tidak Tersedia</h6>
                                            <p class="small text-muted mb-0">Jadwal untuk booking ini mungkin telah dihapus.</p>
                                        @endif
                                    </div>
                                    <span class="badge bg-success-light text-success rounded-pill">Booked</span>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted">Belum ada kelas yang Anda booking.</p>
                                    <a href="{{ route('classes') }}" class="btn btn-primary btn-sm mt-2">Booking Kelas Sekarang</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions (Sesuai MemberDashboard.tsx) --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h4 class="text-primary">Aksi Cepat</h4>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('classes') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-0">
                                <div class="rounded-circle bg-primary-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-calendar-plus text-primary fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Booking Kelas</h6>
                                    <small class="text-muted">Lihat jadwal & booking</small>
                                </div>
                            </a>
                            <a href="{{ route('member.payment') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-0">
                                <div class="rounded-circle bg-accent-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-credit-card text-accent fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Riwayat Pembayaran</h6>
                                    <small class="text-muted">Lihat transaksi</small>
                                </div>
                            </a>
                            <a href="{{ route('member.profile') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-0">
                                <div class="rounded-circle bg-success-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill text-success fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Edit Profil</h6>
                                    <small class="text-muted">Update data diri</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
{{-- Modified by: User-Interfaced Team --}}