<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Trainer;
use Illuminate\Http\Request;

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
}