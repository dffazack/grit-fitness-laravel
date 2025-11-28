{{-- ========================================================================= --}}
{{-- 6. resources/views/admin/payments/index.blade.php --}}
{{-- ========================================================================= --}}

@extends('layouts.admin')

@section('title', 'Validasi Pembayaran')

@section('page-title', 'Validasi Pembayaran')
@section('page-subtitle', 'Kelola operasional gym dengan mudah dan efisien')

@section('content')

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold" style="color: var(--admin-primary);">Validasi Pembayaran</h1>
        <p class="text-muted">Kelola operasional gym dengan mudah dan efisien</p>
    </div>

    {{--
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    --}}

    <div class="card border-0 shadow-sm"></div>
        <div class="card-header bg-white border-0 py-3">
            <h5 class="card-title-custom mb-0">
                <i class="bi bi-credit-card me-2 d-none d-sm-inline"></i>
                Validasi Pembayaran
            </h5>
        </div>
        <div class="card-body p-0">
            @if($transactions->isEmpty())
                <div class="text-center p-5">
                    <i class="bi bi-check-circle fs-1 text-success mb-3"></i>
                    <p class="text-muted mb-0">Tidak ada pembayaran yang menunggu validasi.</p>
                </div>
            @else
                {{-- Desktop Table --}}
                <div class="table-responsive d-none d-lg-block">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Tanggal</th>
                                <th class="border-0">Member</th>
                                <th class="border-0">Paket</th>
                                <th class="border-0">Jumlah</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tx)
                            <tr>
                                <td>{{ $tx->created_at->format('d M Y') }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $tx->user->name ?? 'User Dihapus' }}</strong><br>
                                        <small class="text-muted">{{ $tx->user->email ?? '-' }}</small>
                                    </div>
                                </td>
                                <td class="text-capitalize">
                                    {{ ucfirst($tx->membership->type) }} - {{ $tx->membership->duration_months }} bulan
                                </td>
                                <td><span class="fw-semibold">{{ $tx->getFormattedAmount() }}</span></td>
                                <td><span class="badge rounded-pill bg-pending">{{ $tx->getStatusLabel() }}</span></td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#validateModal-{{ $tx->id }}">
                                        <i class="bi bi-check2-circle me-1"></i>Validasi
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile/Tablet Card View --}}
                <div class="d-lg-none p-3">
                    @foreach($transactions as $tx)
                    <div class="card mb-3 border">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1 fw-semibold">{{ $tx->user->name ?? 'User Dihapus' }}</h6>
                                    <small class="text-muted">{{ $tx->user->email ?? '-' }}</small>
                                </div>
                                <span class="badge rounded-pill bg-pending">Pending</span>
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Tanggal</small>
                                    <span class="small fw-semibold">{{ $tx->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Jumlah</small>
                                    <span class="small fw-semibold">{{ $tx->getFormattedAmount() }}</span>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted d-block">Paket</small>
                                    <span class="small fw-semibold text-capitalize">
                                        {{ ucfirst($tx->membership->type) }} - {{ $tx->membership->duration_months }} bulan
                                    </span>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#validateModal-{{ $tx->id }}">
                                <i class="bi bi-check2-circle me-1"></i> Validasi Pembayaran
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        @if($transactions->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $transactions->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Modals --}}
    @foreach($transactions as $tx)
    <div class="modal fade" id="validateModal-{{ $tx->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Validasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3 p-md-4">
                    <p class="mb-3">Harap periksa bukti pembayaran sebelum menyetujui atau menolak.</p>
                    
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between px-0 py-2">
                            <span class="text-muted">Member</span>
                            <span class="fw-semibold">{{ $tx->user->name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0 py-2">
                            <span class="text-muted">Paket</span>
                            <span class="fw-semibold text-capitalize">{{ ucfirst($tx->membership->type) }} - {{ $tx->membership->duration_months }} bulan</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0 py-2">
                            <span class="text-muted">Jumlah</span>
                            <span class="fw-semibold">{{ $tx->getFormattedAmount() }}</span>
                        </li>
                    </ul>

                    @if($tx->proof_url)
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#proofModal-{{ $tx->id }}">
                                <i class="bi bi-eye-fill me-2"></i> Lihat Bukti Pembayaran
                            </button>
                        </div>
                    @else
                        <div class="alert alert-warning text-center mb-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Member belum mengunggah bukti pembayaran.
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.payments.approve', $tx->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENYETUJUI pembayaran ini?');">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-1"></i> Setujui
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.payments.reject', $tx->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENOLAK pembayaran ini?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-circle me-1"></i> Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    {{-- Image Proof Modals --}}
    @foreach($transactions as $tx)
        @if($tx->proof_url)
        <div class="modal fade" id="proofModal-{{ $tx->id }}" tabindex="-1" aria-labelledby="proofModalLabel-{{ $tx->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="proofModalLabel-{{ $tx->id }}">Bukti Pembayaran: {{ $tx->user->name ?? 'N/A' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ $tx->full_proof_url }}" class="img-fluid rounded" alt="Bukti Pembayaran">
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach

@endsection