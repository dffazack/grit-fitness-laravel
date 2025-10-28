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
use App\Http\Controllers\Admin\TrainerController as AdminTrainerController;
use App\Http\Controllers\Admin\HomepageController as AdminHomepageController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;


/*
|--------------------------------------------------------------------------
| Public & Guest Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Classes & Trainers (Publicly visible)
Route::get('/classes', [ClassController::class, 'index'])->name('classes');
Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers'); // Menggunakan TrainerController Admin
Route::get('/membership', [MembershipController::class, 'index'])->name('membership');

// Authentication Routes (Default Laravel UI Scaffolding)
// Note: Karena Anda menggunakan Laravel 11, routing auth harus ditambahkan manual jika tidak menggunakan Breeze/Jetstream
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);


/*
|--------------------------------------------------------------------------
| Member Routes (Role: member)
|--------------------------------------------------------------------------
| Memerlukan autentikasi dan role 'member'
*/

Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    
    // Dashboard (Menggunakan MemberDashboardController)
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [MemberProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [MemberProfileController::class, 'update'])->name('profile.update');

    // Payment & Subscription
    Route::get('/payment', [MemberPaymentController::class, 'index'])->name('payment');
    Route::post('/payment/submit', [MemberPaymentController::class, 'submitPayment'])->name('payment.submit');

    // Class Booking/Schedule View
    Route::get('/schedule', [ClassController::class, 'schedule'])->name('schedule');
    Route::post('/class/book/{schedule}', [ClassController::class, 'book'])->name('class.book');
    Route::post('/class/cancel/{schedule}', [ClassController::class, 'cancel'])->name('class.cancel');
});


/*
|--------------------------------------------------------------------------
| Admin Routes (Role: admin)
|--------------------------------------------------------------------------
| Memerlukan autentikasi dan role 'admin'
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard & Financial Report
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports/financial', [AdminDashboardController::class, 'financialReport'])->name('reports.financial');

    // Member Management (CRUD)
    Route::resource('members', AdminMemberController::class)->except(['create']);

    // Payment Validation
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/approve/{transaction}', [AdminPaymentController::class, 'approve'])->name('payments.approve');
    Route::post('payments/reject/{transaction}', [AdminPaymentController::class, 'reject'])->name('payments.reject');

    // Schedule Management (CRUD)
    Route::resource('schedules', AdminScheduleController::class);

    // Data Master
    Route::resource('trainers', AdminTrainerController::class);
    Route::resource('notifications', AdminNotificationController::class);
    Route::resource('homepage', AdminHomepageController::class)->only(['index', 'update']);
});