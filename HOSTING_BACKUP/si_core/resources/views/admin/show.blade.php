@extends('layouts.admin')

@section('title', 'Detail Jadwal Kelas')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4">
            <h1 class="h3 mb-2 mb-sm-0 text-gray-800 order-2 order-sm-1">Detail Jadwal Kelas</h1>
            <a href="{{ route('admin.schedules.index') }}"
                class="btn btn-sm btn-secondary shadow-sm order-1 order-sm-2 mb-2 mb-sm-0 align-self-start align-self-sm-center">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>

        <div class="row">
            <!-- Schedule Details Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Informasi Kelas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $schedule->custom_class_name ?? $schedule->classList->name }}
                                </div>
                                <div class="mt-2 text-sm text-gray-600">
                                    <p class="mb-1"><i class="fas fa-calendar-alt mr-2"></i> {{ $schedule->day }}</p>
                                    <p class="mb-1"><i class="fas fa-clock mr-2"></i> {{ $schedule->getFormattedTime() }}
                                    </p>
                                    <p class="mb-1"><i class="fas fa-user-tie mr-2"></i> Trainer:
                                        {{ $schedule->trainer->name }}
                                    </p>
                                    <p class="mb-1"><i class="fas fa-dumbbell mr-2"></i> Tipe: {{ $schedule->type }}</p>
                                    <p class="mb-0"><i class="fas fa-users mr-2"></i> Kuota:
                                        {{ $schedule->bookings->count() }} / {{ $schedule->max_quota }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registered Members List -->
            <div class="col-xl-8 col-md-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Member Terdaftar</h6>
                    </div>
                    <div class="card-body">
                        {{-- Desktop Table View --}}
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>Nama Member</th>
                                        <th>Email</th>
                                        <th>Status Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($schedule->bookings as $index => $booking)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $booking->user->name }}</td>
                                            <td>{{ $booking->user->email }}</td>
                                            <td>
                                                <form action="{{ route('admin.bookings.toggleAttendance', $booking->id) }}"
                                                    method="POST" id="attendance-form-{{ $booking->id }}">
                                                    @csrf
                                                    <div
                                                        class="form-check form-switch d-flex align-items-center justify-content-center">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="attendanceSwitch{{ $booking->id }}"
                                                            onchange="document.getElementById('attendance-form-{{ $booking->id }}').submit()"
                                                            {{ $booking->is_present ? 'checked' : '' }}
                                                            style="cursor: pointer; transform: scale(1.3); margin-top: 0; {{ $booking->is_present ? 'background-color: #1cc88a; border-color: #1cc88a;' : '' }}">
                                                        <label
                                                            class="form-check-label ms-2 {{ $booking->is_present ? 'text-success fw-bold' : 'text-secondary' }}"
                                                            for="attendanceSwitch{{ $booking->id }}" style="cursor: pointer;">
                                                            {{ $booking->is_present ? 'Hadir' : 'Belum Hadir' }}
                                                        </label>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada member yang mendaftar di kelas ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Mobile Card View --}}
                    <div class="d-md-none">
                        @forelse($schedule->bookings as $index => $booking)
                            <div class="card mb-3 border-left-primary shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="font-weight-bold text-gray-800">{{ $booking->user->name }}</div>
                                        <div class="text-xs text-gray-500">#{{ $index + 1 }}</div>
                                    </div>
                                    <div class="text-sm text-gray-600 mb-3">
                                        <i class="fas fa-envelope mr-1"></i> {{ $booking->user->email }}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-xs font-weight-bold text-uppercase text-gray-500">Kehadiran:</span>
                                        <form action="{{ route('admin.bookings.toggleAttendance', $booking->id) }}"
                                            method="POST" id="attendance-form-mobile-{{ $booking->id }}">
                                            @csrf
                                            <div class="form-check form-switch d-flex align-items-center">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="attendanceSwitchMobile{{ $booking->id }}"
                                                    onchange="document.getElementById('attendance-form-mobile-{{ $booking->id }}').submit()"
                                                    {{ $booking->is_present ? 'checked' : '' }}
                                                    style="cursor: pointer; transform: scale(1.3); margin-top: 0; {{ $booking->is_present ? 'background-color: #1cc88a; border-color: #1cc88a;' : '' }}">
                                                <label
                                                    class="form-check-label ms-2 {{ $booking->is_present ? 'text-success fw-bold' : 'text-secondary' }}"
                                                    for="attendanceSwitchMobile{{ $booking->id }}"
                                                    style="cursor: pointer; font-size: 0.9rem;">
                                                    {{ $booking->is_present ? 'Hadir' : 'Belum Hadir' }}
                                                </label>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center p-4 text-muted">
                                <i class="fas fa-users-slash fa-2x mb-2"></i>
                                <p class="mb-0">Belum ada member yang mendaftar.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection