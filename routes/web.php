<?php

use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
// Member Controllers
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;
use App\Http\Controllers\Member\PaymentController as MemberPaymentController;
// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\MembershipPackageController;
use App\Http\Controllers\Admin\TrainerController as AdminTrainerController;
use App\Http\Controllers\Admin\HomepageController as AdminHomepageController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Auth\AdminLoginController;

/*
|--------------------------------------------------------------------------
| Public & Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/classes', [ClassController::class, 'index'])->name('classes');
Route::get('/trainers', [AdminTrainerController::class, 'index'])->name('trainers');
Route::get('/membership', [MembershipController::class, 'index'])->name('membership');

/*
|--------------------------------------------------------------------------
 
| Member Auth Routes
=======
| MEMBER ROUTES (Perlu login sebagai member/guest)
  85aa68d7e34d38b2c4fde502a208b3a92bd6d18f
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

 
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [MemberProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [MemberProfileController::class, 'update'])->name('profile.update');
>>>>>>>>> Temporary merge branch 2

    // Payment (Guest harus bisa akses ini)
    Route::get('/payment', [MemberPaymentController::class, 'index'])
        ->name('payment');

    Route::post('/payment', [MemberPaymentController::class, 'store'])
        ->name('payment.store');
  85aa68d7e34d38b2c4fde502a208b3a92bd6d18f
});

/*
|--------------------------------------------------------------------------
| Admin Login Routes
|--------------------------------------------------------------------------
*/
// TIDAK pakai middleware guest:admin karena ada bug
// Kita handle manual di controller
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');

// Rute yang HANYA BISA diakses oleh ROLE 'MEMBER' AKTIF
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {

    Route::get('/dashboard', [MemberDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [MemberProfileController::class, 'index']) // <-- GUNAKAN ALIAS INI
        ->name('profile');

    Route::post('/profile', [MemberProfileController::class, 'update']) // <-- GUNAKAN ALIAS INI JUGA
        ->name('profile.update');

        // TAMBAHKAN BARIS INI:
    Route::post('/profile/password', [MemberProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // Tambahkan rute lain yang khusus member di sini
    // (Contoh: Booking kelas)
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('reports/financial', [AdminDashboardController::class, 'financialReport'])->name('reports.financial');
    
    // Members
    Route::get('members', [AdminMemberController::class, 'index'])->name('members.index');
    Route::post('members', [AdminMemberController::class, 'store'])->name('members.store');
    Route::get('members/{member}', [AdminMemberController::class, 'show'])->name('members.show');
    Route::get('members/{member}/edit', [AdminMemberController::class, 'edit'])->name('members.edit');
    Route::put('members/{member}', [AdminMemberController::class, 'update'])->name('members.update');
    Route::delete('members/{member}', [AdminMemberController::class, 'destroy'])->name('members.destroy');
    
    // Schedules
    Route::resource('schedules', AdminScheduleController::class);

    // Facilities
    Route::resource('facilities', \App\Http\Controllers\Admin\FacilityController::class);

    // Memberships
    Route::resource('memberships', MembershipPackageController::class);
    
    // Trainers
    Route::resource('trainers', AdminTrainerController::class)->middleware(\App\Http\Middleware\RejectLargeUploads::class);
    
    // Payments
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/approve/{transaction}', [AdminPaymentController::class, 'approve'])->name('payments.approve');
    Route::post('payments/reject/{transaction}', [AdminPaymentController::class, 'reject'])->name('payments.reject');
    
    // Notifications
    Route::post('notifications/{notification}/toggle', [AdminNotificationController::class, 'toggleStatus'])->name('notifications.toggle');
    Route::resource('notifications', AdminNotificationController::class);
    
    // Homepage Management
    Route::get('homepage', [AdminHomepageController::class, 'index'])->name('homepage.index');
    Route::get('homepage/edit', [AdminHomepageController::class, 'edit'])->name('homepage.edit');
    Route::put('homepage/hero', [AdminHomepageController::class, 'updateHero'])->name('homepage.hero');
    Route::put('homepage/stats', [AdminHomepageController::class, 'updateStats'])->name('homepage.stats');
    Route::put('homepage/benefits', [AdminHomepageController::class, 'updateBenefits'])->name('homepage.benefits');
    Route::put('homepage/testimonials', [AdminHomepageController::class, 'updateTestimonials'])->name('homepage.testimonials');
    
    // Logout
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');
});