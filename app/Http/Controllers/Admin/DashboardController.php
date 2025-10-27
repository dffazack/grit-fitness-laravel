<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index()
    {
        // Daily Financial Report
        $reports = Transaction::selectRaw('DATE(created_at) as date, 
                                          SUM(CASE WHEN status = "approved" THEN amount ELSE 0 END) as revenue,
                                          COUNT(CASE WHEN status = "approved" THEN 1 END) as subscriptions,
                                          COUNT(CASE WHEN status = "pending" THEN 1 END) as pending')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
        
        return view('admin.dashboard', compact('reports'));
    }
}
