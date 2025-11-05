@extends('layouts.admin')

@section('title', 'Validasi Pembayaran')

@section('content')

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold" style="color: var(--admin-primary);">Validasi Pembayaran</h1>
        <p class="text-muted">Kelola operasional gym dengan mudah dan efisien</p>
    </div>

    <!-- Sessions Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title-custom">Validasi Pembayaran</h5>
            {{-- Anda bisa tambahkan filter di sini jika mau --}}
        </div>
        <div class="card-body p-0">
            @if($transactions->isEmpty())
                <div class="text-center p-5">
                    <p class="text-muted">Tidak ada pembayaran yang menunggu validasi.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Member</th>
                                <th>Paket</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tx)
                            <tr>
                                <td>
                                    {{-- Asumsi 'created_at' adalah tanggal pengajuan --}}
                                    {{ $tx->created_at->format('d Okt Y') }}
                                </td>
                                <td>
                                    <strong>{{ $tx->user->name ?? 'User Dihapus' }}</strong><br>
                                    <small class="text-muted">{{ $tx->user->email ?? '-' }}</small>
                                </td>
                                <td>
                                    {{ $tx->membership->name ?? $tx->package }}
                                    @if($tx->membership)
                                        <br>
                                        <small class="text-muted">{{ $tx->membership->duration_months }} Bulan</small>
                                    @endif
                                </td>
                                <td>{{ $tx->getFormattedAmount() }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-pending fw-semibold">
                                        {{ $tx->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    {{-- Tombol "Validasi" ini sebenarnya adalah 2 tombol: Approve & Reject --}}
                                    {{-- Kita akan gunakan Modal untuk ini agar lebih rapi --}}
                                    
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#validateModal-{{ $tx->id }}">
                                        Validasi
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        @if($transactions->hasPages())
            <div class="card-footer">
                {{ $transactions->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- =================================================================================== -->
    <!--                                   MODAL VALIDASI                                  -->
    <!-- =================================================================================== -->
    @foreach($transactions as $tx)
    <div class="modal fade" id="validateModal-{{ $tx->id }}" tabindex="-1" aria-labelledby="validateModalLabel-{{ $tx->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="validateModalLabel-{{ $tx->id }}">Validasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Harap periksa bukti pembayaran sebelum menyetujui atau menolak.</p>
                    
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Member</span>
                            <span class="fw-semibold">{{ $tx->user->name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Paket</span>
                            <span class="fw-semibold">{{ $tx->membership->name ?? $tx->package }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Jumlah</span>
                            <span class="fw-semibold">{{ $tx->getFormattedAmount() }}</span>
                        </li>
                    </ul>

                    {{-- Bukti Pembayaran (jika ada) --}}
                    @if($tx->proof_url)
                        <div class="mb-3">
                            <a href="{{ $tx->proof_url }}" target="_blank" class="btn btn-outline-primary w-100">
                                <i class="bi bi-eye-fill me-2"></i> Lihat Bukti Pembayaran
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            Member belum mengunggah bukti pembayaran.
                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <!-- Tombol Tolak (Reject) -->
                        <form action="{{ route('admin.payments.reject', $tx->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENOLAK pembayaran ini?');">
                            @csrf
                            <button type="submit" class="btn btn-danger">Tolak</button>
                        </form>
                        
                        <!-- Tombol Setujui (Approve) -->
                        <form action="{{ route('admin.payments.approve', $tx->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENYETUJUI pembayaran ini?');">
                            @csrf
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection
