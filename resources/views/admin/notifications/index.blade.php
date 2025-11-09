@extends('layouts.admin')

@section('title', 'Data Master - Notifikasi')

@section('content')

    {{-- Memanggil komponen header dan tab navigasi --}}
    @include('admin.components.datamaster-tabs')

    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Terdapat kesalahan pada input Anda. Silakan periksa form.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title-custom">Banner Notifikasi Homepage</h5>
            <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Notifikasi
            </button>
        </div>
        <div class="card-body p-0">
            @if($notifications->isEmpty())
                <div class="text-center p-5">
                    <p class="text-muted">Belum ada notifikasi yang dibuat.</p>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                    <div class="list-group-item p-3">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-8">
                                {{-- Tags --}}
                                <div>
                                    <span class="badge rounded-pill" style="background-color: var(--admin-accent); color: white;">
                                        <i class="bi bi-tag-fill me-1"></i> {{ $notification->type }}
                                    </span>
                                    @if($notification->is_active)
                                        <span class="badge rounded-pill bg-active fw-semibold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary text-white fw-semibold">
                                            <i class="bi bi-x-circle-fill me-1"></i> Non-Aktif
                                        </span>
                                    @endif
                                </div>
                                {{-- Content --}}
                                <p class="fw-semibold my-2 mb-1" style="color: var(--admin-primary);">{{ $notification->title }}</p>
                                <p class="text-muted small mb-1">{{ $notification->content }}</p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Periode: {{ $notification->start_date->format('d M Y') }} - {{ $notification->end_date->format('d M Y') }}
                                </p>
                            </div>
                            
                            {{-- Actions --}}
                            <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
                                <!-- Tombol Toggle Status -->
                                <form action="{{ route('admin.notifications.toggle', $notification) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type.submit" class="btn btn-sm btn-outline-warning">
                                        {{ $notification->is_active ? 'Non-aktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                
                                <!-- Tombol Edit -->
                                <button type="button" class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editNotificationModal-{{ $notification->id }}"
                                        data-notification="{{ $notification->toJson() }}">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </button>
                                
                                <!-- Tombol Hapus -->
                                <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus notifikasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>


    <!-- =================================================================================== -->
    <!--                                   MODAL TAMBAH                                    -->
    <!-- =================================================================================== -->
    <div class="modal fade" id="addNotificationModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 12px;">
                <form action="{{ route('admin.notifications.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="addModalLabel">Tambah Notifikasi Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        
                        <x-form-input label="Judul Notifikasi" name="title" placeholder="cth: Promo Spesial Kemerdekaan" required />
                        
                        <div class="mb-3">
                            <label for="add_content" class="form-label">Konten Notifikasi</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="add_content" name="message" rows="3" required>{{ old('content') }}</textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="add_type" class="form-label">Tipe</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="add_type" name="type" required>
                                    <option value="" disabled selected>Pilih tipe...</option>
                                    @foreach(\App\Models\Notification::TYPES as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Awal</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="add_is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="add_is_active">Langsung Aktifkan</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <x-form-input label="Tanggal Mulai" name="start_date" type="date" required />
                            </div>
                            <div class="col-md-6">
                                <x-form-input label="Tanggal Selesai" name="end_date" type="date" required />
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-accent">Tambah Notifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- =================================================================================== -->
    <!--                                   MODAL EDIT                                      -->
    <!-- =================================================================================== -->
    @foreach($notifications as $notification)
    <div class="modal fade" id="editNotificationModal-{{ $notification->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $notification->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 12px;">
                <form action="{{ route('admin.notifications.update', $notification) }}" method="POST" id="editForm-{{ $notification->id }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="editModalLabel-{{ $notification->id }}">Edit Notifikasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        
                        <x-form-input label="Judul Notifikasi" name="title" :value="$notification->title" required />
                        
                        <div class="mb-3">
                            <label for="edit_content-{{ $notification->id }}" class="form-label">Konten Notifikasi</label>
                            <textarea class="form-control" id="edit_content-{{ $notification->id }}" name="message" rows="3" required>{{ old('content', $notification->content) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="edit_type-{{ $notification->id }}" class="form-label">Tipe</label>
                                <select class="form-select" id="edit_type-{{ $notification->id }}" name="type" required>
                                    @foreach(\App\Models\Notification::TYPES as $type)
                                        <option value="{{ $type }}" {{ (old('type', $notification->type) == $type) ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="edit_is_active-{{ $notification->id }}" name="is_active" value="1" {{ $notification->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_is_active-{{ $notification->id }}">Aktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <x-form-input label="Tanggal Mulai" name="start_date" type="date" :value="$notification->start_date->format('Y-m-d')" required />
                            </div>
                            <div class="col-md-6">
                                <x-form-input label="Tanggal Selesai" name="end_date" type="date" :value="$notification->end_date->format('Y-m-d')" required />
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-accent">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

@endsection

@push('scripts')
<script>
    // Logika untuk menampilkan modal yang benar jika ada error validasi
    @if ($errors->any())
        @if (old('form_type') === 'add')
            var addModal = new bootstrap.Modal(document.getElementById('addNotificationModal'));
            addModal.show();
        @elseif (old('form_type') === 'edit')
            var editModal = new bootstrap.Modal(document.getElementById('editNotificationModal-{{ old('notification_id') }}'));
            editModal.show();
        @endif
    @endif
</script>
@endpush