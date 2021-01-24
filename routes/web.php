<?php

use App\Http\Controllers\Actions\DisableAccount;
use App\Http\Controllers\Actions\UploadVersion;
use App\Http\Controllers\Actions\UseReferralCode;
use App\Http\Controllers\CapesController;
use App\Http\Controllers\Coinbase\Actions\HandleCoinbaseWebhook;
use App\Http\Controllers\Coinbase\CoinbaseController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\LauncherController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PayPal\Actions\HandlePayPalWebhook;
use App\Http\Controllers\PayPal\PayPalController;
use App\Http\Controllers\Stripe\Actions\HandleStripeSourcesWebhook;
use App\Http\Controllers\Stripe\StripeSourcesController;
use App\Http\Controllers\Subscriptions\SubscriptionsController;
use App\Http\Livewire\ShowStripeSource;
use Illuminate\Support\Facades\Route;

/**
 * Home
 */
Route::group([], function () {
    Route::view('/', 'pages.index')->name('index');
    Route::get('dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
    Route::get('security', [PagesController::class, 'security'])->name('pages.security');
    Route::get('discord', [PagesController::class, 'discord'])->name('pages.discord');
    Route::get('subscriptions', [PagesController::class, 'subscriptions'])->name('pages.subscriptions');
    Route::view('terms', 'pages.terms')->name('pages.terms');
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
    Route::view('notifications', 'pages.dashboard.admin.notifications')
        ->name('admin.notifications');
    Route::get('sales', [PagesController::class, 'sales'])
        ->name('admin.sales');

    // upload version
    Route::post('versions', UploadVersion::class)
        ->name('admin.versions.upload');
});

/**
 * Subscriptions
 */
Route::prefix('subscriptions')->group(function () {
    Route::post('cancel', [SubscriptionsController::class, 'delete'])->name('subscriptions.cancel');
});

/**
 * Payments
 */
Route::prefix('paypal')->group(function () {
    Route::post('process', [PayPalController::class, 'process'])->name('paypal.process');
    Route::get('execute', [PayPalController::class, 'execute'])->name('paypal.execute');
    Route::get('cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

    Route::post('webhook', HandlePayPalWebhook::class);
});

/**
 * Users
 */
Route::prefix('user')->group(function () {
    Route::post('referral-code', UseReferralCode::class)->name('users.referral-code');
    Route::delete('disable', DisableAccount::class)->name('users.disable');
});

/**
 * Capes
 */
Route::prefix('cape')->group(function () {
    Route::post('/', [CapesController::class, 'store'])->name('capes.store');
    Route::delete('/', [CapesController::class, 'destroy'])->name('capes.delete');
});

/**
 * Launcher
 */
Route::prefix('launcher')->group(function () {
    Route::get('/download', [LauncherController::class, 'download'])->name('launcher.show');
    Route::post('/', [LauncherController::class, 'store'])->name('launcher.store');
});

/**
 * Connect
 */
Route::prefix('connect')->group(function () {
    Route::group(['prefix' => 'discord'], function () {
        Route::get('/', [DiscordController::class, 'login'])->name('connect.discord');
        Route::get('redirect', [DiscordController::class, 'redirect']);
    });
});

Route::prefix('stripe')->group(function () {
    Route::get('{id}', ShowStripeSource::class)->name('stripe.show');
    Route::post('/', [StripeSourcesController::class, 'store'])->name('stripe.store');

    Route::post('webhook', HandleStripeSourcesWebhook::class);
});

Route::prefix('coinbase')->group(function () {
    Route::get('cancel', [CoinbaseController::class, 'cancel'])->name('coinbase.cancel');
    Route::post('/', [CoinbaseController::class, 'store'])->name('coinbase.store');
    Route::post('webhook', HandleCoinbaseWebhook::class);
});

require __DIR__ . '/auth.php';
