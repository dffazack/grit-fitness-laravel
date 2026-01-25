<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HomepageController as AdminHomepageController;
// Import Controllers (sesuai file yang kamu kirim)
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\MembershipPackageController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\TrainerController as AdminTrainerController;
use App\Http\Controllers\Auth\ForgotPasswordController;
// Member Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ClassController;
// Admin Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Member\BookingController as MemberBookingController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\PaymentController as MemberPaymentController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\TrainerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Guest Routes
|-------------------------------------------x-------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/classes', [ClassController::class, 'index'])->name('classes');
Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers');
Route::get('/membership', [MembershipController::class, 'index'])->name('membership');

/*
|--------------------------------------------------------------------------
| Member Auth Routes (guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');

    Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register.submit');

    // Forgot / Reset password (guest)
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('password/reset', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// Logout (auth)
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ...

Route::get('/email/verify/{id}/{hash}', function (Illuminate\Http\Request $request, $id, $hash) {

    // 1. Cari User (Pakai findOrFail biar ketahuan kalau ID salah)
    $user = \App\Models\User::findOrFail($id);

    // 2. Cek Signature (Keamanan Link)
    // Bagian ini sering gagal di localhost. Kita tambahkan log untuk debugging.
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        // Jika gagal signature, kita return error message yang jelas
        abort(403, 'Link verifikasi rusak atau tidak valid.');
    }

    // 3. Cek apakah URL ini valid (expired atau diedit orang)
    // Catatan: Middleware 'signed' di bawah sebenarnya sudah mengecek ini.
    // Tapi kita cek manual lagi untuk memastikan hash emailnya benar.
    if (!$request->hasValidSignature()) {
        abort(403, 'Link verifikasi sudah kadaluarsa atau tidak valid.');
    }

    // 4. Skenario: User sebenarnya SUDAH verifikasi sebelumnya
    if ($user->hasVerifiedEmail()) {
        // Auto login user tersebut jika belum login
        if (!Auth::check()) {
            Auth::login($user, true);
        }

        // Redirect dengan pesan manis
        return redirect()->route('home')->with('info', 'Email Anda sudah terverifikasi sebelumnya.');
    }

    // 5. PROSES VERIFIKASI UTAMA
    if ($user->markEmailAsVerified()) {
        event(new \Illuminate\Auth\Events\Verified($user));

        // LOGIKA BISNIS KAMU: Ubah Guest jadi Member
        if ($user->role === 'guest') {
            $user->update(['role' => 'member']);
        }
    }

    // 6. Login otomatis setelah sukses
    Auth::login($user, true);

    return redirect()->route('home')->with('success', 'Selamat! Email berhasil diverifikasi.');

})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

/*
|--------------------------------------------------------------------------
| Email Verification (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Notice page
    Route::get('/email/verify', function () {
        return view('auth.verify');
    })->name('verification.notice');

    // Resend verification link (throttled)
    Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Payment (accessible to authenticated users)
    Route::get('/member/payment', [MemberPaymentController::class, 'index'])->middleware(['verified'])->name('member.payment');
    Route::post('/member/payment', [MemberPaymentController::class, 'store'])->middleware(['verified'])->name('member.payment.store');
});

/*
|--------------------------------------------------------------------------
| Member Routes (authenticated, verified)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('member')->name('member.')->group(function () {
    // Profile is accessible to any logged-in user
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::post('/profile/update-phone-quick', [ProfileController::class, 'updatePhoneQuick'])->name('profile.updatePhoneQuick');

    // Routes below require an active membership status
    Route::middleware([\App\Http\Middleware\CheckMembershipStatus::class])->group(function () {
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

        Route::get('bookings', [MemberBookingController::class, 'index'])->name('bookings.index');
        Route::post('bookings/{schedule}', [MemberBookingController::class, 'store'])->name('bookings.store');
        Route::delete('bookings/{booking}', [MemberBookingController::class, 'destroy'])->name('bookings.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Login Routes (manual handling in controller)
|--------------------------------------------------------------------------
*/
Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'login'])->name('admin.login.submit');

/*
|--------------------------------------------------------------------------
| Admin Protected Routes (auth:admin)
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
    Route::resource('schedules', AdminScheduleController::class);
    Route::post('bookings/{booking}/attendance', [AdminScheduleController::class, 'toggleAttendance'])->name('bookings.toggleAttendance');

    // Facilities
    Route::resource('facilities', \App\Http\Controllers\Admin\FacilityController::class);

    // Memberships
    Route::resource('memberships', MembershipPackageController::class);

    // Trainers
    Route::resource('trainers', AdminTrainerController::class)
        ->middleware(\App\Http\Middleware\RejectLargeUploads::class);

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

    // Logout (admin)
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// --- TEMPORARY DEBUG ROUTE (Hapus nanti jika sudah live production) ---
Route::get('/debug/clear-cache', function () {
    try {
        Artisan::call('optimize:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        return "Cache cleared successfully! <br> <a href='/'>Go back to Home</a>";
    } catch (\Exception $e) {
        return "Failed to clear cache: " . $e->getMessage();
    }
});
