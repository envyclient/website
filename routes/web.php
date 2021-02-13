<?php

use App\Http\Controllers\Actions\DisableAccount;
use App\Http\Controllers\Actions\StoreLicenseRequest;
use App\Http\Controllers\Actions\UploadVersion;
use App\Http\Controllers\Actions\UseReferralCode;
use App\Http\Controllers\CapesController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\LauncherController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PagesController;
use App\Http\Livewire\ShowLicenseRequests;
use Illuminate\Support\Facades\Route;

/**
 * Home
 */
Route::group([], function () {
    Route::view('/', 'pages.index')
        ->name('index');

    Route::get('dashboard', [PagesController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('profile', [PagesController::class, 'profile'])
        ->name('pages.profile');

    Route::get('discord', [PagesController::class, 'discord'])
        ->name('pages.discord');

    Route::get('subscription', [PagesController::class, 'subscription'])
        ->name('pages.subscription');

    Route::view('terms', 'pages.terms')
        ->name('pages.terms');
});

/**
 * Admin
 */
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {

    // list users and versions
    Route::view('users', 'pages.dashboard.admin.users')
        ->name('admin.users');

    Route::view('versions', 'pages.dashboard.admin.versions')
        ->name('admin.versions');

    Route::view('referrals', 'pages.dashboard.admin.referrals')
        ->name('admin.referrals');

    Route::get('notifications', [PagesController::class, 'notifications'])
        ->name('admin.notifications');

    Route::get('sales', [PagesController::class, 'sales'])
        ->name('admin.sales');

    Route::get('license-requests', ShowLicenseRequests::class)
        ->name('admin.license-requests');

    // upload version
    Route::post('versions', UploadVersion::class)
        ->name('admin.versions.upload');
});


/**
 * Users
 */
Route::group(['prefix' => 'user'], function () {
    Route::post('referral-code', UseReferralCode::class)
        ->name('users.referral-code');

    Route::post('license-request', StoreLicenseRequest::class)
        ->name('users.license-request');

    Route::delete('disable', DisableAccount::class)
        ->name('users.disable');
});

/**
 * Capes
 */
Route::group(['prefix' => 'cape'], function () {
    Route::post('/', [CapesController::class, 'store'])
        ->name('capes.store');

    Route::delete('/', [CapesController::class, 'destroy'])
        ->name('capes.delete');
});

/**
 * Launcher
 */
Route::group(['prefix' => 'launcher'], function () {
    Route::get('/download', [LauncherController::class, 'download'])
        ->name('launcher.show');

    Route::post('/', [LauncherController::class, 'store'])
        ->name('launcher.store');
});

/**
 * OAuth Connect
 */
Route::group(['prefix' => 'connect'], function () {
    Route::group(['prefix' => 'discord'], function () {
        Route::get('/', [DiscordController::class, 'login'])
            ->name('connect.discord');

        Route::get('redirect', [DiscordController::class, 'redirect']);
    });
});

Route::group(['prefix' => 'notifications'], function () {
    Route::post('/', [NotificationsController::class, 'store'])
        ->name('notifications.store');

    Route::delete('{id}', [NotificationsController::class, 'destroy'])
        ->name('notifications.destroy');
});

require __DIR__ . '/subscriptions.php';
require __DIR__ . '/auth.php';
