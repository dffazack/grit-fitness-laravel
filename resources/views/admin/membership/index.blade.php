@extends('layouts.admin')

@section('title', 'Kelola Paket Membership')

@section('content')

    {{-- Menggunakan komponen tab terpusat --}}
    @include('admin.components.datamaster-tabs')

    <!-- Header "Kelola Paket" dan Tombol Tambah -->
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center mb-4">
        <h3 class="mb-2 mb-sm-0" style="color: var(--admin-primary);">Kelola Paket Membership</h3>
        <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addPackageModal">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Paket
        </button>
    </div>

    <!-- Grid Kartu Paket -->
    <div class="row g-4">
        @forelse($packages as $package)
            <div class="col-12 col-lg-6 col-xl-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-body d-flex flex-column">
                        {{-- Tags (Type, Popular, etc) --}}
                        <div class="mb-3">
                            <span class="badge" style="background-color: #E3F2FD; color: #1E88E5;">
                                {{ $types[$package->type] ?? ucfirst($package->type) }}
                            </span>
                            @if($package->is_popular)
                            <span class="badge" style="background-color: #FCE4EC; color: #D81B60;">
                                Populer
                            </span>
                            @endif
                        </div>
                        
                        {{-- Nama & Harga --}}
                        <h4 class="fw-bold" style="color: var(--admin-primary);">{{ $package->name }}</h4>
                        <h2 class="fw-bold mb-3">{{ $package->getFormattedPrice() }}</h2>
                        
                        {{-- Fitur --}}
                        <ul class="list-unstyled mb-4">
                            @if(is_array($package->features))
                                @foreach($package->features as $feature)
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>{{ $feature }}</li>
                                @endforeach
                            @endif
                        </ul>

                    </div>
                    {{-- Tombol Aksi --}}
                    <div class="card-footer bg-white border-0 pt-0 pb-3 px-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal-{{ $package->id }}">
                            <i class="bi bi-pencil-fill"></i> Edit
                        </button>
                        
                        <form action="{{ route('admin.memberships.destroy', $package->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus paket ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash-fill"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <p class="text-muted">Belum ada paket membership yang dibuat.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Peringatan di Bawah -->
    <div class="alert alert-warning d-flex align-items-center mt-4" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>
            Tip: Paket yang ditandai 'Populer' akan ditampilkan dengan *highlight* khusus di halaman utama.
        </div>
    </div>


    <!-- =================================================================================== -->
    <!--                               MODAL TAMBAH PAKET                                  -->
    <!-- =================================================================================== -->
    <div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 12px;">
                <form action="{{ route('admin.memberships.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="addPackageModalLabel">Tambah Paket Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="form_type" value="add">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="add_name" class="form-label">Nama Paket</label>
                                <input type="text" class="form-control" id="add_name" name="name" value="{{ old('name') }}" placeholder="Contoh: Premium 1 Bulan" required>
                            </div>
                            <div class="col-md-6">
                                <label for="add_type" class="form-label">Tipe Paket</label>
                                <select class="form-select" id="add_type" name="type" required>
                                    @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="add_price" class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" id="add_price" name="price" value="{{ old('price') }}" placeholder="Contoh: 300000" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="add_duration_months" class="form-label">Durasi (Bulan)</label>
                                <input type="number" class="form-control" id="add_duration_months" name="duration_months" value="{{ old('duration_months') }}" placeholder="Contoh: 1" min="1" required>
                            </div>
                            <div class="col-12">
                                <label for="add_features" class="form-label">Fitur (Pisahkan dengan koma)</label>
                                <textarea class="form-control" id="add_features" name="features" rows="3" placeholder="Contoh: Akses gym 24/7, Loker pribadi, ...">{{ old('features') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label for="add_description" class="form-label">Deskripsi (Opsional)</label>
                                <textarea class="form-control" id="add_description" name="description" rows="2">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="add_is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="add_is_active">Aktifkan paket ini</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="add_is_popular" name="is_popular" value="1">
                                    <label class="form-check-label" for="add_is_popular">Tandai sebagai 'Populer'</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Paket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- =================================================================================== -->
    <!--                               MODAL EDIT PAKET                                    -->
    <!-- =================================================================================== -->
    @foreach($packages as $package)
    <div class="modal fade" id="editModal-{{ $package->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $package->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 12px;">
                <form action="{{ route('admin.memberships.update', $package->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="editModalLabel-{{ $package->id }}">Edit Paket Membership</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="form_type" value="edit">
                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_name-{{ $package->id }}" class="form-label">Nama Paket</label>
                                <input type="text" class="form-control" id="edit_name-{{ $package->id }}" name="name" value="{{ old('name', $package->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_type-{{ $package->id }}" class="form-label">Tipe Paket</label>
                                <select class="form-select" id="edit_type-{{ $package->id }}" name="type" required>
                                    @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ old('type', $package->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_price-{{ $package->id }}" class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" id="edit_price-{{ $package->id }}" name="price" value="{{ old('price', $package->price) }}" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_duration_months-{{ $package->id }}" class="form-label">Durasi (Bulan)</label>
                                <input type="number" class="form-control" id="edit_duration_months-{{ $package->id }}" name="duration_months" value="{{ old('duration_months', $package->duration_months) }}" min="1" required>
                            </div>
                            <div class="col-12">
                                <label for="edit_features-{{ $package->id }}" class="form-label">Fitur (Pisahkan dengan koma)</label>
                                {{-- Ubah array 'features' kembali menjadi string --}}
                                <textarea class="form-control" id="edit_features-{{ $package->id }}" name="features" rows="3" required>{{ old('features', is_array($package->features) ? implode(', ', $package->features) : '') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label for="edit_description-{{ $package->id }}" class="form-label">Deskripsi (Opsional)</label>
                                <textarea class="form-control" id="edit_description-{{ $package->id }}" name="description" rows="2">{{ old('description', $package->description) }}</textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="edit_is_active-{{ $package->id }}" name="is_active" value="1" {{ (old('is_active', $package->is_active) == 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_is_active-{{ $package->id }}">Aktifkan paket ini</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="edit_is_popular-{{ $package->id }}" name="is_popular" value="1" {{ (old('is_popular', $package->is_popular) == 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_is_popular-{{ $package->id }}">Tandai sebagai 'Populer'</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Paket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

@endsection

@push('scripts')
<script>
    // Script untuk menangani jika ada error validasi
    document.addEventListener('DOMContentLoaded', function () {
        @if($errors->any())
            var formType = '{{ old('form_type') }}';
            
            // Jika error terjadi saat 'add'
            @if(old('form_type') == 'add')
                var addModal = new bootstrap.Modal(document.getElementById('addPackageModal'));
                addModal.show();
            
            // Jika error terjadi saat 'edit'
            @elseif(old('form_type') == 'edit' && old('package_id'))
                var editModalId = '#editModal-{{ old('package_id') }}';
                var editModal = new bootstrap.Modal(document.querySelector(editModalId));
                editModal.show();
            @endif
        @endif
    });
</script>
@endpush
