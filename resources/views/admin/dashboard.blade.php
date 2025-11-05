@extends('layouts.admin')

@section('title', 'Laporan Harian')

@section('content')

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold" style="color: var(--admin-primary);">Laporan Harian</h1>
        <p class="text-muted">Ringkasan laporan keuangan dan aktivitas harian</p>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri (Laporan & Pembayaran) --}}
        <div class="col-12 col-lg-8 d-flex flex-column gap-4">
            
            <!-- Laporan Keuangan Harian -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title-custom">Laporan Keuangan Harian</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Revenue</th>
                                    <th>Jumlah Berlangganan Member</th>
                                    <th>Pending (Menunggu Validasi)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                <tr>
                                    <td>{{ $report->date ? $report->date->format('d M Y') : 'N/A' }}</td>
                                    <td><span class="fw-semibold text-success">Rp {{ number_format($report->revenue / 1000000, 1) }}M</span></td>
                                    <td>{{ $report->subscriptions }}</td>
                                    <td>{{ $report->pending }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Tidak ada data laporan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Menunggu Validasi -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title-custom">Pembayaran Menunggu Validasi</h5>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @forelse($pendingPayments as $payment)
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="fw-semibold mb-1">{{ $payment->user->name ?? 'User' }}</h6>
                            <p class="text-muted small mb-1">
                                {{ $payment->membership->name ?? $payment->package }} - {{ $payment->duration ?? '12' }} Bulan
                            </p>
                            <p class="mb-0 small">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill bg-warning text-dark px-3 py-2">Menunggu</span>
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-primary">Validasi</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        Tidak ada pembayaran menunggu validasi.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        {{-- Kolom Kanan (Booking Hari Ini) --}}
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title-custom">Booking Hari Ini</h5>
                </div>
                <div class="card-body d-flex flex-column gap-4">
                    @forelse($todayClasses as $class)
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-semibold mb-1">{{ $class->name ?? $class->class_name }}</h6>
                                <p class="text-muted small mb-0">{{ $class->start_time ? $class->start_time->format('H:i') : 'N/A' }}</p>
                            </div>
                            <span class="text-muted small">{{ $class->bookings_count }}/{{ $class->max_quota }}</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 10px;">
                            @php
                                $percentage = ($class->max_quota > 0) ? ($class->bookings_count / $class->max_quota) * 100 : 0;
                            @endphp
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: var(--admin-primary); border-radius: 10px;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        Tidak ada kelas hari ini.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection