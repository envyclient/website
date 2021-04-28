<?php

use App\Http\Controllers\Actions\DisableAccount;
use App\Http\Controllers\Actions\DownloadLauncher;
use App\Http\Controllers\Actions\HandleDiscordWebhook;
use App\Http\Controllers\Actions\UseReferralCode;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Admin\LicenseRequestsTable;
use App\Http\Livewire\Admin\User\UsersTable;
use App\Http\Middleware\Custom\Setup\CheckIfPasswordNull;
use Illuminate\Support\Facades\Route;

/**
 * Home
 */
Route::group(['middleware' => CheckIfPasswordNull::class], function () {

    /**
     * Landing Pages
     */
    Route::group([], function () {
        Route::view('/', 'pages.index')
            ->name('index');

        Route::view('terms', 'pages.terms')
            ->name('terms');
    });

    /**
     * Dashboard
     */
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('home', [HomeController::class, 'home'])
            ->name('home');

        Route::get('profile', [HomeController::class, 'profile'])
            ->name('home.profile');

        Route::get('subscription', [HomeController::class, 'subscription'])
            ->name('home.subscription');
    });

    /**
     * Admin Dashboard
     */
    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'admin']], function () {

        // list users and versions
        Route::get('users', UsersTable::class)
            ->name('admin.users');

        Route::view('versions', 'pages.dashboard.admin.versions')
            ->name('admin.versions');

        Route::view('referrals', 'pages.dashboard.admin.referrals')
            ->name('admin.referrals');

        Route::get('license-requests', LicenseRequestsTable::class)
            ->name('admin.license-requests');
    });
});


/**
 * Users
 */
Route::group(['prefix' => 'user', 'middleware' => ['auth', 'verified']], function () {
    Route::post('referral-code', UseReferralCode::class)
        ->name('users.referral-code');

    Route::delete('disable', DisableAccount::class)
        ->name('users.disable');
});

/**
 * Download Launcher
 */
Route::get('/download', DownloadLauncher::class)
    ->middleware(['auth', 'verified', 'subscribed'])
    ->name('launcher.download');

/**
 * OAuth Connect
 */
Route::group(['prefix' => 'connect'], function () {

    Route::group(['prefix' => 'discord', 'middleware' => ['auth', 'verified']], function () {
        Route::get('/', [DiscordController::class, 'login'])
            ->name('connect.discord');

        Route::get('redirect', [DiscordController::class, 'redirect']);
    });
});

Route::post('discord/webhook', HandleDiscordWebhook::class);

require __DIR__ . '/subscriptions.php';
require __DIR__ . '/auth.php';
