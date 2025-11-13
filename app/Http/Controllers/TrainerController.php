<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::where('is_active', true)
            ->orderBy('name')
            ->paginate(9);
        
        return view('trainers.index', compact('trainers'));
    }
}
