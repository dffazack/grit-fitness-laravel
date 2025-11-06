{{-- ========================================================================= --}}
{{-- 9. resources/views/admin/notifications/index.blade.php - FIXED RESPONSIVE --}}
{{-- ========================================================================= --}}

@extends('layouts.admin')

@section('title', 'Data Master - Notifikasi')

@section('content')

    @include('admin.components.datamaster-tabs')    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error!</strong> Terdapat kesalahan pada input Anda.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                <h5 class="card-title-custom mb-0">
                    <i class="bi bi-bell me-2 d-none d-sm-inline"></i>
                    Banner Notifikasi Homepage
                </h5>
                <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span class="d-none d-sm-inline">Tambah Notifikasi</span>
                    <span class="d-sm-none">Tambah</span>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @if($notifications->isEmpty())
                <div class="text-center p-5">
                    <i class="bi bi-bell-slash fs-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted mb-0">Belum ada notifikasi yang dibuat.</p>
                </div>
            @else
                {{-- Desktop List View (md and up) --}}
                <div class="list-group list-group-flush d-none d-md-block">
                    @foreach($notifications as $notification)
                    <div class="list-group-item p-3 p-lg-4">
                        <div class="row align-items-center g-3">
                            <div class="col-md-8">
                                {{-- Badges --}}
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="badge rounded-pill" style="background-color: var(--admin-accent); color: white;">
                                        <i class="bi bi-tag-fill me-1"></i> {{ $notification->type }}
                                    </span>
                                    @if($notification->is_active)
                                        <span class="badge rounded-pill bg-active">
                                            <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary text-white">
                                            <i class="bi bi-x-circle-fill me-1"></i> Non-Aktif
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- Content --}}
                                <p class="fw-semibold mb-1" style="color: var(--admin-primary);">{{ $notification->title }}</p>
                                <p class="text-muted small mb-2">{{ $notification->content }}</p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Periode: {{ $notification->start_date->format('d M Y') }} - {{ $notification->end_date->format('d M Y') }}
                                </p>
                            </div>
                            
                            {{-- Actions Desktop --}}
                            <div class="col-md-4">
                                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                    {{-- Toggle Status --}}
                                    <form action="{{ route('admin.notifications.toggle', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-toggle-{{ $notification->is_active ? 'on' : 'off' }} me-1 d-none d-lg-inline"></i>
                                            {{ $notification->is_active ? 'Non-aktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    
                                    {{-- Edit --}}
                                    <button type="button" class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editNotificationModal-{{ $notification->id }}">
                                        <i class="bi bi-pencil-fill me-1"></i>
                                        <span class="d-none d-lg-inline">Edit</span>
                                    </button>
                                    
                                    {{-- Delete --}}
                                    <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus notifikasi ini?');">
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
                    @endforeach
                </div>

                {{-- Mobile Card View (below md) --}}
                <div class="d-md-none p-3">
                    @foreach($notifications as $notification)
                    <div class="card mb-3 border">
                        <div class="card-body p-3">
                            {{-- Badges --}}
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge rounded-pill" style="background-color: var(--admin-accent); color: white;">
                                    <i class="bi bi-tag-fill me-1"></i> {{ $notification->type }}
                                </span>
                                @if($notification->is_active)
                                    <span class="badge rounded-pill bg-active">
                                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-secondary text-white">
                                        <i class="bi bi-x-circle-fill me-1"></i> Non-Aktif
                                    </span>
                                @endif
                            </div>
                            
                            {{-- Title & Content --}}
                            <h6 class="fw-semibold mb-2" style="color: var(--admin-primary);">{{ $notification->title }}</h6>
                            <p class="text-muted small mb-3">{{ Str::limit($notification->content, 120) }}</p>
                            
                            {{-- Period --}}
                            <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
                                <i class="bi bi-calendar-event text-muted"></i>
                                <small class="text-muted">
                                    {{ $notification->start_date->format('d M Y') }} - {{ $notification->end_date->format('d M Y') }}
                                </small>
                            </div>

                            {{-- Actions Mobile --}}
                            <div class="d-grid gap-2">
                                {{-- Toggle Status --}}
                                <form action="{{ route('admin.notifications.toggle', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning w-100">
                                        <i class="bi bi-toggle-{{ $notification->is_active ? 'on' : 'off' }} me-1"></i>
                                        {{ $notification->is_active ? 'Non-aktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                
                                {{-- Edit & Delete Row --}}
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-primary flex-grow-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editNotificationModal-{{ $notification->id }}">
                                        <i class="bi bi-pencil-fill me-1"></i> Edit
                                    </button>
                                    
                                    <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus notifikasi ini?');">
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
                    @endforeach
                </div>
            @endif
        </div>
        
        @if($notifications->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tambah - Fully Responsive --}}
    <div class="modal fade" id="addNotificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <form action="{{ route('admin.notifications.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="form_type" value="add">
                    
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Tambah Notifikasi Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body p-3 p-md-4">
                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label">Judul Notifikasi</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   placeholder="cth: Promo Spesial Kemerdekaan" 
                                   required>
                            @error('title') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>
                        
                        {{-- Content --}}
                        <div class="mb-3">
                            <label class="form-label">Konten Notifikasi</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      name="message" 
                                      rows="3" 
                                      placeholder="Tulis konten notifikasi..."
                                      required>{{ old('content') }}</textarea>
                            @error('content') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        <div class="row g-3">
                            {{-- Type --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tipe</label>
                                <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                                    <option value="" disabled selected>Pilih tipe...</option>
                                    @foreach(\App\Models\Notification::TYPES as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>
                            
                            {{-- Active Status --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">Status Awal</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           role="switch" 
                                           name="is_active" 
                                           value="1" 
                                           checked>
                                    <label class="form-check-label">Langsung Aktifkan</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            {{-- Start Date --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       name="start_date" 
                                       value="{{ old('start_date') }}" 
                                       required>
                                @error('start_date') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>
                            
                            {{-- End Date --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       name="end_date" 
                                       value="{{ old('end_date') }}" 
                                       required>
                                @error('end_date') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1 d-none d-sm-inline"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-accent">
                            <i class="bi bi-plus-circle me-1 d-none d-sm-inline"></i>
                            Tambah Notifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Modal Edit - Fully Responsive --}}
    @foreach($notifications as $notification)
    <div class="modal fade" id="editNotificationModal-{{ $notification->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <form action="{{ route('admin.notifications.update', $notification) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_type" value="edit">
                    <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                    
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Edit Notifikasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body p-3 p-md-4">
                        {{-- Title --}}
                        <div class="mb-3">
                            <label class="form-label">Judul Notifikasi</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="title" 
                                   value="{{ old('title', $notification->title) }}" 
                                   required>
                        </div>
                        
                        {{-- Content --}}
                        <div class="mb-3">
                            <label class="form-label">Konten Notifikasi</label>
                            <textarea class="form-control" 
                                      name="message" 
                                      rows="3" 
                                      required>{{ old('content', $notification->content) }}</textarea>
                        </div>

                        <div class="row g-3">
                            {{-- Type --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tipe</label>
                                <select class="form-select" name="type" required>
                                    @foreach(\App\Models\Notification::TYPES as $type)
                                        <option value="{{ $type }}" {{ (old('type', $notification->type) == $type) ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Active Status --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           role="switch" 
                                           name="is_active" 
                                           value="1" 
                                           {{ $notification->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">Aktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            {{-- Start Date --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="start_date" 
                                       value="{{ old('start_date', $notification->start_date->format('Y-m-d')) }}" 
                                       required>
                            </div>
                            
                            {{-- End Date --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="end_date" 
                                       value="{{ old('end_date', $notification->end_date->format('Y-m-d')) }}" 
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1 d-none d-sm-inline"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-accent">
                            <i class="bi bi-save me-1 d-none d-sm-inline"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

@endsection

@push('scripts')
<script>
    // Auto-show modal jika ada validation error
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            @if (old('form_type') === 'add')
                var addModal = new bootstrap.Modal(document.getElementById('addNotificationModal'));
                addModal.show();
            @elseif (old('form_type') === 'edit' && old('notification_id'))
                var editModal = new bootstrap.Modal(document.getElementById('editNotificationModal-{{ old('notification_id') }}'));
                editModal.show();
            @endif
        @endif
    });
</script>
@endpush

@push('styles')
<style>
    /* Responsive adjustments */
    @media (max-width: 767px) {
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
    }
    
    /* Badge wrap */
    .badge {
        white-space: normal;
        word-break: break-word;
    }
</style>
@endpush

@extends('layouts.admin')

@section('title', 'Data Master - Notifikasi')

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
            <strong>Error!</strong> Terdapat kesalahan pada input Anda.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                <h5 class="card-title-custom mb-0">
                    <i class="bi bi-bell me-2 d-none d-sm-inline"></i>
                    Banner Notifikasi Homepage
                </h5>
                <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span class="d-none d-sm-inline">Tambah Notifikasi</span>
                    <span class="d-sm-none">Tambah</span>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @if($notifications->isEmpty())
                <div class="text-center p-5">
                    <i class="bi bi-bell-slash fs-1 text-muted mb-3"></i>
                    <p class="text-muted mb-0">Belum ada notifikasi yang dibuat.</p>
                </div>
            @else
                {{-- Desktop List View --}}
                <div class="list-group list-group-flush d-none d-md-block">
                    @foreach($notifications as $notification)
                    <div class="list-group-item p-3 p-md-4">
                        <div class="row align-items-center g-3">
                            <div class="col-md-8">
                                <div class="d-flex gap-2 mb-2">
                                    <span class="badge rounded-pill" style="background-color: var(--admin-accent); color: white;">
                                        <i class="bi bi-tag-fill me-1"></i> {{ $notification->type }}
                                    </span>
                                    @if($notification->is_active)
                                        <span class="badge rounded-pill bg-active">
                                            <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary text-white">
                                            <i class="bi bi-x-circle-fill me-1"></i> Non-Aktif
                                        </span>
                                    @endif
                                </div>
                                <p class="fw-semibold mb-1" style="color: var(--admin-primary);">{{ $notification->title }}</p>
                                <p class="text-muted small mb-1">{{ $notification->content }}</p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $notification->start_date->format('d M Y') }} - {{ $notification->end_date->format('d M Y') }}
                                </p>
                            </div>
                            
                            <div class="col-md-4 text-md-end">
                                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                    <form action="{{ route('admin.notifications.toggle', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning">
                                            {{ $notification->is_active ? 'Non-aktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editNotificationModal-{{ $notification->id }}">
                                        <i class="bi bi-pencil-fill me-1 d-none d-lg-inline"></i> Edit
                                    </button>
                                    
                                    <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus notifikasi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Mobile Card View --}}
                <div class="d-md-none p-3">
                    @foreach($notifications as $notification)
                    <div class="card mb-3 border">
                        <div class="card-body p-3">
                            <div class="d-flex gap-2 mb-2">
                                <span class="badge rounded-pill" style="background-color: var(--admin-accent); color: white;">
                                    {{ $notification->type }}
                                </span>
                                @if($notification->is_active)
                                    <span class="badge rounded-pill bg-active">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary">Non-Aktif</span>
                                @endif
                            </div>
                            
                            <h6 class="fw-semibold mb-1" style="color: var(--admin-primary);">{{ $notification->title }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($notification->content, 100) }}</p>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $notification->start_date->format('d M Y') }} - {{ $notification->end_date->format('d M Y') }}
                            </p>

                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.notifications.toggle', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning w-100">
                                        {{ $notification->is_active ? 'Non-aktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-primary flex-grow-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editNotificationModal-{{ $notification->id }}">
                                        <i class="bi bi-pencil-fill me-1"></i> Edit
                                    </button>
                                    
                                    <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus notifikasi ini?');">
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
                    @endforeach
                </div>
            @endif
        </div>
        
        @if($notifications->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tambah - Responsive --}}
    <div class="modal fade" id="addNotificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <form action="{{ route('admin.notifications.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Tambah Notifikasi Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3 p-md-4">
                        
                        <div class="mb-3">
                            <label class="form-label">Judul Notifikasi</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="cth: Promo Spesial Kemerdekaan" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Konten Notifikasi</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="3" required>{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tipe</label>
                                <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                                    <option value="" disabled selected>Pilih tipe...</option>
                                    @foreach(\App\Models\Notification::TYPES as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Status Awal</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" checked>
                                    <label class="form-check-label">Langsung Aktifkan</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
    
    {{-- Modal Edit - Responsive --}}
    @foreach($notifications as $notification)
    <div class="modal fade" id="editNotificationModal-{{ $notification->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <form action="{{ route('admin.notifications.update', $notification) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Edit Notifikasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3 p-md-4">
                        
                        <div class="mb-3">
                            <label class="form-label">Judul Notifikasi</label>
                            <input type="text" class="form-control" name="title" value="{{ old('title', $notification->title) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Konten Notifikasi</label>
                            <textarea class="form-control" name="message" rows="3" required>{{ old('message', $notification->content) }}</textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tipe</label>
                                <select class="form-select" name="type" required>
                                    @foreach(\App\Models\Notification::TYPES as $type)
                                        <option value="{{ $type }}" {{ (old('type', $notification->type) == $type) ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" {{ $notification->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">Aktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $notification->start_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $notification->end_date->format('Y-m-d')) }}" required>
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
