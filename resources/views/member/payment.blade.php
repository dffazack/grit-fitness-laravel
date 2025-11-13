@extends('layouts.app')

@section('title', 'Riwayat & Pembayaran')

@php $user = Auth::user(); @endphp

@section('content')
{{-- Sisa kode kamu (div container py-5, dst.) ... --}}
<div class="container py-5">
    
    {{-- Header --}}
    <div class="mb-5">
        <h1 class="text-primary fw-bold">Riwayat & Pembayaran</h1>
        <p class="text-muted">Kelola transaksi dan upload bukti pembayaran</p>
    </div>

    {{-- Tabs (Sesuai MemberPaymentPage.tsx) --}}
    <ul class="nav nav-tabs nav-fill mb-4" id="paymentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="true">
                <i class="bi bi-clock-history me-2"></i> Riwayat Pembayaran
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab" aria-controls="upload" aria-selected="false">
                <i class="bi bi-upload me-2"></i> Unggah Bukti Bayar
            </button>
        </li>
    </ul>

    <div class="tab-content" id="paymentTabsContent">
        
        {{-- Payment History Tab --}}
        <div class="tab-pane fade show active" id="history" role="tabpanel" aria-labelledby="history-tab">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="text-primary">Riwayat Transaksi</h4>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Deskripsi / Paket</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Ganti $paymentHistory dengan data dari controller --}}
                                @php $paymentHistory = $user->transactions()->orderBy('created_at', 'desc')->get(); @endphp
                                @forelse($paymentHistory as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('d M Y') }}</td>
                                        <td class="text-capitalize">{{ $payment->package }} Membership</td>
                                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($payment->status == 'approved' || $payment->status == 'active')
                                                <span class="badge rounded-pill bg-success-light text-success">Lunas</span>
                                            @elseif($payment->status == 'pending')
                                                <span class="badge rounded-pill bg-warning-light text-dark">Menunggu</span>
                                            @else
                                                <span class="badge rounded-pill bg-danger-light text-danger">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Belum ada riwayat transaksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Upload Proof Tab --}}
        <div class="tab-pane fade" id="upload" role="tabpanel" aria-labelledby="upload-tab">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h4 class="text-primary">Upload Bukti Pembayaran</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('member.payment.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="package" class="form-label">Pilih Paket</label>
                                    <select class="form-select @error('package') is-invalid @enderror" id="package" name="package" required>
                                        <option value="">-- Pilih Paket Membership --</option>
                                        {{-- Ganti $packages dengan data dari controller --}}
                                        @php $packages = \App\Models\MembershipPackage::where('is_active', true)->get(); @endphp
                                        @foreach($packages as $package)
                                            <option value="{{ $package->type }}" {{ request('package') == $package->type ? 'selected' : '' }}>
                                                {{ $package->name }} (Rp {{ number_format($package->price, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('package') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="proof" class="form-label">File Bukti Transfer</label>
                                    <input class="form-control @error('proof') is-invalid @enderror" type="file" id="proof" name="proof" accept="image/png, image/jpeg, image/jpg" required>
                                    <div class="form-text">Format: JPG, PNG (Max 2MB)</div>
                                    @error('proof') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 btn-lg mt-3">
                                    <i class="bi bi-send-check me-2"></i> Kirim Bukti Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm" style="background-color: var(--grit-background);">
                         <div class="card-body p-4">
                            <h5 class="text-primary mb-3">Informasi Rekening</h5>
                            <div class="alert alert-info border-0">
                                <p class="fw-bold mb-1">Bank BCA: 1234567890</p>
                                <p class="fw-bold mb-1">Bank Mandiri: 0987654321</p>
                                <p class="mb-0">a/n PT GRIT Fitness Indonesia</p>
                            </div>
                            <hr>
                            <h5 class="text-primary mb-3">Instruksi</h5>
                            <ul class="list-unstyled text-muted small">
                                <li class="mb-2"><i class="bi bi-1-circle-fill me-2 text-primary"></i> Lakukan transfer sesuai nominal paket.</li>
                                <li class="mb-2"><i class="bi bi-2-circle-fill me-2 text-primary"></i> Pilih paket dan upload bukti transfer Anda di sini.</li>
                                <li class="mb-2"><i class="bi bi-3-circle-fill me-2 text-primary"></i> Tim kami akan memverifikasi (maks. 1x24 jam).</li>
                                <li class="mb-2"><i class="bi bi-4-circle-fill me-2 text-primary"></i> Akun Anda akan aktif setelah diverifikasi.</li>
                            </ul>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- Modified by: User-Interfaced Team --}}