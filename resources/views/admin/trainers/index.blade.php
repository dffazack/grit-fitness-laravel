{{-- ========================================================================= --}}
{{-- 5. resources/views/admin/trainers/index.blade.php --}}
{{-- ========================================================================= --}}

@extends('layouts.admin')

@section('title', 'Data Master - Trainer')

@section('content')
    
    @include('admin.components.datamaster-tabs')
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Validasi Gagal!</strong> Harap periksa input Anda di form.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    {{-- Menampilkan error upload ketika handler fallback menggunakan query param --}}
    @if(request()->query('upload_error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Upload Gagal!</strong>
            <p class="mb-0">{{ urldecode(request()->query('upload_error_message')) }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header "Data Trainer" dan Tombol Tambah -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0" style="color: var(--admin-primary);">Data Trainer</h3>
        <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addTrainerModal">
            <i class="bi bi-plus-circle me-1"></i>
            <span class="d-none d-sm-inline">Tambah Trainer</span>
            <span class="d-sm-none">Tambah</span>
        </button>
    </div>

    {{-- Grid Responsive --}}
    <div class="row g-3 g-md-4">
        @forelse($trainers as $trainer)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body d-flex flex-column text-center p-3 p-md-4">
                        {{-- 3. Tambahkan foto bulat (circular) di dalam card-body --}}
                        <img src="{{ asset('storage/' . $trainer->image) }}" class="rounded-circle mx-auto" alt="{{ $trainer->name }}" 
                             style="width: 180px; height: 180px; object-fit: cover; object-position: center top; margin-bottom: 1rem; border: 4px solid var(--admin-bg);">

                        {{-- Nama & Status --}}
                        <h5 class="card-title-custom mb-1 text-truncate" title="{{ $trainer->name }}">{{ $trainer->name }}</h5>
                        @if($trainer->is_active)
                            <span class="badge rounded-pill bg-active mb-2 align-self-center">Aktif</span>
                        @else
                            <span class="badge rounded-pill bg-danger text-white mb-2 align-self-center">Non-Aktif</span>
                        @endif
                        
                        {{-- Spesialisasi --}}
                        <p class="mb-2 fw-semibold text-truncate" style="color: var(--admin-accent);" title="{{ $trainer->specialization }}">
                            {{ $trainer->specialization }}
                        </p>
                        
                        {{-- Exp & Clients --}}
                        <p class="small text-muted mb-3">
                            <i class="bi bi-award me-1"></i>{{ $trainer->experience }} tahun 
                            <span class="mx-1">|</span>
                            <i class="bi bi-people me-1"></i>{{ $trainer->clients ?? 'N/A' }}
                        </p>
                        
                        {{-- Bio --}}
                        <p class="card-text small text-muted flex-grow-1 mb-3" style="line-height: 1.6;">
                            {{ Str::limit($trainer->bio, 80) }}
                        </p>
                        
                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-center gap-2 mt-auto">
                            <button type="button" class="btn btn-sm btn-primary flex-grow-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal-{{ $trainer->id }}">
                                <i class="bi bi-pencil-fill me-1"></i>
                                <span class="d-none d-sm-inline">Edit</span>
                            </button>
                            
                            <form action="{{ route('admin.trainers.destroy', $trainer->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Anda yakin ingin menghapus trainer ini?');">
                                @csrf
                                @method('DELETE')
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

    @if($trainers->hasPages())
        <div class="mt-4">
            {{ $trainers->links() }}
        </div>
    @endif

    {{-- Modal Tambah - Responsive --}}
    <div class="modal fade" id="addTrainerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <form action="{{ route('admin.trainers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Tambah Trainer Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3 p-md-4">
                        <input type="hidden" name="form_type" value="add">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="add_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="add_name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_specialization" class="form-label">Spesialisasi</label>
                                <input type="text" class="form-control" id="add_specialization" name="specialization" value="{{ old('specialization') }}" placeholder="Contoh: Yoga & Flexibility" required>
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
                                @php
                                $isInvalid = $errors->has('image') && old('form_type') == 'add';
                                @endphp
                                <input type="file" 
                                    class="form-control {{ $isInvalid ? 'is-invalid' : '' }} image-upload-input" 
                                    id="add_image" 
                                    name="image" 
                                        accept=".png,.jpg">
                                    <small class="form-text text-muted">Hanya file PNG atau JPG yang diizinkan. Maksimal 2MB.</small>

                                @if($isInvalid)
                                    <div class="invalid-feedback">
                                    {{ $errors->first('image') }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="add_is_active" name="is_active" value="1" checked>
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

    {{-- Modal Edit for each trainer --}}
    @foreach($trainers as $trainer)
    <div class="modal fade" id="editModal-{{ $trainer->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <form action="{{ route('admin.trainers.update', $trainer->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="editModalLabel-{{ $trainer->id }}">Edit Trainer - {{ $trainer->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3 p-md-4">
                        <input type="hidden" name="form_type" value="edit">
                        <input type="hidden" name="trainer_id" value="{{ $trainer->id }}">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">

                                <label for="edit_name_{{ $trainer->id }}" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="edit_name_{{ $trainer->id }}" name="name" value="{{ old('name', $trainer->name) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_specialization_{{ $trainer->id }}" class="form-label">Spesialisasi</label>
                                <input type="text" class="form-control" id="edit_specialization_{{ $trainer->id }}" name="specialization" value="{{ old('specialization', $trainer->specialization) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_email_{{ $trainer->id }}" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email_{{ $trainer->id }}" name="email" value="{{ old('email', $trainer->email) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_phone_{{ $trainer->id }}" class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" id="edit_phone_{{ $trainer->id }}" name="phone" value="{{ old('phone', $trainer->phone) }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_experience_{{ $trainer->id }}" class="form-label">Pengalaman (Tahun)</label>
                                <input type="number" class="form-control" id="edit_experience_{{ $trainer->id }}" name="experience" value="{{ old('experience', $trainer->experience) }}" min="0" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_clients_{{ $trainer->id }}" class="form-label">Jumlah Klien (Opsional)</label>
                                <input type="text" class="form-control" id="edit_clients_{{ $trainer->id }}" name="clients" value="{{ old('clients', $trainer->clients) }}" placeholder="Contoh: 150+">
                            </div>
                            <div class="col-12">
                                <label for="edit_certifications_{{ $trainer->id }}" class="form-label">Sertifikasi (Pisahkan dengan koma)</label>
                                <input type="text" class="form-control" id="edit_certifications_{{ $trainer->id }}" name="certifications" value="{{ old('certifications', $trainer->certifications ? implode(', ', $trainer->certifications) : '') }}" placeholder="Contoh: ACE, RYT-200">
                            </div>
                            <div class="col-12">
                                <label for="edit_bio_{{ $trainer->id }}" class="form-label">Bio Singkat</label>
                                <textarea class="form-control" id="edit_bio_{{ $trainer->id }}" name="bio" rows="3">{{ old('bio', $trainer->bio) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label for="edit_image_{{ $trainer->id }}" class="form-label">Ganti Foto Trainer (Opsional)</label>
                                @php
                                    $isInvalidEdit = $errors->has('image') && old('form_type') == 'edit' && old('trainer_id') == $trainer->id;
                                @endphp
                                <input type="file" class="form-control {{ $isInvalidEdit ? 'is-invalid' : '' }} image-upload-input" id="edit_image_{{ $trainer->id }}" name="image" accept=".png,.jpg">
                                @if($isInvalidEdit)
                                    <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="edit_is_active_{{ $trainer->id }}" name="is_active" value="1" {{ (old('is_active', $trainer->is_active) == 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_is_active_{{ $trainer->id }}">Status Aktif</label>
                                </div>
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

@push('styles')
<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
<<<<<<< HEAD
        const handleFileValidation = (event) => {
            const fileInput = event.target;
            const form = fileInput.closest('form');
            const submitButton = form.querySelector('button[type="submit"]');
            const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            
            // Clear previous custom error
            const existingError = form.querySelector('.custom-file-error');
            if (existingError) {
                existingError.remove();
            }
            fileInput.classList.remove('is-invalid');
            if (submitButton) {
                submitButton.disabled = false;
            }

            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const fileName = file.name;

                if (!allowedExtensions.exec(fileName)) {
                    fileInput.value = ''; // Clear the invalid file selection
                    fileInput.classList.add('is-invalid');
                    
                    const errorFeedback = document.createElement('div');
                    errorFeedback.classList.add('invalid-feedback', 'd-block', 'custom-file-error');
                    errorFeedback.textContent = 'Format file harus PNG, JPG, atau JPEG.';
                    
                    fileInput.parentNode.insertBefore(errorFeedback, fileInput.nextSibling);
                    
                    if (submitButton) {
                        submitButton.disabled = true;
                    }
                }
            }
        };

        const imageUploadInputs = document.querySelectorAll('.image-upload-input');
        imageUploadInputs.forEach(input => {
            input.addEventListener('change', handleFileValidation);
        });

        // Also reset validation state when modal is closed
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                if (form) {
                    const fileInput = form.querySelector('.image-upload-input');
                    const submitButton = form.querySelector('button[type="submit"]');
                    
                    if (fileInput) {
                        fileInput.classList.remove('is-invalid');
                        const existingError = form.querySelector('.custom-file-error');
                        if (existingError) {
                            existingError.remove();
                        }
                    }
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                }
            });
        });
    });
</script>
@endpush