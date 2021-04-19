<?php

use App\Http\Controllers\Auth\DiscordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Passwords\Confirm;
use App\Http\Livewire\Auth\Passwords\Email;
use App\Http\Livewire\Auth\Passwords\Reset;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\SetupAccount;
use App\Http\Livewire\Auth\Verify;
use App\Http\Middleware\Custom\RedirectIfVerified;
use App\Http\Middleware\Custom\Setup\RedirectIfSetup;

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)
        ->name('login');

    Route::get('register', Register::class)
        ->name('register');
});

Route::middleware('guest')->group(function () {
    Route::get('login/discord', [DiscordController::class, 'login'])
        ->name('login.discord');

    Route::get('login/discord/redirect', [DiscordController::class, 'redirect']);
});

Route::get('forgot-password', Email::class)
    ->name('password.request');

Route::get('reset-password/{token}', Reset::class)
    ->name('password.reset');

Route::get('setup', SetupAccount::class)
    ->middleware('auth', RedirectIfSetup::class)
    ->name('setup-account');

Route::middleware('auth')->group(function () {
    Route::get('email/verify', Verify::class)
        ->middleware('throttle:6,1', RedirectIfVerified::class)
        ->name('verification.notice');

    Route::get('password/confirm', Confirm::class)
        ->name('password.confirm');
});

Route::middleware('auth')->group(function () {
    Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('logout', LogoutController::class)
        ->name('logout');
});
