<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {

    Route::post('register', [RegisterController::class, 'register'])
        ->middleware(['throttle:auth'])
        ->name('register');

    Route::post('login', [AuthController::class, 'login'])
        ->middleware(['throttle:auth'])
        ->name('login');

    Route::middleware(['auth:sanctum', 'throttle:authenticated', 'verified'])->group(function (): void {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('user', [AuthController::class, 'user'])->name('user');
    });

    Route::middleware(['auth:sanctum', 'throttle:authenticated'])->group(function (): void {
        Route::post('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
            ->middleware(['signed'])
            ->name('verification.verify');

        Route::post('email/resend', [EmailVerificationController::class, 'resend'])
            ->middleware('throttle:3,1')
            ->name('verification.resend');
    });

    Route::middleware(['throttle:auth'])->group(function (): void {
        Route::post('forgot-password', [PasswordController::class, 'forgotPassword'])
            ->name('password.email');

        Route::post('reset-password', [PasswordController::class, 'resetPassword'])
            ->name('password.reset');
    });
});
