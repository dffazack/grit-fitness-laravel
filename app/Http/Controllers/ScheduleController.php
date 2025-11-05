<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSchedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = ClassSchedule::with(['trainer'])
            ->where('is_active', true)
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->get()
            ->map(function ($schedule) {
                // Calculate available slots
                $bookedCount = $schedule->bookings()
                    ->where('booking_date', '>=', now()->toDateString())
                    ->where('status', 'confirmed')
                    ->count();
                
                $schedule->available_slots = max(0, $schedule->quota - $bookedCount);
                return $schedule;
            });
        
        return view('schedule', compact('schedules'));
    }
}