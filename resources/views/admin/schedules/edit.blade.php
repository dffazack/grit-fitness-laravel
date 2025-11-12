@extends('layouts.app')

@section('title', 'Edit Jadwal - Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 style="color: var(--grit-primary);">Edit Jadwal</h1>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali
                </a>
            </div>

            <x-default-card>
                <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                    @csrf
                    @method('PUT') 
                    
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input label="Nama Kelas" name="name" value="{{ old('name', $schedule->name) }}" required />
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label grit-label">Tipe Kelas</label>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Pilih Tipe</option>
                                    @foreach($classTypes as $type)
                                        <option value="{{ $type }}" {{ old('type', $schedule->type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="day" class="form-label grit-label">Hari</label>
                            <select name="day" id="day" class="form-select @error('day') is-invalid @enderror" required>
                                <option value="">Pilih Hari</option>
                                @php $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu']; @endphp
                                @foreach($hari as $h)
                                    <option value="{{ $h }}" {{ old('day', $schedule->day) == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                            @error('day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="trainer_id" class="form-label grit-label">Trainer</label>
                            <select name="trainer_id" id="trainer_id" class="form-select @error('trainer_id') is-invalid @enderror" required>
                                <option value="">Pilih Trainer</option>
                                @foreach($trainers as $trainer)
                                    <option value="{{ $trainer->id }}" {{ old('trainer_id', $schedule->trainer_id) == $trainer->id ? 'selected' : '' }}>
                                        {{ $trainer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('trainer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <x-form-input label="Waktu Mulai" name="start_time" type="time" value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}" required />
                        </div>
                        <div class="col-md-4">
                            <x-form-input label="Waktu Selesai" name="end_time" type="time" value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}" required />
                        </div>
                         <div class="col-md-4">
                            <x-form-input label="Kuota Maksimal" name="max_quota" type="number" value="{{ old('max_quota', $schedule->max_quota) }}" required />
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label grit-label">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $schedule->description) }}</textarea>
                    </div>
                    
                    <div class="text-end">
                        <x-primary-button type="submit" variant="primary">
                            Update Jadwal
                        </x-primary-button>
                    </div>
                </form>
            </x-default-card>
        </div>
    </div>
</div>
@endsection
{{-- Modified by: User-Interfaced Team -- }}
