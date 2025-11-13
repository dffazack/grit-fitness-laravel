{{-- ========================================================================= --}}
{{-- 3. resources/views/admin/members/index.blade.php --}}
{{-- ========================================================================= --}}

@extends('layouts.admin')

@section('title', 'Kelola Member')

@section('page-title', 'Kelola Member')
@section('page-subtitle', 'Kelola operasional gym dengan mudah dan efisien')

@section('content')

    {{-- Search & Filter Section - Responsive --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <form method="GET" action="{{ route('admin.members.index') }}">
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Cari member..." 
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                                <span class="d-none d-sm-inline ms-1">Cari</span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-4 col-lg-6 text-md-end">
                    <div class="d-flex gap-2 justify-content-end">
                        <button class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="bi bi-printer"></i>
                            <span class="d-none d-lg-inline ms-1">Print</span>
                        </button>
                        <button class="btn btn-outline-success">
                            <i class="bi bi-file-earmark-excel"></i>
                            <span class="d-none d-lg-inline ms-1">Export</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="card-title-custom mb-0">
                <i class="bi bi-people me-2 d-none d-sm-inline"></i>
                Manajemen Member
            </h5>
        </div>
        <div class="card-body p-0">
            @if($members->isEmpty())
                <div class="text-center p-5">
                    <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                    <p class="text-muted mb-0">Tidak ada member yang ditemukan.</p>
                </div>
            @else
                {{-- Desktop Table View --}}
                <div class="table-responsive d-none d-lg-block">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Nama</th>
                                <th class="border-0">Email</th>
                                <th class="border-0">Paket</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Expired</th>
                                <th class="border-0 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2"
                                             style="width: 36px; height: 36px;">
                                            <i class="bi bi-person-fill text-muted"></i>
                                        </div>
                                        <strong>{{ $member->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->membership->name ?? 'N/A' }}</td>
                                <td>
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
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $member->id }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
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

                {{-- Mobile/Tablet Card View --}}
                <div class="d-lg-none p-3">
                    @foreach($members as $member)
                    <div class="card mb-3 border">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-fill text-muted"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $member->name }}</h6>
                                        <small class="text-muted">{{ $member->email }}</small>
                                    </div>
                                </div>
                                @if($member->membership_status == 'active')
                                    <span class="badge rounded-pill bg-active">Active</span>
                                @elseif($member->membership_status == 'pending')
                                    <span class="badge rounded-pill bg-pending">Pending</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">{{ ucfirst($member->membership_status) }}</span>
                                @endif
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Paket</small>
                                    <span class="small fw-semibold">{{ $member->membership->name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Expired</small>
                                    <span class="small fw-semibold">{{ $member->membership_expiry ? $member->membership_expiry->format('d M Y') : 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $member->id }}">
                                    <i class="bi bi-eye-fill me-1"></i> Detail
                                </button>
                                <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus member ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        @if($members->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $members->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Modals --}}
    @foreach($members as $member)
    <div class="modal fade" id="detailModal-{{ $member->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">Detail Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; background-color: var(--admin-bg);">
                        <i class="bi bi-person-fill fs-1" style="color: var(--admin-primary);"></i>
                    </div>
                    
                    <h4 class="fw-bold" style="color: var(--admin-primary);">{{ $member->name }}</h4>
                    <p class="text-muted">{{ $member->email }}</p>
                    
                    <div class="text-start mt-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-muted">No. Telepon</span>
                                <span class="fw-semibold">{{ $member->phone ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-muted">Status</span>
                                @if($member->membership_status == 'active')
                                    <span class="badge rounded-pill bg-active">Active</span>
                                @elseif($member->membership_status == 'pending')
                                    <span class="badge rounded-pill bg-pending">Pending</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">{{ ucfirst($member->membership_status) }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-muted">Paket</span>
                                <span class="fw-semibold">{{ $member->membership->name ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-muted">Bergabung</span>
                                <span class="fw-semibold">{{ $member->joined_date ? $member->joined_date->format('d M Y') : 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-muted">Expired</span>
                                <span class="fw-semibold">{{ $member->membership_expiry ? $member->membership_expiry->format('d M Y') : 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-muted">Sisa Sesi</span>
                                <span class="fw-semibold">{{ $member->remaining_sessions ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>

                    @if($member->transactions->count() > 0)
                    <div class="text-start mt-4">
                        <h6 class="fw-semibold mb-3">Riwayat Transaksi</h6>
                        <div style="max-height: 200px; overflow-y: auto;">
                            @foreach($member->transactions as $tx)
                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                    <small>{{ $tx->package ?? 'N/A' }} ({{ $tx->created_at->format('d M Y') }})</small>
                                    <small class="fw-semibold">{{ $tx->getFormattedAmount() }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection
{{-- Modified by: User-Interfaced Team -- }}