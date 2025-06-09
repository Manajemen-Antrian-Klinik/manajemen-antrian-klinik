<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\QueueController;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\AdminQueueController;
use App\Http\Controllers\LoginAdminController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\ForgotPasswordController;

// Landing Page
Route::get('/', [LandingPageController::class, 'index'])->middleware('guest')->name('landing');

// Home
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

// Login User
Route::get('/login', [LoginUserController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginUserController::class, 'authenticate']);
Route::post('/logout', [LoginUserController::class, 'logout'])->name('logout');

// Login Admin
Route::get('/login-adm', [LoginAdminController::class, 'index'])->middleware("guest");
Route::post('/login-adm', [LoginAdminController::class, 'autentificate']);
Route::post('/logout-adm', [LoginAdminController::class, 'logout'])->name('admin.logout');

// Reset Password
Route::get('/forgot-password', function() {
    return view('auth.forgot-password', [
        'title' => 'Forgot Password'
    ]);
})
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', function(Request $request) {
    $request->validate(['email' => 'required|email:dns|exists:users,email']);
        
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
})->middleware('guest', 'throttle:5,1')->name('password.email');
    

Route::get('/reset-password/{token}', function(Request $request, string $token) {
    return view('auth.reset-password', [
        'title' => 'Reset Password',
        'token' => $token,
        'email' => $request->email
    ]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function(Request $request) {
    $request->validate([
        'email' => 'required|email:dns|exists:users,email',
        'password' => 'required|min:8|max:255|confirmed',
        'token' => 'required'
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));
            
            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect('/login')->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.update');

// Register User
Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'store']);


Route::middleware(['auth', 'type:user'])->group(function () {
    // Halaman Antrian pada User
    Route::get('/queue/create/{date}', [QueueController::class, 'create'])->name('queue.create')->middleware('auth');    
    Route::post('/queue/store/{date}', [QueueController::class, 'store'])->name('queue.store')->middleware('auth');
    Route::get('/queue', [QueueController::class, 'index'])->name('queue.index')->middleware('auth');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index')->middleware('auth');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
});


Route::middleware(['auth', 'type:admin'])->group(function () {
    // Halaman Admin
    Route::get('/adm-dashboard', [HomeController::class, 'adminIndex'])->name('admin.dashboard.index');

    // Halaman Antrian pada Admin
    Route::get('/adm-queue', [AdminQueueController::class, 'index'])->name('admin.antrian.index');
    Route::post('/adm-queue/{queue}/next', [AdminQueueController::class, 'next'])->name('admin.antrian.next');
    Route::post('/adm-queue/{queue}/previous', [AdminQueueController::class, 'previous'])->name('admin.antrian.previous');
    Route::delete('/adm-queue/{queue}', [AdminQueueController::class, 'destroy'])->name('admin.antrian.destroy');
    
    // Halaman Pembayaran pada Admin
    Route::get('/adm-payment', [AdminPaymentController::class, 'index'])->name('admin.pembayaran.index');
    Route::get('/adm-payment/{payment}/details', [AdminPaymentController::class, 'getDetails'])->name('admin.pembayaran.details');
    Route::post('/adm-payment/process', [AdminPaymentController::class, 'processPayment'])->name('admin.pembayaran.process');
    Route::put('/adm-payment/update', [AdminPaymentController::class, 'update'])->name('admin.pembayaran.update');
    Route::get('/adm-payment/{payment}/receipt', [AdminPaymentController::class, 'getReceipt'])->name('admin.pembayaran.receipt');
});