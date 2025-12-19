<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasskeyController;
use App\Http\Controllers\MagicLinkController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\BrowserSessionController;

// Magic Link Routes
Route::post('/magic-link/send', [MagicLinkController::class, 'sendMagicLink'])
    ->middleware(['guest', 'throttle:5,1'])
    ->name('magic-link.send');

Route::get('/magic-link/login/{user}', [MagicLinkController::class, 'authenticateViaLink'])
    ->middleware(['guest', 'signed'])
    ->name('magic-link.login');

Route::middleware('auth')->group(function () {
    Route::get('/settings/passkeys', [PasskeyController::class, 'index'])->name('passkeys.index');

    Route::post('/settings/passkeys', [PasskeyController::class, 'store'])->name('passkeys.store');

    Route::delete('/settings/passkeys/{id}', [PasskeyController::class, 'delete'])->name('passkeys.delete');

    Route::delete('/user/profile-photo', [ProfileController::class, 'destroyPhoto'])->name('profile-photo.destroy');

    Route::get('/settings/browser-sessions', [BrowserSessionController::class, 'showBrowserSessions'])->name('browser-sessions')->middleware(['password.confirm']);

    Route::delete('/user/other-browser-sessions', [BrowserSessionController::class, 'destroy'])->name('other-browser-sessions.destroy');
});
