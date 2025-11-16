<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::with(['classSchedules'])
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
        
        return view('trainers', compact('trainers'));
    }
}