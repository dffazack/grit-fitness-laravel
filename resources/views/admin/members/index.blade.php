@extends('layouts.admin')

@section('title', 'Kelola Member')

@section('content')

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold" style="color: var(--admin-primary);">Kelola Member</h1>
        <p class="text-muted">Kelola operasional gym dengan mudah dan efisien</p>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title-custom">Manajemen Member</h5>
            
            {{-- Search Form --}}
            <form method="GET" action="{{ route('admin.members.index') }}" style="width: 300px;">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari member..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            @if($members->isEmpty())
                <div class="text-center p-5">
                    <p class="text-muted">Tidak ada member yang ditemukan.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Paket</th>
                                <th>Status</th>
                                <th>Expired</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                            <tr>
                                <td><strong>{{ $member->name }}</strong></td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->membership->name ?? 'N/A' }}</td>
                                <td>
                                    {{-- Menggunakan badge kustom dari CSS --}}
                                    @if($member->membership_status == 'active')
                                        <span class="badge rounded-pill bg-active fw-semibold">Active</span>
                                    @elseif($member->membership_status == 'pending')
                                        <span class="badge rounded-pill bg-pending fw-semibold">Pending</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger text-white fw-semibold">{{ ucfirst($member->membership_status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $member->membership_expiry ? $member->membership_expiry->format('d M Y') : 'N/A' }}</td>
                                <td class="text-end">
                                    {{-- Tombol Detail (Modal Trigger) --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $member->id }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus member ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        @if($members->hasPages())
            <div class="card-footer">
                {{ $members->withQueryString()->links() }}
            </div>
        @endif
    </div>


    <!-- =================================================================================== -->
    <!--                                   MODAL DETAIL MEMBER                             -->
    <!-- =================================================================================== -->
    @foreach($members as $member)
    <div class="modal fade" id="detailModal-{{ $member->id }}" tabindex="-1" aria-labelledby="detailModalLabel-{{ $member->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="detailModalLabel-{{ $member->id }}">Detail Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    
                    {{-- Avatar --}}
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background-color: var(--admin-bg);">
                        <i class="bi bi-person-fill fs-1" style="color: var(--admin-primary);"></i>
                    </div>
                    
                    {{-- Info Utama --}}
                    <h4 class="fw-bold" style="color: var(--admin-primary);">{{ $member->name }}</h4>
                    <p class="text-muted">{{ $member->email }}</p>
                    
                    {{-- Detail List --}}
                    <div class="text-start mt-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">No. Telepon</span>
                                <span class="fw-semibold">{{ $member->phone ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Status</span>
                                @if($member->membership_status == 'active')
                                    <span class="badge rounded-pill bg-active fw-semibold">Active</span>
                                @elseif($member->membership_status == 'pending')
                                    <span class="badge rounded-pill bg-pending fw-semibold">Pending</span>
                                @else
                                    <span class="badge rounded-pill bg-danger text-white fw-semibold">{{ ucfirst($member->membership_status) }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Paket</span>
                                <span class="fw-semibold">{{ $member->membership->name ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Bergabung</span>
                                <span class="fw-semibold">{{ $member->joined_date ? $member->joined_date->format('d M Y') : 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Expired</span>
                                <span class="fw-semibold">{{ $member->membership_expiry ? $member->membership_expiry->format('d M Y') : 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted">Sisa Sesi</span>
                                <span class="fw-semibold">{{ $member->remaining_sessions ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Riwayat Transaksi (Opsional) --}}
                    <div class="text-start mt-4">
                        <h6 class="fw-semibold">Riwayat Transaksi</h6>
                        <div style="max-height: 150px; overflow-y: auto;">
                            @forelse($member->transactions as $tx)
                                <div class="d-flex justify-content-between p-2 border-bottom">
                                    <small>{{ $tx->package ?? 'N/A' }} ({{ $tx->created_at->format('d M Y') }})</small>
                                    <small class="fw-semibold">{{ $tx->getFormattedAmount() }}</small>
                                </div>
                            @empty
                                <small class="text-muted">Tidak ada riwayat transaksi.</small>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection
