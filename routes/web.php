<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingPageController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\LoginAdminController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminQueueController;
use App\Http\Controllers\AdminPaymentController;

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

// Register User
Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'store']);

// Halaman Antrian pada User
Route::get('/queue/create/{date}', [QueueController::class, 'create'])->name('queue.create')->middleware('auth');    
Route::post('/queue/store/{date}', [QueueController::class, 'store'])->name('queue.store')->middleware('auth');
Route::get('/queue', [QueueController::class, 'index'])->name('queue.index')->middleware('auth');

// Profile
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index')->middleware('auth');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

// Halaman Antrian pada Admin
Route::get('/adm-queue', [AdminQueueController::class, 'index'])->middleware(AdminMiddleware::class)->name('admin.antrian.index');
Route::post('/adm-queue', [AdminQueueController::class, 'store'])->middleware(AdminMiddleware::class)->name('admin.antrian.store');
Route::post('/adm-queue/{queue}/next', [AdminQueueController::class, 'next'])->name('admin.antrian.next');
Route::post('/adm-queue/{queue}/previous', [AdminQueueController::class, 'previous'])->name('admin.antrian.previous');
// Route::get('/adm-queue/{queue}/edit', [AdminQueueController::class, 'edit'])->name('admin.antrian.edit');
// Route::put('/adm-queue/{queue}', [AdminQueueController::class, 'update'])->name('admin.antrian.update');
Route::delete('/adm-queue/{queue}', [AdminQueueController::class, 'destroy'])->name('admin.antrian.destroy');

// Halaman Pembayaran pada Admin
Route::get('/adm-payment', [AdminPaymentController::class, 'index'])->middleware(AdminMiddleware::class)->name('admin.pembayaran.index');
Route::get('/adm-payment/{payment}/details', [AdminPaymentController::class, 'getDetails'])->name('admin.pembayaran.details');
Route::post('/adm-payment/process', [AdminPaymentController::class, 'processPayment'])->name('admin.pembayaran.process');
Route::put('/adm-payment/update', [AdminPaymentController::class, 'update'])->name('admin.pembayaran.update');
Route::get('/adm-payment/{payment}/receipt', [AdminPaymentController::class, 'getReceipt'])->name('admin.pembayaran.receipt');