@extends('layouts.admin')

@section('title', 'Kelola Jadwal')

@section('content')

    {{-- Header --}}
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 fw-bold" style="color: var(--admin-primary);">Kelola Jadwal</h1>
            <p class="text-muted">Kelola operasional gym dengan mudah dan efisien</p>
        </div>
    </div>
    
    
    <!-- Tampilkan Error Validasi (Penting untuk Modal) -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Terdapat kesalahan pada input Anda. Silakan periksa kembali form.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title-custom">Kelola Jadwal Kelas</h5>
            {{-- Tombol ini memicu Modal 'addScheduleModal' --}}
            <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Jadwal
            </button>
        </div>
        <div class="card-body p-0">
            @if($schedules->isEmpty())
                <div class="text-center p-5">
                    <p class="text-muted">Belum ada jadwal yang dibuat.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama Kelas</th>
                                <th>Hari</th>
                                <th>Waktu</th>
                                <th>Trainer</th>
                                <th>Kuota</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr>
                                <td>
                                    <strong>{{ $schedule->custom_class_name ?? $schedule->classList->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $schedule->type }}</small>
                                </td>
                                <td>{{ $schedule->day }}</td>
                                <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                <td>{{ $schedule->trainer->name ?? 'N/A' }}</td>
                                <td>
                                    {{ $schedule->bookings_count }}/{{ $schedule->max_quota }}
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
                                    
                                    {{-- Tombol Hapus: memanggil route 'admin.schedules.destroy' --}}
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
            @endif
        </div>
        
        @if($schedules->hasPages())
            <div class="card-footer">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>

    
    <!-- =================================================================================== -->
    <!--                               MODAL TAMBAH JADWAL                                   -->
    <!-- =================================================================================== -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="addScheduleModalLabel">Tambah Jadwal Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                {{-- Form ini memanggil route 'admin.schedules.store' --}}
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

                            {{-- Hari --}}
                            <div class="col-md-6">
                                <label for="add_day" class="form-label">Hari</label>
                                <select class="form-select @error('day') is-invalid @enderror" id="add_day" name="day" required>
                                    <option value="" disabled selected>Pilih hari...</option>
                                    @foreach(\App\Models\ClassSchedule::DAYS as $day)
                                        <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                    @endforeach
                                </select>
                                @error('day')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tipe Kelas --}}
                            <div class="col-md-6">
                                <label for="add_type" class="form-label">Tipe Kelas</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="add_type" name="type" required>
                                    <option value="" disabled selected>Pilih tipe...</option>
                                    @foreach(\App\Models\ClassSchedule::CLASS_TYPES as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Waktu Mulai --}}
                            <div class="col-md-6">
                                <label for="add_start_time" class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="add_start_time" name="start_time" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Waktu Selesai --}}
                            <div class="col-md-6">
                                <label for="add_end_time" class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="add_end_time" name="end_time" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Trainer --}}
                            <div class="col-md-6">
                                <label for="add_trainer_id" class="form-label">Trainer</label>
                                <select class="form-select @error('trainer_id') is-invalid @enderror" id="add_trainer_id" name="trainer_id" required>
                                    <option value="" disabled selected>Pilih trainer...</option>
                                    @foreach($trainers as $trainer)
                                        <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>{{ $trainer->name }}</option>
                                    @endforeach
                                </select>
                                @error('trainer_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kuota Maksimal --}}
                            <div class="col-md-6">
                                <label for="add_max_quota" class="form-label">Kuota Maksimal</label>
                                <input type="number" class="form-control @error('max_quota') is-invalid @enderror" id="add_max_quota" name="max_quota" value="{{ old('max_quota') }}" min="1" required>
                                @error('max_quota')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-12">
                                <label for="add_description" class="form-label">Deskripsi (Opsional)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="add_description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
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
    
    
    <!-- =================================================================================== -->
    <!--                                MODAL EDIT JADWAL                                    -->
    <!-- =================================================================================== -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="editScheduleModalLabel">Edit Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                {{-- Form ini action-nya akan di-set oleh JavaScript --}}
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

                            {{-- Hari --}}
                            <div class="col-md-6">
                                <label for="edit_day" class="form-label">Hari</label>
                                <select class="form-select @error('day') is-invalid @enderror" id="edit_day" name="day" required>
                                    @foreach(\App\Models\ClassSchedule::DAYS as $day)
                                        <option value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                                @error('day')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tipe Kelas --}}
                            <div class="col-md-6">
                                <label for="edit_type" class="form-label">Tipe Kelas</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="edit_type" name="type" required>
                                    @foreach(\App\Models\ClassSchedule::CLASS_TYPES as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Waktu Mulai --}}
                            <div class="col-md-6">
                                <label for="edit_start_time" class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="edit_start_time" name="start_time" required>
                                @error('start_time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Waktu Selesai --}}
                            <div class="col-md-6">
                                <label for="edit_end_time" class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="edit_end_time" name="end_time" required>
                                @error('end_time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Trainer --}}
                            <div class="col-md-6">
                                <label for="edit_trainer_id" class="form-label">Trainer</label>
                                <select class="form-select @error('trainer_id') is-invalid @enderror" id="edit_trainer_id" name="trainer_id" required>
                                    @foreach($trainers as $trainer)
                                        <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                                    @endforeach
                                </select>
                                @error('trainer_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kuota Maksimal --}}
                            <div class="col-md-6">
                                <label for="edit_max_quota" class="form-label">Kuota Maksimal</label>
                                <input type="number" class="form-control @error('max_quota') is-invalid @enderror" id="edit_max_quota" name="max_quota" min="1" required>
                                @error('max_quota')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-12">
                                <label for="edit_description" class="form-label">Deskripsi (Opsional)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="edit_description" name="description" rows="3"></textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
        // --- SCRIPT UNTUK MODAL EDIT ---
        const editScheduleModal = document.getElementById('editScheduleModal');
        
        editScheduleModal.addEventListener('show.bs.modal', function (event) {
            // Tombol yang memicu modal
            const button = event.relatedTarget;
            
            // Ambil data dari atribut 'data-*' di tombol
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
            form.querySelector('#edit_description').value = schedule.description;

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
        // Jika ada error validasi di form TAMBAH, buka kembali modal-nya
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