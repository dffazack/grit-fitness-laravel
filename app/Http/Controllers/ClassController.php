<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Trainer;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index()
    {
        $schedules = ClassSchedule::with('trainer')
            ->where('is_active', true)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');
            
        $trainers = Trainer::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('classes.index', compact('schedules', 'trainers'));
    }

    public function schedule()
    {
        $schedules = ClassSchedule::with('trainer')
            ->where('is_active', true)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        $myBookings = Auth::user()->bookings()->with('classSchedule.trainer')->get();

        return view('member.schedule', compact('schedules', 'myBookings'));
    }

    public function book(ClassSchedule $schedule)
    {
        $user = Auth::user();

        if (!$user->hasActiveMembership()) {
            return redirect()->back()->with('error', 'You do not have an active membership.');
        }

        if ($schedule->isFull()) {
            return redirect()->back()->with('error', 'This class is already full.');
        }

        Booking::create([
            'user_id' => $user->id,
            'class_schedule_id' => $schedule->id,
            'booking_date' => now(),
        ]);

        $schedule->increment('quota');

        return redirect()->back()->with('success', 'Class booked successfully!');
    }

    public function cancel(ClassSchedule $schedule)
    {
        $user = Auth::user();

        $booking = Booking::where('user_id', $user->id)
            ->where('class_schedule_id', $schedule->id)
            ->first();

        if ($booking) {
            $booking->delete();
            $schedule->decrement('quota');
            return redirect()->back()->with('success', 'Booking canceled successfully!');
        }

        return redirect()->back()->with('error', 'Booking not found.');
    }
}