<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index(Request $request)
    {
        $filterSpecialization = $request->query('specialization');

        $trainersQuery = Trainer::where('is_active', true);

        if ($filterSpecialization && $filterSpecialization !== 'all') {
            $trainersQuery->where('specialization', $filterSpecialization);
        }

        $trainers = $trainersQuery->orderBy('name')->paginate(9);

        $specializations = Trainer::where('is_active', true)
            ->whereNotNull('specialization')
            ->distinct()
            ->pluck('specialization');

        return view('trainers.index', compact('trainers', 'specializations', 'filterSpecialization'));
    }
}
