<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\ClassSchedule;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    
    public function index()
    {
        // 1. Data untuk "Laporan Keuangan Harian"
        // (Ini dari kode Anda sebelumnya, sudah benar)
        $reports = Transaction::selectRaw('DATE(created_at) as date, 
                                          SUM(CASE WHEN status = "approved" THEN amount ELSE 0 END) as revenue,
                                          COUNT(CASE WHEN status = "approved" THEN 1 END) as subscriptions,
                                          COUNT(CASE WHEN status = "pending" THEN 1 END) as pending')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // 2. Data untuk "Pembayaran Menunggu Validasi"
        $pendingPayments = Transaction::where('status', 'pending')
                            ->with(['user', 'membership'])
                            ->latest()
                            ->take(5)
                            ->get();

        // 3. Data untuk "Booking Hari Ini"
        $todayClasses = ClassSchedule::whereDate('start_time', Carbon::today())
                            ->with('trainer')
                            ->withCount('bookings') // Ini akan membuat 'bookings_count'
                            ->get();
        

        // 5. Kirim SEMUA data ke view
        return view('admin.dashboard', compact(
            'reports',
            'pendingPayments',
            'todayClasses'
        ));
    }
}
