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

// Routes accessibles aux invités (non connectés)
Route::middleware('guest')->group(function () {
    // Inscription (Register -> doko972)
    Route::get('/doko972', [RegisteredUserController::class, 'create'])
        ->name('register');
    
    Route::post('/doko972', [RegisteredUserController::class, 'store']);

    // Connexion (Login -> se_logger)
    Route::get('/se_logger', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    
    Route::post('/se_logger', [AuthenticatedSessionController::class, 'store']);

    // Mot de passe oublié
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // Réinitialisation du mot de passe
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// Routes nécessitant une authentification
Route::middleware('auth')->group(function () {
    // Vérification de l'email
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Confirmation du mot de passe
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Mise à jour du mot de passe
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    // Déconnexion
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
