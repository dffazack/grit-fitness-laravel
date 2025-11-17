@extends('layouts.admin')

@section('title', 'Kelola Paket Membership')

@section('content')

    {{-- Menggunakan komponen tab terpusat --}}
    @include('admin.components.datamaster-tabs')

    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center mb-4">
        <h3 class="mb-2 mb-sm-0" style="color: var(--admin-primary);">Kelola Paket Membership</h3>
        <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addPackageModal">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Paket
        </button>
    </div>

        <div class="alert alert-warning d-flex align-items-center mt-4" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>
            Tip: Paket yang ditandai 'Populer' akan ditampilkan dengan *highlight* khusus di halaman utama.
        </div>
        </div>

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


    <div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel" aria-hidden="true">
        {{-- HAPUS CLASS 'modal-dialog-centered' AGAR LEBIH AMAN --}}
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
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
                                <label class="form-label">Fitur Paket</label>
                                <div id="add-features-container">
                                    @if(old('features'))
                                        @foreach(old('features') as $feature)
                                            @if(!empty($feature)) 
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" name="features[]" placeholder="Contoh: Akses gym 24/7" value="{{ $feature }}" required>
                                                <button class="btn btn-outline-danger remove-feature-btn" type="button">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="features[]" placeholder="Contoh: Akses gym 24/7" required>
                                            <button class="btn btn-outline-danger remove-feature-btn" type="button">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success mt-2" id="add-feature-btn">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Fitur
                                </button>
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


    @foreach($packages as $package)
    <div class="modal fade" id="editModal-{{ $package->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $package->id }}" aria-hidden="true">
        {{-- HAPUS CLASS 'modal-dialog-centered' AGAR LEBIH AMAN --}}
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
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
                                <label class="form-label">Fitur Paket</label>
                                <div class="features-container" id="edit-features-container-{{ $package->id }}">
                                    @php
                                        $features = $package->features;
                                        if (old('form_type') == 'edit' && old('package_id') == $package->id) {
                                            $features = old('features', []); 
                                        }
                                    @endphp

                                    @forelse($features as $feature)
                                        @if(!empty($feature))
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="features[]" placeholder="Contoh: Akses gym 24/7" value="{{ $feature }}" required>
                                            <button class="btn btn-outline-danger remove-feature-btn" type="button">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                        @endif
                                    @empty
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="features[]" placeholder="Contoh: Akses gym 24/7" required>
                                            <button class="btn btn-outline-danger remove-feature-btn" type="button">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success mt-2 add-feature-btn-edit" data-container-target="#edit-features-container-{{ $package->id }}">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Fitur
                                </button>
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

@push('styles')
<style>
    /* ===============================================================
      FIX: MODAL FOOTER TENGGELAM DI LAYAR BESAR (FLEXBOX)
      ===============================================================
    */
    
    /* 1. Batasi tinggi KESELURUHAN modal agar tidak "tumpah" */
    .modal-dialog .modal-content {
        max-height: 85vh;  /* 85% tinggi layar */
        display: flex;
        flex-direction: column;
    }

    /* 2. Paksa <form> untuk mengisi sisa ruang */
    .modal-content form {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        min-height: 0; /* Fix bug flexbox */
    }

    /* 3. Kunci ukuran header dan footer agar tidak mengecil */
    .modal-content .modal-header,
    .modal-content .modal-footer {
        flex-shrink: 0; 
    }

    /* 4. Buat HANYA modal-body yang bisa di-scroll */
    .modal-content .modal-body {
        flex-grow: 1;
        overflow-y: auto;
        min-height: 0;
    }
    /* =============================================================== */
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // --- Menampilkan Modal Error Validation ---
    @if($errors->any())
        var formType = '{{ old('form_type') }}';
        
        @if(old('form_type') == 'add')
            var addModal = new bootstrap.Modal(document.getElementById('addPackageModal'));
            addModal.show();
        @elseif(old('form_type') == 'edit' && old('package_id'))
            var editModalId = '#editModal-{{ old('package_id') }}';
            var editModal = new bootstrap.Modal(document.querySelector(editModalId));
            editModal.show();
        @endif
    @endif

    // --- Logika Repeater Input Fitur ---

    const featureInputTemplate = `
    <div class="input-group mb-2">
        <input type="text" class="form-control" name="features[]" placeholder="Fitur baru" required>
        <button class="btn btn-outline-danger remove-feature-btn" type="button">
            <i class="bi bi-trash-fill"></i>
        </button>
    </div>`;
    
    function updateRemoveButtons(container) {
        if (!container) return;
        const inputGroups = container.querySelectorAll('.input-group');
        
        if (inputGroups.length <= 1) {
            inputGroups[0].querySelector('.remove-feature-btn').style.display = 'none';
        } else {
            inputGroups.forEach(group => {
                group.querySelector('.remove-feature-btn').style.display = 'block';
            });
        }
    }

    // Event Delegation
    document.addEventListener('click', function(e) {
        let addBtn = null;
        
        if (e.target.id === 'add-feature-btn') {
            addBtn = e.target;
        } else if (e.target.closest('.add-feature-btn-edit')) {
            addBtn = e.target.closest('.add-feature-btn-edit');
        }

        // Tambah Fitur
        if (addBtn) {
            e.preventDefault(); 
            const targetSelector = addBtn.id === 'add-feature-btn' ? '#add-features-container' : addBtn.getAttribute('data-container-target');
            const container = document.querySelector(targetSelector);
            
            if (container) {
                container.insertAdjacentHTML('beforeend', featureInputTemplate);
                updateRemoveButtons(container); 
            }
        }

        // Hapus Fitur
        if (e.target.classList.contains('remove-feature-btn') || e.target.closest('.remove-feature-btn')) {
            e.preventDefault(); 
            
            const button = e.target.classList.contains('remove-feature-btn') ? e.target : e.target.closest('.remove-feature-btn');
            const inputGroup = button.closest('.input-group');
            const container = inputGroup.parentElement;
            
            if (container.querySelectorAll('.input-group').length > 1) {
                inputGroup.remove();
                updateRemoveButtons(container);
            }
        }
    });

    // Inisialisasi status tombol hapus saat modal dibuka
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const addContainer = modal.querySelector('#add-features-container');
            const editContainers = modal.querySelectorAll('.features-container');
            
            if (addContainer) updateRemoveButtons(addContainer);
            editContainers.forEach(container => updateRemoveButtons(container));
        });
    });

});
</script>
@endpush