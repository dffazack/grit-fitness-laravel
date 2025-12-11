<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $filterDay = $request->query('day');
        $filterType = $request->query('class_type');

        $schedulesQuery = ClassSchedule::with('trainer', 'classList')
            ->where('is_active', true);

        if ($filterDay && $filterDay !== 'all') {
            $schedulesQuery->where('day', $filterDay);
        }

        if ($filterType && $filterType !== 'all') {
            $schedulesQuery->whereHas('classList', function ($query) use ($filterType) {
                $query->where('name', $filterType);
            });
        }

        $schedules = $schedulesQuery->orderBy('day')->orderBy('start_time')->get()->groupBy('day');

        $trainers = Trainer::where('is_active', true)
            ->orderBy('name')
            ->get();

        $userBookings = [];
        if (Auth::check()) {
            $userBookings = Auth::user()->bookings()->pluck('class_schedule_id')->toArray();
        }

        return view('classes.index', compact('schedules', 'trainers', 'userBookings', 'filterDay', 'filterType'));
    }
}
