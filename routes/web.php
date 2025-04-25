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

Route::get('/', [LandingPageController::class, 'index'])->middleware('guest')->name('landing');

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::get('/login', [LoginUserController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginUserController::class, 'authenticate']);
Route::post('/logout', [LoginUserController::class, 'logout'])->name('logout');

Route::get('/login-adm', [LoginAdminController::class, 'index'])->middleware("guest");
Route::post('/login-adm', [LoginAdminController::class, 'autentificate']);
Route::post('/logout-adm', [LoginAdminController::class, 'logout'])->name('admin.logout');

Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/queue/create/{date}', [QueueController::class, 'create'])->name('queue.create')->middleware('auth');    
Route::post('/queue/store/{date}', [QueueController::class, 'store'])->name('queue.store')->middleware('auth');
Route::get('/queue', [QueueController::class, 'index'])->name('queue.index')->middleware('auth');

Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth');


// Route::get('/adm-home', function() {
//     return view('admin.home.index', ['title' => 'Antrian' ]);
// })->middleware(AdminMiddleware::class);

Route::get('/adm-queue', [AdminQueueController::class, 'index'])->middleware(AdminMiddleware::class)->name('admin.antrian.index');
Route::post('/adm-queue', [AdminQueueController::class, 'store'])->middleware(AdminMiddleware::class)->name('admin.antrian.store');
Route::post('/adm-queue/{queue}/next', [AdminQueueController::class, 'next'])->name('admin.antrian.next');
Route::post('/adm-queue/{queue}/previous', [AdminQueueController::class, 'previous'])->name('admin.antrian.previous');
Route::get('/adm-queue/{queue}/edit', [AdminQueueController::class, 'edit'])->name('admin.antrian.edit');
Route::put('/adm-queue/{queue}', [AdminQueueController::class, 'update'])->name('admin.antrian.update');
Route::delete('/adm-queue/{queue}', [AdminQueueController::class, 'destroy'])->name('admin.antrian.destroy');
Route::get('/adm-payment', [AdminPaymentController::class, 'index'])->middleware(AdminMiddleware::class);