<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Register Routes
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Login Routes
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');

    // Password Reset Routes
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    // Email Verification Routes
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Confirm Password Routes
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Change Password Routes
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');


    // Logout Route
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// 2FA Routes (ensure they're part of the auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/two-factor-challenge', [AuthenticatedSessionController::class, 'showTwoFactorForm'])
        ->name('two-factor.challenge');
    Route::post('/two-factor-challenge', [AuthenticatedSessionController::class, 'verifyTwoFactor'])
        ->name('two-factor.verify');
});

// Public welcome route
Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware('guest')->group(function () {
    // ... other guest routes ...
    
    // 2FA Routes
    Route::get('/two-factor-challenge', [AuthenticatedSessionController::class, 'showTwoFactorForm'])
        ->name('two-factor.challenge');
    Route::post('/two-factor-challenge', [AuthenticatedSessionController::class, 'verifyTwoFactor'])
        ->name('two-factor.verify');
});