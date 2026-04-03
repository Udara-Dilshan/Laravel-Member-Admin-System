<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('members', MemberController::class)->except(['show']);

    Route::middleware('admin')->group(function (): void {
        Route::resource('admins', AdminController::class)->except(['show']);
        Route::get('/otp/verify', [OtpController::class, 'show'])->name('otp.show');
        Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
        Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
        Route::post('/otp/cancel', [OtpController::class, 'cancel'])->name('otp.cancel');
    });
});
