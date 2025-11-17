{{-- ========================================================================= --}}
{{-- 4. resources/views/admin/schedules/index.blade.php --}}
{{-- ========================================================================= --}}

@extends('layouts.admin')

@section('title', 'Kelola Jadwal')

@section('page-title', 'Kelola Jadwal')
@section('page-subtitle', 'Kelola operasional gym dengan mudah dan efisien')

@section('content')

    {{-- Header --}}
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 fw-bold" style="color: var(--admin-primary);">Kelola Jadwal</h1>
            <p class="text-muted">Kelola operasional gym dengan mudah dan efisien</p>
        </div>
    </div>
    
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
                                    <strong>{{ $schedule->custom_class_name ?? $schedule->classList->name ?? 'N/A' }}</strong><br>
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
                                    {{-- Tombol Edit: Memicu Modal 'editScheduleModal' --}}
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
                                    <h6 class="mb-1 fw-semibold">{{ $schedule->custom_class_name ?? $schedule->classList->name ?? 'N/A' }}</h6>
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
                    <input type="hidden" name="form_type" value="add">
                    <div class="modal-body p-4">
                        {{-- Tambahkan ini untuk debug error spesifik --}}
                        @if ($errors->any())
                            @dump($errors->all())
                        @endif
                        <div class="row g-3">
                            {{-- Nama Kelas (Select for existing, input for 'Other') --}}
                            <div class="col-12">
                                <label for="add_class_list_id" class="form-label">Nama Kelas</label>
                                <select class="form-select @error('class_list_id') is-invalid @enderror" id="add_class_list_id" name="class_list_id" required>
                                    <option value="" disabled selected>Pilih nama kelas...</option>
                                    @foreach($classLists as $classList)
                                        <option value="{{ $classList->id }}" {{ old('class_list_id') == $classList->id ? 'selected' : '' }}>{{ $classList->name }}</option>
                                    @endforeach
                                    <option value="other" {{ old('class_list_id') == 'other' ? 'selected' : '' }}>Other (Specify New Class)</option>
                                </select>
                                @error('class_list_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Custom Class Name Input (hidden by default) --}}
                            <div class="col-12" id="add_custom_class_name_group" style="display: none;">
                                <label for="add_custom_class_name" class="form-label">Nama Kelas Baru</label>
                                <input type="text" class="form-control @error('custom_class_name') is-invalid @enderror" id="add_custom_class_name" name="custom_class_name" value="{{ old('custom_class_name') }}" placeholder="Enter new class name">
                                @error('custom_class_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
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
                    <input type="hidden" name="form_type" value="edit">
                    <input type="hidden" name="schedule_id" id="edit_schedule_id">
                    <div class="modal-body p-4">
                        {{-- Tambahkan ini untuk debug error spesifik --}}
                        @if ($errors->any())
                            @dump($errors->all())
                        @endif
                        <div class="row g-3">
                            {{-- Nama Kelas (Select for existing, input for 'Other') --}}
                            <div class="col-12">
                                <label for="edit_class_list_id" class="form-label">Nama Kelas</label>
                                <select class="form-select @error('class_list_id') is-invalid @enderror" id="edit_class_list_id" name="class_list_id" required>
                                    <option value="" disabled selected>Pilih nama kelas...</option>
                                    @foreach($classLists as $classList)
                                        <option value="{{ $classList->id }}">{{ $classList->name }}</option>
                                    @endforeach
                                    <option value="other">Other (Specify New Class)</option>
                                </select>
                                @error('class_list_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Custom Class Name Input (hidden by default) --}}
                            <div class="col-12" id="edit_custom_class_name_group" style="display: none;">
                                <label for="edit_custom_class_name" class="form-label">Nama Kelas Baru</label>
                                <input type="text" class="form-control @error('custom_class_name') is-invalid @enderror" id="edit_custom_class_name" name="custom_class_name" placeholder="Enter new class name">
                                @error('custom_class_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
            const scheduleId = button.getAttribute('data-id');

            // Ambil form di dalam modal
            const form = document.getElementById('editScheduleForm');
            
            // Set URL action dan schedule_id untuk form
            form.action = action;
            form.querySelector('#edit_schedule_id').value = scheduleId;
            
            // Isi semua field di form dengan data schedule
            form.querySelector('#edit_class_list_id').value = schedule.class_list_id || '';  
            form.querySelector('#edit_day').value = schedule.day;
            form.querySelector('#edit_type').value = schedule.type;
            form.querySelector('#edit_start_time').value = schedule.start_time ? schedule.start_time.substring(0, 5) : '';
            form.querySelector('#edit_end_time').value = schedule.end_time ? schedule.end_time.substring(0, 5) : '';
            form.querySelector('#edit_trainer_id').value = schedule.trainer_id;
            form.querySelector('#edit_max_quota').value = schedule.max_quota;
            form.querySelector('#edit_description').value = schedule.description || '';

            // Handle 'Other' option for edit modal
            const editClassListSelect = form.querySelector('#edit_class_list_id');
            const editCustomClassNameGroup = form.querySelector('#edit_custom_class_name_group');
            const editCustomClassNameInput = form.querySelector('#edit_custom_class_name');

            let isPredefined = false;
            for (let i = 0; i < editClassListSelect.options.length; i++) {
                if (editClassListSelect.options[i].value == schedule.class_list_id && editClassListSelect.options[i].value !== 'other') {
                    isPredefined = true;
                    break;
                }
            }
            
            if (!isPredefined || schedule.class_list_id === null) {  // Jika custom (class_list_id null)
                editClassListSelect.value = 'other';
                editCustomClassNameGroup.style.display = 'block';
                editCustomClassNameInput.value = schedule.custom_class_name || '';  // Gunakan custom_class_name
                editCustomClassNameInput.setAttribute('required', 'required');
            } else {
                editClassListSelect.value = schedule.class_list_id;
                editCustomClassNameGroup.style.display = 'none';
                editCustomClassNameInput.removeAttribute('required');
                editCustomClassNameInput.value = '';
            }

            editClassListSelect.dispatchEvent(new Event('change')); // Trigger change to apply logic
        });

        // --- SCRIPT UNTUK MENANGANI ERROR VALIDASI DAN AUTO-OPEN MODAL ---
        @if ($errors->any() && old('form_type') === 'add')
            const addModal = new bootstrap.Modal(document.getElementById('addScheduleModal'));
            addModal.show();
        @endif
        
        // Jika ada error validasi di form EDIT, buka kembali modal-nya
        @if ($errors->any() && old('form_type') === 'edit' && old('schedule_id'))
            // Dapatkan tombol edit yang sesuai
            const editButton = document.querySelector(`.btn-edit[data-id="${{ old('schedule_id') }}"]`);
            if(editButton) {
                // Tampilkan modal edit
                const editModal = new bootstrap.Modal(document.getElementById('editScheduleModal'));
                
                // Set action dan schedule_id
                const form = document.getElementById('editScheduleForm');
                form.action = editButton.getAttribute('data-action');
                form.querySelector('#edit_schedule_id').value = "{{ old('schedule_id') }}";

                // Isi field dengan old data
                form.querySelector('#edit_class_list_id').value = "{{ old('class_list_id') }}";
                form.querySelector('#edit_day').value = "{{ old('day') }}";
                form.querySelector('#edit_type').value = "{{ old('type') }}";
                form.querySelector('#edit_start_time').value = "{{ old('start_time') }}";
                form.querySelector('#edit_end_time').value = "{{ old('end_time') }}";
                form.querySelector('#edit_trainer_id').value = "{{ old('trainer_id') }}";
                form.querySelector('#edit_max_quota').value = "{{ old('max_quota') }}";
                form.querySelector('#edit_description').value = "{{ old('description') }}";

                // Handle 'Other' option for old input
                const editClassListSelect = form.querySelector('#edit_class_list_id');
                const editCustomClassNameGroup = form.querySelector('#edit_custom_class_name_group');
                const editCustomClassNameInput = form.querySelector('#edit_custom_class_name');

                if ("{{ old('class_list_id') }}" === 'other') {
                    editCustomClassNameGroup.style.display = 'block';
                    editCustomClassNameInput.value = "{{ old('custom_class_name') }}";
                    editCustomClassNameInput.setAttribute('required', 'required');
                } else {
                    editCustomClassNameGroup.style.display = 'none';
                    editCustomClassNameInput.removeAttribute('required');
                    editCustomClassNameInput.value = '';
                }

                editModal.show();
            }
        @endif

        // Auto-open modal berdasarkan flash data dari controller
        @if (session('modal') === 'add')
            const addModal = new bootstrap.Modal(document.getElementById('addScheduleModal'));
            addModal.show();
        @elseif (session('modal') === 'edit' && session('edit_schedule_id'))
            const editButton = document.querySelector(`.btn-edit[data-id="${{ session('edit_schedule_id') }}"]`);
            if (editButton) {
                editButton.click(); // Simulasi klik tombol edit untuk buka modal
            }
        @endif

        // --- JAVASCRIPT UNTUK MENANGANI PILIHAN 'OTHER' PADA NAMA KELAS ---
        function setupOtherOptionLogic(selectId, inputGroupId, inputId) {
            const selectElement = document.getElementById(selectId);
            const inputGroupElement = document.getElementById(inputGroupId);
            const inputElement = document.getElementById(inputId);

            if (!selectElement || !inputGroupElement || !inputElement) {
                console.warn(`Elements not found for setupOtherOptionLogic: ${selectId}, ${inputGroupId}, ${inputId}`);
                return;
            }

            function toggleInput() {
                if (selectElement.value === 'other') {
                    inputGroupElement.style.display = 'block';
                    inputElement.setAttribute('required', 'required');
                } else {
                    inputGroupElement.style.display = 'none';
                    inputElement.removeAttribute('required');
                    inputElement.value = ''; // Clear value when hidden
                }
            }

            selectElement.addEventListener('change', toggleInput);

            // Initial check in case 'other' was pre-selected (e.g., due to old input)
            toggleInput();
        }

        // Setup for Add Schedule Modal
        setupOtherOptionLogic('add_class_list_id', 'add_custom_class_name_group', 'add_custom_class_name');

        // Setup for Edit Schedule Modal
        setupOtherOptionLogic('edit_class_list_id', 'edit_custom_class_name_group', 'edit_custom_class_name');
    });
</script>
@endpush