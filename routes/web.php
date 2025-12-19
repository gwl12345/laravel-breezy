<?php

use Inertia\Inertia;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\WebAuthn\WebAuthnRegisterController;
use App\Http\Controllers\WebAuthn\WebAuthnLoginController;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::prefix('webauthn')->name('webauthn.')->group(function () {
    // Attestation (register)
    Route::post('register/challenge', [WebAuthnRegisterController::class, 'options'])
        ->name('register.challenge');

    Route::post('register', [WebAuthnRegisterController::class, 'register'])
        ->name('register');

    // Assertion (login)
    Route::post('login/challenge', [WebAuthnLoginController::class, 'options'])
        ->name('login.challenge');

    Route::post('login', [WebAuthnLoginController::class, 'login'])
        ->name('login');
})->withoutMiddleware(VerifyCsrfToken::class);

require __DIR__ . '/starter-kit.php';

require __DIR__ . '/settings.php';
