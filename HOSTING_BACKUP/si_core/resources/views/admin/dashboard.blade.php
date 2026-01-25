{{-- ========================================================================= --}}
{{-- 2. resources/views/admin/dashboard.blade.php --}}
{{-- ========================================================================= --}}

@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan aktivitas gym hari ini')

@section('content')

    {{-- Stats Cards - Responsive Grid --}}
    <div class="row g-3 g-md-4 mb-4">
        {{-- Total Member --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background: rgba(43, 50, 130, 0.1);">
                            <i class="bi bi-people-fill fs-5" style="color: var(--admin-primary);"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success small">
                            <i class="bi bi-arrow-up"></i> 12%
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: var(--admin-primary); font-size: clamp(1.5rem, 4vw, 2rem);">{{ $totalMembers ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Total Member</p>
                </div>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background: rgba(229, 27, 131, 0.1);">
                            <i class="bi bi-cash-stack fs-5" style="color: var(--admin-accent);"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success small">
                            <i class="bi bi-arrow-up"></i> 8%
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: var(--admin-accent); font-size: clamp(1.25rem, 3.5vw, 1.75rem);">
                        Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}
                    </h3>
                    <p class="text-muted mb-0 small">Revenue Bulan Ini</p>
                </div>
            </div>
        </div>

        {{-- Pending Payments --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background: rgba(255, 193, 7, 0.1);">
                            <i class="bi bi-clock-history fs-5 text-warning"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1 text-warning" style="font-size: clamp(1.5rem, 4vw, 2rem);">{{ $pendingPayments->count() ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Pending Validasi</p>
                </div>
            </div>
        </div>

        {{-- Active Classes --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background: rgba(25, 135, 84, 0.1);">
                            <i class="bi bi-calendar-check fs-5 text-success"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1 text-success" style="font-size: clamp(1.5rem, 4vw, 2rem);">{{ $activeClasses ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Kelas Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-md-4">
        {{-- Laporan & Pembayaran --}}
        <div class="col-12 col-xl-8">
            <div class="d-flex flex-column gap-3 gap-md-4">
                
                {{-- Laporan Keuangan --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title-custom mb-0">Laporan Keuangan Harian</h5>
                    </div>
                    <div class="card-body p-0">
                        {{-- Desktop Table --}}
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Tanggal</th>
                                        <th class="border-0">Revenue</th>
                                        <th class="border-0">Berlangganan</th>
                                        <th class="border-0">Pending</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports ?? [] as $report)
                                    <tr>
                                        <td>{{ $report->date ? $report->date->format('d M Y') : 'N/A' }}</td>
                                        <td><span class="fw-semibold text-success">Rp {{ number_format($report->revenue, 0, ',', '.') }}</span></td>
                                        <td>{{ $report->subscriptions }}</td>
                                        <td><span class="badge bg-warning-subtle text-warning">{{ $report->pending }}</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Tidak ada data laporan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Cards --}}
                        <div class="d-md-none p-3">
                            @forelse($reports ?? [] as $report)
                            <div class="card mb-3 border">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">{{ $report->date ? $report->date->format('d M Y') : 'N/A' }}</h6>
                                        <span class="badge bg-warning-subtle text-warning">{{ $report->pending }} Pending</span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Revenue</small>
                                            <span class="fw-semibold text-success">Rp {{ number_format($report->revenue, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Berlangganan</small>
                                            <span class="fw-semibold">{{ $report->subscriptions }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-muted py-3">Tidak ada data laporan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Pembayaran Pending --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title-custom mb-0">Pembayaran Menunggu Validasi</h5>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-primary">
                            <span class="d-none d-sm-inline">Lihat Semua</span>
                            <i class="bi bi-arrow-right d-sm-none"></i>
                        </a>
                    </div>
                    <div class="card-body p-3">
                        @forelse($pendingPayments ?? [] as $payment)
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-3 mb-3 pb-3 border-bottom">
                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1">{{ $payment->user->name ?? 'User' }}</h6>
                                <p class="text-muted small mb-1">
                                    @if($payment->membership)
                                        {{ ucfirst($payment->membership->type) }} - {{ $payment->membership->name }}
                                    @else
                                        {{ $payment->package ?? 'N/A' }}
                                    @endif
                                </p>
                                <p class="mb-0 small fw-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill bg-warning text-dark px-3 py-2">Menunggu</span>
                                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-primary">
                                    <span class="d-none d-sm-inline">Validasi</span>
                                    <i class="bi bi-check2 d-sm-none"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-check-circle fs-1 mb-2"></i>
                            <p class="mb-0">Tidak ada pembayaran menunggu validasi.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking Hari Ini --}}
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title-custom mb-0">Booking Hari Ini</h5>
                </div>
                <div class="card-body p-3 d-flex flex-column gap-3">
                    @forelse($todayClasses ?? [] as $class)
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-semibold mb-1">{{ $class->name ?? $class->class_name }}</h6>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $class->start_time ? $class->start_time->format('H:i') : 'N/A' }}
                                </p>
                            </div>
                            <span class="badge bg-light text-dark">{{ $class->bookings_count }}/{{ $class->max_quota }}</span>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 10px;">
                            @php
                                $percentage = ($class->max_quota > 0) ? ($class->bookings_count / $class->max_quota) * 100 : 0;
                            @endphp
                            <div class="progress-bar" 
                                 style="width: {{ $percentage }}%; background-color: var(--admin-primary); border-radius: 10px;" 
                                 role="progressbar" 
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-calendar-x fs-1 mb-2"></i>
                        <p class="mb-0">Tidak ada kelas hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    /* Responsive Stats Cards */
    @media (max-width: 576px) {
        .card-body h3 {
            font-size: 1.5rem !important;
        }
        .card-body .small {
            font-size: 0.75rem;
        }
    }
</style>
@endpush