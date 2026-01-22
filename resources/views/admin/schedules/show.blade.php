@extends('layouts.admin')

@section('title', 'Detail Jadwal Kelas')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4">
            <h1 class="h3 mb-2 mb-sm-0 text-gray-800 order-2 order-sm-1">Detail Jadwal Kelas</h1>
            <a href="{{ route('admin.schedules.index') }}"
                class="btn btn-sm btn-secondary shadow-sm order-1 order-sm-2 mb-2 mb-sm-0 align-self-start align-self-sm-center">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="row">
            <!-- Schedule Details Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow h-100 py-2 border-start border-4 border-primary">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Informasi Kelas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800 mb-3">
                                    {{ $schedule->custom_class_name ?? ($schedule->classList->name ?? 'N/A') }}
                                </div>
                                <div class="mt-2 text-sm text-gray-600">
                                    <p class="mb-2"><i class="bi bi-calendar3 me-2 text-primary"></i> {{ $schedule->day }}
                                    </p>
                                    <p class="mb-2"><i class="bi bi-clock me-2 text-primary"></i>
                                        {{ $schedule->start_time->format('H:i') }} -
                                        {{ $schedule->end_time->format('H:i') }}
                                    </p>
                                    <p class="mb-2"><i class="bi bi-person-badge me-2 text-primary"></i> Trainer:
                                        {{ $schedule->trainer->name ?? 'N/A' }}
                                    </p>
                                    <p class="mb-2"><i class="bi bi-tag me-2 text-primary"></i> Tipe: {{ $schedule->type }}
                                    </p>
                                    <p class="mb-0"><i class="bi bi-people me-2 text-primary"></i> Kuota:
                                        {{ $schedule->bookings->count() }} / {{ $schedule->max_quota }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-calendar-check fs-1 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registered Members List -->
            <div class="col-xl-8 col-md-6 mb-4">
                <div class="card shadow mb-4 border-0">
                    <div class="card-header py-3 bg-white border-bottom-0">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Member Terdaftar</h6>
                    </div>
                    <div class="card-body p-0">
                        {{-- Desktop Table View --}}
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover mb-0" id="dataTable" width="100%" cellspacing="0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%" class="ps-4">No</th>
                                        <th>Nama Member</th>
                                        <th>Email</th>
                                        <th>Status Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($schedule->bookings as $index => $booking)
                                        <tr>
                                            <td class="ps-4">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($booking->user->profile_photo_path)
                                                        <img src="{{ asset('storage/' . $booking->user->profile_photo_path) }}"
                                                            alt="" class="rounded-circle me-2"
                                                            style="width: 30px; height: 30px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-light text-secondary"
                                                            style="width: 30px; height: 30px;">
                                                            <i class="bi bi-person-fill small"></i>
                                                        </div>
                                                    @endif
                                                    {{ $booking->user->name }}
                                                </div>
                                            </td>
                                            <td>{{ $booking->user->email }}</td>
                                            <td>
                                                <form action="{{ route('admin.bookings.toggleAttendance', $booking->id) }}"
                                                    method="POST" id="attendance-form-{{ $booking->id }}">
                                                    @csrf
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="attendanceSwitch{{ $booking->id }}"
                                                            onchange="document.getElementById('attendance-form-{{ $booking->id }}').submit()"
                                                            {{ $booking->is_present ? 'checked' : '' }}
                                                            style="cursor: pointer; transform: scale(1.2); {{ $booking->is_present ? 'background-color: #1cc88a; border-color: #1cc88a;' : '' }}">
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
                                            <td colspan="4" class="text-center py-5 text-muted">Belum ada member yang mendaftar
                                                di kelas ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Mobile Card View --}}
                    <div class="d-md-none p-3">
                        @forelse($schedule->bookings as $index => $booking)
                            <div class="card mb-3 border shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="font-weight-bold text-gray-800 d-flex align-items-center">
                                            @if($booking->user->profile_photo_path)
                                                <img src="{{ asset('storage/' . $booking->user->profile_photo_path) }}" alt=""
                                                    class="rounded-circle me-2"
                                                    style="width: 30px; height: 30px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-light text-secondary"
                                                    style="width: 30px; height: 30px;">
                                                    <i class="bi bi-person-fill small"></i>
                                                </div>
                                            @endif
                                            {{ $booking->user->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">#{{ $index + 1 }}</div>
                                    </div>
                                    <div class="text-sm text-gray-600 mb-3 ps-5">
                                        <i class="bi bi-envelope me-1"></i> {{ $booking->user->email }}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center ps-5">
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
                                <i class="bi bi-people fs-1 mb-2"></i>
                                <p class="mb-0">Belum ada member yang mendaftar.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection