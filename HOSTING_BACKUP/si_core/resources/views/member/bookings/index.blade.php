@extends('layouts.app')

@section('title', 'Booking Saya')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="text-center mb-5">
        <h1 class="text-primary fw-bold">Booking Kelas Saya</h1>
        <p class="text-muted">Kelola kelas yang sudah Anda booking.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Jadwal</th>
                            <th>Trainer</th>
                            <th>Tanggal Booking</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <strong>{{ $booking->schedule->custom_class_name ?? $booking->schedule->classList->name ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    @if($booking->schedule)
                                        {{ $booking->schedule->day }}, {{ $booking->schedule->start_time->format('H:i') }} - {{ $booking->schedule->end_time->format('H:i') }}
                                    @else
                                        Jadwal Tidak Tersedia
                                    @endif
                                </td>
                                <td>{{ $booking->schedule->trainer->name ?? 'N/A' }}</td>
                                <td>{{ $booking->booking_date->format('d M Y, H:i') }}</td>
                                <td class="text-end">
                                    @if($booking->schedule && $booking->schedule->trashed())
                                        <span class="badge bg-danger">Kelas Dihapus</span>
                                    @else
                                        <form action="{{ route('member.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan booking ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-x-circle"></i> Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Anda belum memiliki booking kelas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
