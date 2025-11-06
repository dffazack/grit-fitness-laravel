<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user = Auth::user();
        
        $upcomingClasses = ClassSchedule::with('trainer')
            ->where('is_active', true)
            ->limit(6)
            ->get();
            
        $facilities = Facility::where('is_active', true)
            ->orderBy('order')
            ->limit(4)
            ->get();
        
        return view('member.dashboard', compact('user', 'upcomingClasses', 'facilities'));
    }
}
