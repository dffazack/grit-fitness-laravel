<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the user's bookings.
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with(['classSchedule' => function ($query) {
                $query->withTrashed()->with(['trainer', 'classList']);
            }])
            ->latest('booking_date')
            ->get();

        return view('member.bookings.index', compact('bookings'));
    }

    /**
     * Store a new booking.
     */
    public function store(Request $request, ClassSchedule $schedule)
    {
        $user = Auth::user();

        // 1. Check if user is an active member
        if (! $user->hasActiveMembership()) {
            return redirect()->back()->with('error', 'Anda harus memiliki membership aktif untuk booking kelas.');
        }

        // 2. Check if the class is full
        if ($schedule->isFull()) {
            return redirect()->back()->with('error', 'Kelas ini sudah penuh.');
        }

        // 3. Check if the user has already booked this class
        $alreadyBooked = Booking::where('user_id', $user->id)
            ->where('class_schedule_id', $schedule->id)
            ->exists();

        if ($alreadyBooked) {
            return redirect()->back()->with('error', 'Anda sudah memesan kelas ini.');
        }

        // 4. Create the booking
        Booking::create([
            'user_id' => $user->id,
            'class_schedule_id' => $schedule->id,
            'booking_date' => now(),
        ]);

        // 5. Increment quota
        $schedule->increment('quota');

        return redirect()->back()->with('success', 'Kelas berhasil di-booking!');
    }

    /**
     * Cancel (delete) a booking.
     */
    public function destroy(Booking $booking)
    {
        // 1. Check if the booking belongs to the authenticated user
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Anda tidak diizinkan untuk membatalkan booking ini.');
        }

        // 2. Decrement quota on the schedule
        $schedule = $booking->schedule;
        if ($schedule) {
            $schedule->decrement('quota');
        }

        // 3. Delete the booking
        $booking->delete();

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
