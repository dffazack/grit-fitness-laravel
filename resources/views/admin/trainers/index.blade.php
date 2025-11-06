{{-- ========================================================================= --}}
{{-- 5. resources/views/admin/trainers/index.blade.php --}}
{{-- ========================================================================= --}}

@extends('layouts.admin')

@section('title', 'Data Master - Trainer')

@section('content')
    
    @include('admin.components.datamaster-tabs')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Validasi Gagal!</strong> Harap periksa input Anda di form.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
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
                        
                        {{-- Foto Trainer --}}
                        <img src="{{ $trainer->getImageUrl() }}" 
                             class="rounded-circle mx-auto mb-3" 
                             alt="{{ $trainer->name }}" 
                             style="width: 120px; height: 120px; object-fit: cover; object-position: center top; border: 4px solid var(--admin-bg); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

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

    {{-- Modal Edit for each trainer --}}
    @foreach($trainers as $trainer)
    <div class="modal fade" id="editModal-{{ $trainer->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <form action="{{ route('admin.trainers.update', $trainer->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Edit Trainer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3 p-md-4">
                        <input type="hidden" name="form_type" value="edit">
                        <input type="hidden" name="trainer_id" value="{{ $trainer->id }}">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $trainer->name) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Spesialisasi</label>
                                <input type="text" class="form-control" name="specialization" value="{{ old('specialization', $trainer->specialization) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $trainer->email) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" name="phone" value="{{ old('phone', $trainer->phone) }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Pengalaman (Tahun)</label>
                                <input type="number" class="form-control" name="experience" value="{{ old('experience', $trainer->experience) }}" min="0" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Jumlah Klien</label>
                                <input type="text" class="form-control" name="clients" value="{{ old('clients', $trainer->clients) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Sertifikasi</label>
                                <input type="text" class="form-control" name="certifications" value="{{ old('certifications', $trainer->certifications ? implode(', ', $trainer->certifications) : '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Bio Singkat</label>
                                <textarea class="form-control" name="bio" rows="3">{{ old('bio', $trainer->bio) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Ganti Foto Trainer (Opsional)</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" {{ (old('is_active', $trainer->is_active) == 1) ? 'checked' : '' }}>
                                    <label class="form-check-label">Status Aktif</label>
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
        @if($errors->any())
            @if(old('form_type') == 'add')
                var addModal = new bootstrap.Modal(document.getElementById('addTrainerModal'));
                addModal.show();
            @elseif(old('form_type') == 'edit' && old('trainer_id'))
                var editModal = new bootstrap.Modal(document.querySelector('#editModal-{{ old('trainer_id') }}'));
                editModal.show();
            @endif
        @endif
    });
</script>
@endpush