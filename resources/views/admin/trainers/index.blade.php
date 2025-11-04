@extends('layouts.admin')

@section('title', 'Data Master - Trainer')

@section('content')
    
    {{-- Memanggil komponen header dan tab navigasi --}}
    @include('admin.components.datamaster-tabs')

    <!-- Sessions Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
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
                        <img src="{{ $trainer->getImageUrl() }}" class="rounded-circle mx-auto" alt="{{ $trainer->name }}" 
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
                            
                            <form action="{{ route('admin.trainers.destroy', $trainer->id) }}" method="POST" class="d-inline-block w-100" onsubmit="return confirm('Anda yakin ingin menghapus trainer ini?');">
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
                <form action="{{ route('admin.trainers.store') }}" method="POST" enctype="multipart/form-data">
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
                                <input type="file" class="form-control" id="add_image" name="image" accept="image/*">
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
                <form id="editForm-{{ $trainer->id }}" action="{{ route('admin.trainers.update', $trainer->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="editModalLabel-{{ $trainer->id }}">Edit Trainer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="form_type" value="edit">
                        <input type="hidden" name="trainer_id" value="{{ $trainer->id }}">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="edit_name-{{ $trainer->id }}" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="edit_name-{{ $trainer->id }}" name="name" value="{{ old('name', $trainer->name) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_specialization-{{ $trainer->id }}" class="form-label">Spesialisasi</label>
                                <input type="text" class="form-control" id="edit_specialization-{{ $trainer->id }}" name="specialization" value="{{ old('specialization', $trainer->specialization) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_email-{{ $trainer->id }}" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email-{{ $trainer->id }}" name="email" value="{{ old('email', $trainer->email) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_phone-{{ $trainer->id }}" class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" id="edit_phone-{{ $trainer->id }}" name="phone" value="{{ old('phone', $trainer->phone) }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_experience-{{ $trainer->id }}" class="form-label">Pengalaman (Tahun)</label>
                                <input type="number" class="form-control" id="edit_experience-{{ $trainer->id }}" name="experience" value="{{ old('experience', $trainer->experience) }}" min="0" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_clients-{{ $trainer->id }}" class="form-label">Jumlah Klien (Opsional)</label>
                                <input type="text" class="form-control" id="edit_clients-{{ $trainer->id }}" name="clients" value="{{ old('clients', $trainer->clients) }}">
                            </div>
                            <div class="col-12">
                                <label for="edit_certifications-{{ $trainer->id }}" class="form-label">Sertifikasi (Pisahkan dengan koma)</label>
                                <input type="text" class="form-control" id="edit_certifications-{{ $trainer->id }}" name="certifications" value="{{ old('certifications', $trainer->certifications ? implode(', ', $trainer->certifications) : '') }}">
                            </div>
                            <div class="col-12">
                                <label for="edit_bio-{{ $trainer->id }}" class="form-label">Bio Singkat</label>
                                <textarea class="form-control" id="edit_bio-{{ $trainer->id }}" name="bio" rows="3">{{ old('bio', $trainer->bio) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label for="edit_image-{{ $trainer->id }}" class="form-label">Ganti Foto Trainer (Opsional)</label>
                                <input type="file" class="form-control" id="edit_image-{{ $trainer->id }}" name="image" accept="image/*">
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="edit_is_active-{{ $trainer->id }}" name="is_active" value="1" {{ (old('is_active', $trainer->is_active) == 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_is_active-{{ $trainer->id }}">Status Aktif</label>
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
    // Script untuk menangani jika ada error validasi
    document.addEventListener('DOMContentLoaded', function () {
        @if($errors->any())
            var formType = '{{ old('form_type') }}';
            
            // Jika error terjadi saat 'add'
            @if(old('form_type') == 'add')
                var addModal = new bootstrap.Modal(document.getElementById('addTrainerModal'));
                addModal.show();
            
            // Jika error terjadi saat 'edit'
            @elseif(old('form_type') == 'edit' && old('trainer_id'))
                var editModalId = '#editModal-{{ old('trainer_id') }}';
                var editModal = new bootstrap.Modal(document.querySelector(editModalId));
                editModal.show();
            @endif
        @endif
    });
</script>
@endpush

