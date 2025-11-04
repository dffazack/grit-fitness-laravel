@extends('layouts.app')

@section('title', 'Tambah Jadwal Baru - Admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 style="color: var(--grit-primary);">Tambah Jadwal Baru</h1>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali
                </a>
            </div>

            <x-default-card>
                <form action="{{ route('admin.schedules.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input label="Nama Kelas" name="name" placeholder="cth: Yoga Pagi" required />
                        </div>
                        <div class="col-md-6">
                            {{-- INI YANG DIUBAH --}}
                            <div class="mb-3">
                                <label for="type" class="form-label grit-label">Tipe Kelas</label>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Pilih Tipe</option>
                                    @foreach($classTypes as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
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
                                <option value="Senin" {{ old('day') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ old('day') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ old('day') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ old('day') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ old('day') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                <option value="Sabtu" {{ old('day') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                <option value="Minggu" {{ old('day') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                            </select>
                            @error('day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="trainer_id" class="form-label grit-label">Trainer</label>
                            <select name="trainer_id" id="trainer_id" class="form-select @error('trainer_id') is-invalid @enderror" required>
                                <option value="">Pilih Trainer</option>
                                @foreach($trainers as $trainer)
                                    <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                        {{ $trainer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('trainer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <x-form-input label="Waktu Mulai" name="start_time" type="time" required />
                        </div>
                        <div class="col-md-4">
                            <x-form-input label="Waktu Selesai" name="end_time" type="time" required />
                        </div>
                         <div class="col-md-4">
                            <x-form-input label="Kuota Maksimal" name="max_quota" type="number" placeholder="cth: 20" required />
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label grit-label">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="text-end">
                        <x-primary-button type="submit" variant="primary">
                            Simpan Jadwal
                        </x-primary-button>
                    </div>
                </form>
            </x-default-card>
        </div>
    </div>
</div>
@endsection
