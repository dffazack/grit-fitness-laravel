{{-- resources/views/admin/trainers/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Data Master - Trainer')

@section('content')

    @include('admin.components.datamaster-tabs')

    {{-- Success Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Validasi Gagal!</strong> Harap periksa input Anda.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Upload Error via Query String --}}
    @if(request()->query('upload_error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Upload Gagal!</strong>
            <p class="mb-0">{{ urldecode(request()->query('upload_error_message')) }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header + Tombol Tambah --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <h3 class="mb-0" style="color: var(--admin-primary);">Data Trainer</h3>
        <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addTrainerModal">
            <i class="bi bi-plus-circle me-1"></i>
            <span class="d-none d-sm-inline">Tambah Trainer</span>
            <span class="d-sm-none">Tambah</span>
        </button>
    </div>

    {{-- Grid Trainer Cards --}}
    <div class="row g-3 g-md-4">
        @forelse($trainers as $trainer)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body d-flex flex-column text-center p-3 p-md-4">
                        
                        {{-- Foto Trainer --}}
                        <img src="{{ asset('storage/' . $trainer->image) }}" 
                             class="rounded-circle mx-auto mb-3" 
                             alt="{{ $trainer->name }}" 
                             style="width: 120px; height: 120px; object-fit: cover; object-position: center top; border: 4px solid var(--admin-bg); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

                        <h5 class="card-title-custom mb-1 text-truncate" title="{{ $trainer->name }}">{{ $trainer->name }}</h5>

                        @if($trainer->is_active)
                            <span class="badge rounded-pill bg-success mb-2 align-self-center">Aktif</span>
                        @else
                            <span class="badge rounded-pill bg-danger text-white mb-2 align-self-center">Non-Aktif</span>
                        @endif

                        <p class="mb-2 fw-semibold text-truncate" style="color:var(--admin-accent);" title="{{ $trainer->specialization }}">
                            {{ $trainer->specialization }}
                        </p>

                        <p class="small text-muted mb-3">
                            <i class="bi bi-award me-1"></i>{{ $trainer->experience }} tahun 
                            <span class="mx-1">|</span>
                            <i class="bi bi-people me-1"></i>{{ $trainer->clients ?? 'N/A' }}
                        </p>

                        <p class="card-text small text-muted flex-grow-1 mb-3" style="line-height:1.6;">
                            {{ Str::limit($trainer->bio, 80) }}
                        </p>

                        <div class="d-flex justify-content-center gap-2 mt-auto">
                            <button type="button" class="btn btn-sm btn-primary flex-grow-1"
                                    data-bs-toggle="modal" data-bs-target="#editModal-{{ $trainer->id }}">
                                <i class="bi bi-pencil-fill me-1"></i>
                                <span class="d-none d-sm-inline">Edit</span>
                            </button>

                            <form action="{{ route('admin.trainers.destroy', $trainer->id) }}" method="POST"
                                  onsubmit="return confirm('Anda yakin ingin menghapus trainer ini?');" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-person-x fs-1 text-muted mb-3"></i>
                        <p class="text-muted mb-0">Belum ada data trainer.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($trainers->hasPages())
        <div class="mt-4">
            {{ $trainers->links() }}
        </div>
    @endif


    {{-- ==================== MODAL TAMBAH TRAINER ==================== --}}
    <div class="modal fade" id="addTrainerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
                <form action="{{ route('admin.trainers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="form_type" value="add">

                    <div class="modal-header border-0">
                        <h5 class="modal-title">Tambah Trainer Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-3 p-md-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="add_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="add_name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_specialization" class="form-label">Spesialisasi</label>
                                <input type="text" class="form-control" id="add_specialization" name="specialization" value="{{ old('specialization') }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="add_email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_phone" class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" id="add_phone" name="phone" value="{{ old('phone') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_experience" class="form-label">Pengalaman (Tahun)</label>
                                <input type="number" class="form-control" id="add_experience" name="experience" value="{{ old('experience') }}" min="0" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_clients" class="form-label">Jumlah Klien (Opsional)</label>
                                <input type="text" class="form-control" id="add_clients" name="clients" value="{{ old('clients') }}" placeholder="Contoh: 150+">
                            </div>
                            <div class="col-12">
                                <label for="add_certifications" class="form-label">Sertifikasi (Pisahkan dengan koma)</label>
                                <input type="text" class="form-control" id="add_certifications" name="certifications" value="{{ old('certifications') }}" placeholder="Contoh: ACE, RYT-200">
                            </div>
                            <div class="col-12">
                                <label for="add_bio" class="form-label">Bio Singkat</label>
                                <textarea class="form-control" id="add_bio" name="bio" rows="3">{{ old('bio') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label for="add_image" class="form-label">Foto Trainer</label>
                                @php $isInvalid = $errors->has('image') && old('form_type') == 'add'; @endphp
                                <input type="file" class="form-control {{ $isInvalid ? 'is-invalid' : '' }} image-upload-input" id="add_image" name="image" accept=".png,.jpg,.jpeg">
                                <small class="form-text text-muted">Hanya PNG/JPG/JPEG, maksimal 2MB.</small>
                                @if($isInvalid)<div class="invalid-feedback">{{ $errors->first('image') }}</div>@endif
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="add_is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="add_is_active">Status Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Trainer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ==================== MODAL EDIT TRAINER (per trainer) ==================== --}}
    @foreach($trainers as $trainer)
        <div class="modal fade" id="editModal-{{ $trainer->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
                    <form action="{{ route('admin.trainers.update', $trainer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <input type="hidden" name="form_type" value="edit">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="edit_name_{{ $trainer->id }}" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="edit_name_{{ $trainer->id }}" name="name" value="{{ old('name', $trainer->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_specialization_{{ $trainer->id }}" class="form-label">Spesialisasi</label>
                                <input type="text" class="form-control @error('specialization') is-invalid @enderror" id="edit_specialization_{{ $trainer->id }}" name="specialization" value="{{ old('specialization', $trainer->specialization) }}" required>
                                @error('specialization')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_email_{{ $trainer->id }}" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="edit_email_{{ $trainer->id }}" name="email" value="{{ old('email', $trainer->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_phone_{{ $trainer->id }}" class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="edit_phone_{{ $trainer->id }}" name="phone" value="{{ old('phone', $trainer->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_experience_{{ $trainer->id }}" class="form-label">Pengalaman (Tahun)</label>
                                <input type="number" class="form-control @error('experience') is-invalid @enderror" id="edit_experience_{{ $trainer->id }}" name="experience" value="{{ old('experience', $trainer->experience) }}" min="0" required>
                                @error('experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_clients_{{ $trainer->id }}" class="form-label">Jumlah Klien (Opsional)</label>
                                <input type="text" class="form-control @error('clients') is-invalid @enderror" id="edit_clients_{{ $trainer->id }}" name="clients" value="{{ old('clients', $trainer->clients) }}" placeholder="Contoh: 150+">
                                @error('clients')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="edit_bio_{{ $trainer->id }}" class="form-label">Bio Singkat</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" id="edit_bio_{{ $trainer->id }}" name="bio" rows="3">{{ old('bio', $trainer->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="edit_image_{{ $trainer->id }}" class="form-label">Ganti Foto Trainer (Opsional)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror image-upload-input" id="edit_image_{{ $trainer->id }}" name="image" accept=".png,.jpg">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="edit_is_active_{{ $trainer->id }}" name="is_active" value="1" {{ (old('is_active', $trainer->is_active) == 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_is_active_{{ $trainer->id }}">Status Aktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update Trainer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection


{{-- ====================== STYLES ====================== --}}
@push('styles')
<style>
    .hover-card{
        transition:transform .3s ease,box-shadow .3s ease;
    }
    .hover-card:hover{
        transform:translateY(-5px);
        box-shadow:0 8px 24px rgba(0,0,0,.15)!important;
    }

    /* Fix modal footer tidak terlihat di layar kecil */
    .modal-dialog .modal-content{
        max-height:90vh;
        display:flex;
        flex-direction:column;
    }
    .modal-content form{
        display:flex;
        flex-direction:column;
        flex-grow:1;
        min-height:0;
    }
    .modal-header,.modal-footer{flex-shrink:0;}
    .modal-body{
        flex-grow:1;
        overflow-y:auto;
        min-height:0;
    }
</style>
@endpush


{{-- ====================== SCRIPTS ====================== --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const handleFileValidation = (event) => {
        const fileInput = event.target;
        const form = fileInput.closest('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        const allowed = /(\.jpg|\.jpeg|\.png)$/i;

        // Reset
        const prevError = form.querySelector('.custom-file-error');
        if (prevError) prevError.remove();
        fileInput.classList.remove('is-invalid');
        if (submitBtn) submitBtn.disabled = false;

        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if (!allowed.exec(file.name)) {
                fileInput.value = '';
                fileInput.classList.add('is-invalid');
                const err = document.createElement('div');
                err.className = 'invalid-feedback d-block custom-file-error';
                err.textContent = 'Format file harus PNG, JPG, atau JPEG.';
                fileInput.parentNode.insertBefore(err, fileInput.nextSibling);
                if (submitBtn) submitBtn.disabled = true;
            }
        }
    };

    document.querySelectorAll('.image-upload-input').forEach(input => {
        input.addEventListener('change', handleFileValidation);
    });

    // Reset ketika modal ditutup
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            const form = this.querySelector('form');
            if (!form) return;
            const fileInput = form.querySelector('.image-upload-input');
            const submitBtn = form.querySelector('button[type="submit"]');
            const err = form.querySelector('.custom-file-error');
            if (fileInput) fileInput.classList.remove('is-invalid');
            if (err) err.remove();
            if (submitBtn) submitBtn.disabled = false;
        });
    });

    // Auto-open modal jika ada error validasi
    @if($errors->any())
        @if(old('form_type') === 'add')
            new bootstrap.Modal(document.getElementById('addTrainerModal')).show();
        @elseif(old('form_type') === 'edit' && old('trainer_id'))
            new bootstrap.Modal(document.getElementById('editModal-{{ old('trainer_id') }}')).show();
        @endif
    @endif
});
</script>
@endpush
