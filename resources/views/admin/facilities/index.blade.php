@extends('layouts.admin')

@section('title', 'Kelola Fasilitas')

@section('content')

    {{-- Menggunakan komponen tab terpusat --}}
    @include('admin.components.datamaster-tabs')

    <!-- Header "Kelola Fasilitas" dan Tombol Tambah -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0" style="color: var(--admin-primary);">Kelola Fasilitas Gym</h3>
        <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Fasilitas
        </button>
    </div>

    <!-- Grid Kartu Fasilitas -->
    <div class="row g-4">
        @forelse($facilities as $facility)
            <div class="col-12 col-lg-6 col-xl-4 d-flex">
                <div class="card flex-fill shadow-sm">
                    @if($facility->getImageUrl())
                        <img src="{{ $facility->getImageUrl() }}" class="card-img-top" alt="{{ $facility->name }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        {{-- Status Badge --}}
                        <div class="mb-3">
                            @if($facility->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </div>
                        
                        {{-- Nama Fasilitas --}}
                        <h4 class="fw-bold" style="color: var(--admin-primary);">{{ $facility->name }}</h4>
                        
                        {{-- Deskripsi sebagai list --}}
                        <ul class="list-unstyled mb-4 mt-2 flex-grow-1">
                            @foreach(explode(',', $facility->description ?? '') as $item)
                                @if(trim($item))
                                <li class="d-flex align-items-start mb-1">
                                    <i class="bi bi-check-circle-fill text-success me-2 mt-1" style="flex-shrink: 0;"></i>
                                    <span>{{ trim($item) }}</span>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    {{-- Tombol Aksi --}}
                    <div class="card-footer bg-white border-0 pt-0 pb-3 px-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editFacilityModal"
                                data-facility='{{ $facility->toJson() }}'
                                data-action="{{ route('admin.facilities.update', $facility->id) }}">
                            <i class="bi bi-pencil-fill"></i> Edit
                        </button>
                        
                        <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus fasilitas ini?');">
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
                        <p class="text-muted">Belum ada fasilitas yang ditambahkan.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($facilities->hasPages())
    <div class="mt-4">
        {{ $facilities->links() }}
    </div>
    @endif


    <!-- =================================================================================== -->
    <!--                               MODAL TAMBAH FASILITAS                              -->
    <!-- =================================================================================== -->
    <div class="modal fade" id="addFacilityModal" tabindex="-1" aria-labelledby="addFacilityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content" style="border-radius: 12px;">
                <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="form_type" value="add">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="addFacilityModalLabel">Tambah Fasilitas Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="add_name" class="form-label">Nama Fasilitas</label>
                                <input type="text" class="form-control" id="add_name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-12">
                                <label for="add_description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="add_description" name="description" rows="3" required>{{ old('description') }}</textarea>
                                <small class="text-muted">Pisahkan setiap item deskripsi dengan koma (,)</small>
                            </div>
                            <div class="col-12">
                                <label for="add_image" class="form-label">Gambar (Opsional)</label>
                                <input class="form-control" type="file" id="add_image" name="image">
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="add_is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="add_is_active">Aktifkan fasilitas ini</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Fasilitas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- =================================================================================== -->
    <!--                               MODAL EDIT FASILITAS                                -->
    <!-- =================================================================================== -->
    <div class="modal fade" id="editFacilityModal" tabindex="-1" aria-labelledby="editFacilityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content" style="border-radius: 12px;">
                <form id="editFacilityForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_type" value="edit">
                    <input type="hidden" id="edit_facility_id" name="facility_id" value="">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="editFacilityModalLabel">Edit Fasilitas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="edit_name" class="form-label">Nama Fasilitas</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                            <div class="col-12">
                                <label for="edit_description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                                <small class="text-muted">Pisahkan setiap item deskripsi dengan koma (,)</small>
                            </div>
                            <div class="col-12">
                                <label for="edit_image" class="form-label">Gambar Baru (Opsional)</label>
                                <input class="form-control" type="file" id="edit_image" name="image">
                                @error('image')
                                    <span class="text-danger small mt-1">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="edit_is_active" name="is_active" value="1">
                                    <label class="form-check-label" for="edit_is_active">Aktifkan fasilitas ini</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Fasilitas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editFacilityModal = document.getElementById('editFacilityModal');
        const editForm = document.getElementById('editFacilityForm');

        // Script untuk mengisi modal edit
        editFacilityModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const facility = JSON.parse(button.getAttribute('data-facility'));
            const action = button.getAttribute('data-action');

            editForm.action = action;

            editForm.querySelector('#edit_facility_id').value = facility.id;
            editForm.querySelector('#edit_name').value = facility.name;
            editForm.querySelector('#edit_description').value = facility.description;
            editForm.querySelector('#edit_is_active').checked = facility.is_active == 1;
        });

        // Script untuk membuka modal jika ada error validasi
        @if($errors->any())
            var formType = '{{ old("form_type") }}';
            
            if (formType === 'add') {
                var addModal = new bootstrap.Modal(document.getElementById('addFacilityModal'));
                addModal.show();
            } else if (formType === 'edit') {
                var editModal = new bootstrap.Modal(document.getElementById('editFacilityModal'));
                const facilityId = '{{ old("facility_id") }}';
                const button = document.querySelector(`.btn-edit[data-facility*'"id":${facilityId}']`);
                
                if(button) {
                    const action = button.getAttribute('data-action');
                    editForm.action = action;
                    
                    editForm.querySelector('#edit_facility_id').value = facilityId;
                    editForm.querySelector('#edit_name').value = '{{ old("name") }}';
                    editForm.querySelector('#edit_description').value = '{{ old("description") }}';
                    editForm.querySelector('#edit_is_active').checked = '{{ old("is_active") }}' == '1';
                    
                    editModal.show();
                }
            }
        @endif
    });
</script>
@endpush