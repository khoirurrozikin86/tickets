<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Register
    Route::get('register', [
        RegisteredUserController::class,
        'create',
    ])->name('register');

    Route::post('register', [
        RegisteredUserController::class,
        'store',
    ]);

    // Login
    Route::get('login', [
        AuthenticatedSessionController::class,
        'create',
    ])->name('login');

    Route::post('login', [
        AuthenticatedSessionController::class,
        'store',
    ])
        ->middleware('throttle:login');

});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Email Verification
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware([
            'signed',
            'throttle:6,1',
        ])
        ->name('verification.verify');

    Route::post(
        'email/verification-notification',
        [
            EmailVerificationNotificationController::class,
            'store',
        ]
    )
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Password Confirmation
    Route::get('confirm-password', [
        ConfirmablePasswordController::class,
        'show',
    ])->name('password.confirm');

    Route::post('confirm-password', [
        ConfirmablePasswordController::class,
        'store',
    ])
        ->middleware('throttle:6,1');

    // Change Password
    Route::put('password', [
        PasswordController::class,
        'update',
    ])->name('password.update');

    // Logout
    Route::post('logout', [
        AuthenticatedSessionController::class,
        'destroy',
    ])->name('logout');
});
