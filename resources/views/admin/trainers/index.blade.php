@extends('layouts.admin')

@section('title', 'Data Master - Trainer')

@section('content')
    
    {{-- Memanggil komponen header dan tab navigasi --}}
    @include('admin.components.datamaster-tabs')


    
    {{-- Menampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validasi Gagal!</strong> Harap periksa input Anda di form.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
            Tambah Trainer
        </button>
    </div>

    <!-- Grid Kartu Trainer -->
    <div class="row g-4">
        @forelse($trainers as $trainer)
            <div class="col-12 col-md-6 col-lg-4">
                {{-- [PERUBAHAN STRUKTUR CARD DIMULAI DI SINI] --}}
                <div class="card h-100">
                    {{-- 
                      1. Hapus <img class="card-img-top"> dari sini.
                      2. Tambahkan 'text-center' ke card-body.
                    --}}
                    <div class="card-body d-flex flex-column text-center">
                        
                        {{-- 3. Tambahkan foto bulat (circular) di dalam card-body --}}
                        <img src="{{ asset('storage/' . $trainer->image) }}" class="rounded-circle mx-auto" alt="{{ $trainer->name }}" 
                             style="width: 180px; height: 180px; object-fit: cover; object-position: center top; margin-bottom: 1rem; border: 4px solid var(--admin-bg);">

                        {{-- 4. Sederhanakan Tampilan Nama dan Badge --}}
                        <h5 class="card-title-custom mb-1">{{ $trainer->name }}</h5>
                        @if($trainer->is_active)
                            <span class="badge rounded-pill bg-active fw-semibold mb-2 align-self-center">Aktif</span>
                        @else
                            <span class="badge rounded-pill bg-danger text-white fw-semibold mb-2 align-self-center">Non-Aktif</span>
                        @endif
                        
                        <p class="mb-1 fw-semibold" style="color: var(--admin-accent);">{{ $trainer->specialization }}</p>
                        
                        {{-- 5. Ubah teks Exp/Clients agar 'text-center' (sudah otomatis) --}}
                        <p class="small text-muted mb-1">
                            Exp: {{ $trainer->experience }} tahun | Clients: {{ $trainer->clients ?? 'N/A' }}
                        </p>
                        
                        {{-- 6. 'flex-grow-1' akan mendorong tombol ke bawah --}}
                        <p class="card-text small text-muted flex-grow-1">{{ Str::limit($trainer->bio, 100) }}</p>
                        
                        {{-- 7. Ubah tombol agar 'justify-content-center' dan 'mt-auto' --}}
                        <div class="d-flex justify-content-center gap-2 mt-auto pt-3">
                            <button type="button" class="btn btn-sm btn-primary w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal-{{ $trainer->id }}"
                                    data-trainer-json="{{ htmlspecialchars(json_encode($trainer), ENT_QUOTES, 'UTF-8') }}">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>
                            
                            <form action="{{ route('admin.masterdata.trainers.destroy', $trainer->id) }}" method="POST" class="d-inline-block w-100" onsubmit="return confirm('Anda yakin ingin menghapus trainer ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100">
                                    <i class="bi bi-trash-fill"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <p class="text-muted">Belum ada data trainer.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    @if($trainers->hasPages())
        <div class="mt-4">
            {{ $trainers->links() }}
        </div>
    @endif


    <!-- =================================================================================== -->
    <!--                               MODAL TAMBAH TRAINER                                -->
    <!-- =================================================================================== -->
    <div class="modal fade" id="addTrainerModal" tabindex="-1" aria-labelledby="addTrainerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 12px;">
                <form action="{{ route('admin.masterdata.trainers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="addTrainerModalLabel">Tambah Trainer Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
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


    <!-- =================================================================================== -->
    <!--                               MODAL EDIT TRAINER                                  -->
    <!-- =================================================================================== -->
    @foreach($trainers as $trainer)
    <div class="modal fade" id="editModal-{{ $trainer->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $trainer->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 12px;">
                <form id="editForm-{{ $trainer->id }}" action="{{ route('admin.masterdata.trainers.update', $trainer->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="editModalLabel-{{ $trainer->id }}">Edit Trainer - {{ $trainer->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
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