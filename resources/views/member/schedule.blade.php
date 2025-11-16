@extends('layouts.member')

@section('title', 'Jadwal Kelas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Jadwal Kelas Mingguan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                        <th>{{ $day }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $timeSlots = [];
                                    foreach ($schedules as $day => $classes) {
                                        foreach ($classes as $class) {
                                            $timeSlots[$class->start_time] = true;
                                        }
                                    }
                                    ksort($timeSlots);
                                @endphp
                                @foreach (array_keys($timeSlots) as $time)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($time)->format('H:i') }}</td>
                                        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                            <td>
                                                @php
                                                    $classOnDay = $schedules[ucfirst(strtolower($day))] ?? collect();
                                                    $class = $classOnDay->first(function($c) use ($time) {
                                                        return $c->start_time == $time;
                                                    });
                                                @endphp
                                                @if ($class)
                                                    <strong>{{ $class->name }}</strong><br>
                                                    <small>{{ $class->trainer->name }}</small><br>
                                                    @if ($myBookings->contains('class_schedule_id', $class->id))
                                                        <form action="{{ route('member.class.cancel', $class->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger mt-2">Batalkan</button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('member.class.book', $class->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-primary mt-2">Booking</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Kelas yang Anda Booking</h4>
                </div>
                <div class="card-body">
                    @if ($myBookings->isEmpty())
                        <p>Anda belum memesan kelas apapun.</p>
                    @else
                        <ul class="list-group">
                            @foreach ($myBookings as $booking)
                                <li class="list-group-item">
                                    <strong>{{ $booking->classSchedule->name }}</strong><br>
                                    <small>{{ $booking->classSchedule->day }}, {{ $booking->classSchedule->getFormattedTime() }}</small><br>
                                    <small>Trainer: {{ $booking->classSchedule->trainer->name }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection