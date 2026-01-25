@extends('layouts.app')

@section('title', 'Detail Member: ' . $member->name)

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="color: var(--grit-primary);">Detail Member</h1>
        <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Daftar Member
        </a>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Info Member -->
        <div class="col-lg-4">
            <x-default-card>
                <div class="text-center mb-3">
                    {{-- Placeholder Avatar --}}
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 100px; height: 100px; background-color: var(--grit-primary); color: white; font-size: 3rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
                <h3 class="text-center" style="color: var(--grit-primary);">{{ $member->name }}</h3>
                <p class="text-center text-muted">{{ $member->email }}</p>
                <div class="text-center">
                    <x-status-label status="{{ $member->membership_status ?? 'non-member' }}" />
                </div>
                
                <hr>
                
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong class="text-muted d-block">Telepon</strong>
                        <span>{{ $member->phone ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-2">
                        <strong class="text-muted d-block">Paket Saat Ini</strong>
                        <span>{{ $member->membership->package_name ?? 'Tidak ada paket' }}</span>
                    </li>
                    <li class="mb-2">
                        <strong class="text-muted d-block">Tanggal Bergabung</strong>
                        <span>{{ $member->created_at ? $member->created_at->format('d M Y') : 'N/A' }}</span>
                    </li>
                    <li class="mb-2">
                        <strong class="text-muted d-block">Membership Berakhir</strong>
                        <span>{{ $member->membership_expires_at ? $member->membership_expires_at->format('d M Y') : 'N/A' }}</span>
                    </li>
                </ul>
            </x-default-card>
        </div>

        <!-- Kolom Kanan: Riwayat Transaksi -->
        <div class="col-lg-8">
            <x-default-card title="Riwayat Transaksi">
                @if($member->transactions->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted">Member ini belum memiliki riwayat transaksi.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Paket</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($member->transactions->sortByDesc('created_at') as $tx)
                                <tr>
                                    <td><strong>#{{ $tx->id }}</strong></td>
                                    <td>{{ $tx->membership->package_name ?? 'N/A' }}</td>
                                    <td>Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                                    <td><x-status-label status="{{ $tx->status }}" /></td>
                                    <td>{{ $tx->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-default-card>
        </div>
    </div>
</div>
@endsection
{{-- Modified by: User-Interfaced Team -- }}
