<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        
        // Get user's upcoming bookings
        $myBookings = $user->bookings()
            ->with(['classSchedule' => function ($query) {
                $query->with(['trainer', 'classList']);
            }])
            ->get()
            ->filter(function ($booking) {
                // Basic upcoming filter: today or future days
                $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                $todayIndex = array_search(now()->format('l'), $dayOrder);
                $classIndex = array_search($booking->classSchedule->day, $dayOrder);
                return $classIndex >= $todayIndex;
            })
            ->sortBy(function($booking) {
                return $booking->classSchedule->day . $booking->classSchedule->start_time;
            })
            ->take(5);

        // Generic upcoming classes (for exploration)
        $upcomingClasses = ClassSchedule::with('trainer', 'classList')
            ->where('is_active', true)
            ->where('day', '>=', now()->format('l')) // Simplified: from today onwards
            ->orderBy('day', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(6)
            ->get();
            
        $facilities = Facility::where('is_active', true)
            ->orderBy('order')
            ->limit(4)
            ->get();
        
        return view('member.dashboard', compact('user', 'myBookings', 'upcomingClasses', 'facilities'));
    }
}
