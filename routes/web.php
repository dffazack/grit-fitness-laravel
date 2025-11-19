<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

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
use App\Http\Controllers\Member\BookingController as MemberBookingController;
// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\MembershipPackageController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\Admin\TrainerController as AdminTrainerController;
use App\Http\Controllers\Admin\HomepageController as AdminHomepageController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Public & Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/classes', [ClassController::class, 'index'])->name('classes');
Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers');
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

use Illuminate\Auth\Events\Verified;

/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Email Verification Routes
    Route::get('/email/verify', function () {
        return view('auth.verify');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
        $user = Auth::user();

        dd($id, $user->getKey(), $hash, sha1($user->getEmailForVerification()));

        // Check if logged-in user ID matches ID from URL
        if (! hash_equals((string) $id, (string) $user->getKey())) {
            abort(403);
        }

        // Check if hash from URL matches email's SHA1 hash
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        // Fulfill the verification
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        
        // Re-login the user to refresh the session with the new state
        Auth::login($user);

        return redirect()->route('home')->with('verified', true);
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Payment (Guest and Member should be able to access this)
    Route::get('/member/payment', [MemberPaymentController::class, 'index'])->name('member.payment');
    Route::post('/member/payment', [MemberPaymentController::class, 'store'])->name('member.payment.store');
});

/*
|--------------------------------------------------------------------------
| Member Routes (Strictly for Active Members)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [MemberProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [MemberProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [MemberProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // Booking Routes
    Route::get('bookings', [MemberBookingController::class, 'index'])->name('bookings.index');
    Route::post('bookings/{schedule}', [MemberBookingController::class, 'store'])->name('bookings.store');
    Route::delete('bookings/{booking}', [MemberBookingController::class, 'destroy'])->name('bookings.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Login Routes
|--------------------------------------------------------------------------
*/
// TIDAK pakai middleware guest:admin karena ada bug
// Kita handle manual di controller
Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'login'])->name('admin.login.submit');

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
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});