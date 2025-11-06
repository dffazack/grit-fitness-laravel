{{-- ========================================================================= --}}
{{-- 4. resources/views/admin/schedules/index.blade.php --}}
{{-- ========================================================================= --}}

@extends('layouts.admin')

@section('title', 'Kelola Jadwal')

@section('page-title', 'Kelola Jadwal')
@section('page-subtitle', 'Kelola operasional gym dengan mudah dan efisien')

@section('content')

    {{-- Flash Messages --}}
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
            <strong>Error!</strong> Terdapat kesalahan pada input Anda. Silakan periksa kembali form.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                <h5 class="card-title-custom mb-0">
                    <i class="bi bi-calendar3 me-2 d-none d-sm-inline"></i>
                    Kelola Jadwal Kelas
                </h5>
                <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span class="d-none d-sm-inline">Tambah Jadwal</span>
                    <span class="d-sm-none">Tambah</span>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @if($schedules->isEmpty())
                <div class="text-center p-5">
                    <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                    <p class="text-muted mb-0">Belum ada jadwal yang dibuat.</p>
                </div>
            @else
                {{-- Desktop Table --}}
                <div class="table-responsive d-none d-lg-block">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Nama Kelas</th>
                                <th class="border-0">Hari</th>
                                <th class="border-0">Waktu</th>
                                <th class="border-0">Trainer</th>
                                <th class="border-0">Kuota</th>
                                <th class="border-0 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr>
                                <td>
                                    <strong>{{ $schedule->name }}</strong><br>
                                    <small class="text-muted">{{ $schedule->type }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $schedule->day }}</span></td>
                                <td>
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                </td>
                                <td>{{ $schedule->trainer->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $schedule->bookings_count >= $schedule->max_quota ? 'bg-danger' : 'bg-success' }}">
                                        {{ $schedule->bookings_count }}/{{ $schedule->max_quota }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-primary btn-edit"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editScheduleModal"
                                            data-id="{{ $schedule->id }}"
                                            data-action="{{ route('admin.schedules.update', $schedule->id) }}"
                                            data-schedule="{{ $schedule->toJson() }}"
                                            data-trainer-id="{{ $schedule->trainer_id }}">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus jadwal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile/Tablet Card View --}}
                <div class="d-lg-none p-3">
                    @foreach($schedules as $schedule)
                    <div class="card mb-3 border">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1 fw-semibold">{{ $schedule->name }}</h6>
                                    <small class="text-muted">{{ $schedule->type }}</small>
                                </div>
                                <span class="badge {{ $schedule->bookings_count >= $schedule->max_quota ? 'bg-danger' : 'bg-success' }}">
                                    {{ $schedule->bookings_count }}/{{ $schedule->max_quota }}
                                </span>
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Hari</small>
                                    <span class="badge bg-light text-dark">{{ $schedule->day }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Waktu</small>
                                    <span class="small fw-semibold">{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</span>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted d-block">Trainer</small>
                                    <span class="small fw-semibold">{{ $schedule->trainer->name ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-primary flex-grow-1 btn-edit"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editScheduleModal"
                                        data-id="{{ $schedule->id }}"
                                        data-action="{{ route('admin.schedules.update', $schedule->id) }}"
                                        data-schedule="{{ $schedule->toJson() }}"
                                        data-trainer-id="{{ $schedule->trainer_id }}">
                                    <i class="bi bi-pencil-fill me-1"></i> Edit
                                </button>
                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        @if($schedules->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tambah - Responsive --}}
    <div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Tambah Jadwal Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <form action="{{ route('admin.schedules.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-3 p-md-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="add_name" class="form-label">Nama Kelas</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="add_name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="add_day" class="form-label">Hari</label>
                                <select class="form-select @error('day') is-invalid @enderror" id="add_day" name="day" required>
                                    <option value="" disabled selected>Pilih hari...</option>
                                    @foreach(\App\Models\ClassSchedule::DAYS as $day)
                                        <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                    @endforeach
                                </select>
                                @error('day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="add_type" class="form-label">Tipe Kelas</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="add_type" name="type" required>
                                    <option value="" disabled selected>Pilih tipe...</option>
                                    @foreach(\App\Models\ClassSchedule::CLASS_TYPES as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="add_start_time" class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="add_start_time" name="start_time" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="add_end_time" class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="add_end_time" name="end_time" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="add_trainer_id" class="form-label">Trainer</label>
                                <select class="form-select @error('trainer_id') is-invalid @enderror" id="add_trainer_id" name="trainer_id" required>
                                    <option value="" disabled selected>Pilih trainer...</option>
                                    @foreach($trainers as $trainer)
                                        <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>{{ $trainer->name }}</option>
                                    @endforeach
                                </select>
                                @error('trainer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="add_max_quota" class="form-label">Kuota Maksimal</label>
                                <input type="number" class="form-control @error('max_quota') is-invalid @enderror" id="add_max_quota" name="max_quota" value="{{ old('max_quota') }}" min="1" required>
                                @error('max_quota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="add_description" class="form-label">Deskripsi (Opsional)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="add_description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-accent">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Modal Edit - Responsive --}}
    <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Edit Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <form id="editScheduleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-3 p-md-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="edit_name" class="form-label">Nama Kelas</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="edit_day" class="form-label">Hari</label>
                                <select class="form-select" id="edit_day" name="day" required>
                                    @foreach(\App\Models\ClassSchedule::DAYS as $day)
                                        <option value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="edit_type" class="form-label">Tipe Kelas</label>
                                <select class="form-select" id="edit_type" name="type" required>
                                    @foreach(\App\Models\ClassSchedule::CLASS_TYPES as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="edit_start_time" class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control" id="edit_start_time" name="start_time" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="edit_end_time" class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control" id="edit_end_time" name="end_time" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="edit_trainer_id" class="form-label">Trainer</label>
                                <select class="form-select" id="edit_trainer_id" name="trainer_id" required>
                                    @foreach($trainers as $trainer)
                                        <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="edit_max_quota" class="form-label">Kuota Maksimal</label>
                                <input type="number" class="form-control" id="edit_max_quota" name="max_quota" min="1" required>
                            </div>

                            <div class="col-12">
                                <label for="edit_description" class="form-label">Deskripsi (Opsional)</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-accent">Perbarui Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editScheduleModal = document.getElementById('editScheduleModal');
        
        editScheduleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const action = button.getAttribute('data-action');
            const schedule = JSON.parse(button.getAttribute('data-schedule'));
            const form = document.getElementById('editScheduleForm');
            
            form.action = action;
            form.querySelector('#edit_name').value = schedule.name;
            form.querySelector('#edit_day').value = schedule.day;
            form.querySelector('#edit_type').value = schedule.type;
            form.querySelector('#edit_start_time').value = schedule.start_time ? schedule.start_time.substring(0, 5) : '';
            form.querySelector('#edit_end_time').value = schedule.end_time ? schedule.end_time.substring(0, 5) : '';
            form.querySelector('#edit_trainer_id').value = schedule.trainer_id;
            form.querySelector('#edit_max_quota').value = schedule.max_quota;
            form.querySelector('#edit_description').value = schedule.description || '';
        });

        @if ($errors->any() && old('form_type') === 'add')
            const addModal = new bootstrap.Modal(document.getElementById('addScheduleModal'));
            addModal.show();
        @endif
    });
</script>
@endpush