<?php

use App\Http\Controllers\Auth\AuthenticatonController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->group(function (): void {
        Route::post('register', [RegisterController::class, 'register'])
            ->middleware(['throttle:auth'])
            ->name('auth.register');

        Route::post('login', [AuthenticatonController::class, 'login'])
            ->middleware(['throttle:auth'])
            ->name('auth.login');

        Route::middleware(['auth:sanctum', 'throttle:authenticated'])->group(function (): void {
            Route::post('logout', [AuthenticatonController::class, 'logout'])->name('auth.logout');
            Route::get('me', [AuthenticatonController::class, 'me'])->name('auth.me');

            // Email verification
        });
    });
