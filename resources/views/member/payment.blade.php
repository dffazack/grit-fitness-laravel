@extends('layouts.member')

@section('title', 'Pembayaran')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulir Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('member.payment.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="membership_package_id" class="form-label">Pilih Paket Membership</label>
                                <select class="form-select" id="membership_package_id" name="membership_package_id">
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="proof" class="form-label">Unggah Bukti Pembayaran</label>
                                <input class="form-control" type="file" id="proof" name="proof">
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Riwayat Transaksi</h4>
                    </div>
                    <div class="card-body">
                        @if ($transactions->isEmpty())
                            <p>Belum ada riwayat transaksi.</p>
                        @else
                            <ul class="list-group">
                                @foreach ($transactions as $transaction)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $transaction->membershipPackage->name }}</strong>
                                            <br>
                                            <small>{{ $transaction->created_at->format('d M Y') }}</small>
                                        </div>
                                        <span class="badge {{ $transaction->getStatusBadgeClass() }}">{{ $transaction->getStatusLabel() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
