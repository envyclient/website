<?php

use App\Http\Controllers\Actions\DeleteAccount;
use App\Http\Controllers\Actions\UseReferralCode;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Admin\LicenseRequestsTable;
use App\Http\Livewire\Admin\User\UsersTable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/**
 * Ping route
 */
Route::get('ping', fn () => response());

/**
 * Dashboard Pages
 */
Route::group([], function () {
    /**
     * Landing Pages
     */
    Route::group([], function () {
        Route::view('/', 'pages.index')->name('index');
        Route::view('terms', 'pages.terms')->name('terms');
    });

    /**
     * Dashboard
     */
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('home', [HomeController::class, 'home'])->name('home');
        Route::get('profile', [HomeController::class, 'profile'])->name('home.profile');
        Route::get('subscription', [HomeController::class, 'subscription'])->name('home.subscription');
    });

    /**
     * Admin Dashboard
     */
    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'admin']], function () {
        Route::get('users', UsersTable::class)->name('admin.users');
        Route::view('versions', 'pages.dashboard.admin.versions')->name('admin.versions');
        Route::view('launcher', 'pages.dashboard.admin.launcher')->name('admin.launcher');
        Route::view('loader', 'pages.dashboard.admin.loader')->name('admin.loader');
        Route::view('referrals', 'pages.dashboard.admin.referrals')->name('admin.referrals');
        Route::get('license-requests', LicenseRequestsTable::class)->name('admin.license-requests');
    });
});

/**
 * Users
 */
Route::group(['prefix' => 'user', 'middleware' => ['auth', 'verified']], function () {
    Route::post('referral-code', UseReferralCode::class)->name('users.referral-code');
    Route::delete('delete', DeleteAccount::class)->name('users.delete');
});

/**
 * Download Launcher
 */
Route::get('download', fn () => Storage::disk('s3')->download('launcher.exe', 'envy.exe'))
    ->middleware(['auth', 'verified', 'subscribed'])
    ->name('launcher.download');

require __DIR__.'/subscriptions.php';
require __DIR__.'/auth.php';
