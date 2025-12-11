@extends('layouts.app')

@section('title', 'Riwayat & Pembayaran')

@php $user = Auth::user(); @endphp

@section('content')
<div class="container py-5">
    
    {{-- Header --}}
    <div class="mb-5">
        <h1 class="text-primary fw-bold">Riwayat & Pembayaran</h1>
        <p class="text-muted">Kelola transaksi dan upload bukti pembayaran</p>
    </div>

    {{-- Tabs --}}
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
                                @php $paymentHistory = $user->transactions()->with('membership')->orderBy('created_at', 'desc')->get(); @endphp
                                @forelse($paymentHistory as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('d M Y') }}</td>
                                        <td class="text-capitalize">{{ ucfirst($payment->membership->type ?? '-') }} - {{ $payment->membership->duration_months ?? 0 }} bulan</td>
                                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($payment->status == 'approved' || $payment->status == 'active')
                                                <span class="badge rounded-pill bg-success text-white">Lunas</span>
                                            @elseif($payment->status == 'pending')
                                                <span class="badge rounded-pill bg-warning text-dark">Menunggu</span>
                                            @else
                                                <span class="badge rounded-pill bg-danger text-white">Ditolak</span>
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
                
                {{-- LOGIKA BARU: Cek Kelengkapan Data --}}
                @if(empty($user->phone))
                    {{-- TAMPILAN JIKA BELUM ADA NO HP --}}
                    <div class="col-12">
                        <div class="card border-warning shadow-sm">
                            <div class="card-header bg-warning text-dark fw-bold">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Data Belum Lengkap
                            </div>
                            <div class="card-body p-5 text-center">
                                <h3 class="mb-3">Satu langkah lagi!</h3>
                                <p class="text-muted mb-4" style="max-width: 600px; margin: 0 auto;">
                                    Sebelum melakukan pembayaran, kami memerlukan <strong>Nomor WhatsApp</strong> aktif Anda untuk keperluan konfirmasi membership dan jadwal latihan.
                                </p>

                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <form action="{{ route('member.profile.updatePhoneQuick') }}" method="POST">
                                            @csrf
                                            <div class="mb-3 text-start">
                                                <label class="form-label fw-bold">Nomor WhatsApp / HP</label>
                                                <input type="number" name="phone" class="form-control form-control-lg" placeholder="Contoh: 081234567890" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                                Simpan & Lanjutkan Pembayaran <i class="bi bi-arrow-right ms-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- TAMPILAN NORMAL (JIKA NO HP SUDAH ADA) --}}
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 pt-4 px-4">
                                <h4 class="text-primary">Upload Bukti Pembayaran</h4>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('member.payment.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label for="membership_package_id" class="form-label">Pilih Paket</label>
                                        <select class="form-select @error('membership_package_id') is-invalid @enderror" id="membership_package_id" name="membership_package_id" required>
                                            <option value="">-- Pilih Paket Membership --</option>
                                            @php $packages = \App\Models\MembershipPackage::where('is_active', true)->orderBy('price')->get(); @endphp
                                            @foreach($packages as $package)
                                                <option value="{{ $package->id }}" {{ old('membership_package_id') == $package->id ? 'selected' : '' }}>
                                                    {{ ucfirst($package->type) }} - {{ $package->duration_months }} bulan (Rp {{ number_format($package->price, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('membership_package_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <div class="card border-0 shadow-sm" style="background-color: #f8f9fa;">
                             <div class="card-body p-4">
                                <h5 class="text-primary mb-3">Informasi Rekening</h5>
                                <div class="alert alert-info border-0 bg-white shadow-sm">
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
                @endif
            </div>
        </div>
    </div>
</div>
@endsection