<?php

use App\Http\Controllers\Actions\DisableAccount;
use App\Http\Controllers\Actions\HandlePayPalWebhook;
use App\Http\Controllers\Actions\UploadVersion;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\SubscriptionsController;
use Illuminate\Support\Facades\Route;

/**
 * Home
 */
Route::group([], function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('home', [HomeController::class, 'home'])->name('home');
    Route::get('security', [HomeController::class, 'security'])->name('pages.security');
    Route::get('subscriptions', [HomeController::class, 'subscriptions'])->name('pages.subscriptions');
    Route::get('terms', [HomeController::class, 'terms'])->name('pages.terms');
});

/**
 * Admin
 */
Route::prefix('admin')->group(function () {

    // list users and versions
    Route::get('users', [HomeController::class, 'users'])->name('admin.users');
    Route::get('versions', [HomeController::class, 'versions'])->name('admin.versions');

    // upload version
    Route::post('versions', UploadVersion::class)->name('admin.versions.upload');
});

/**
 * Subscriptions
 */
Route::prefix('subscriptions')->group(function () {
    Route::post('free', [SubscriptionsController::class, 'free'])->name('subscriptions.free');
    Route::post('cancel', [SubscriptionsController::class, 'cancel'])->name('subscriptions.cancel');
});

/**
 * Payments
 */
Route::prefix('paypal')->group(function () {
    // admin
    Route::get('createBillingPlan', [PayPalController::class, 'createBillingPlan'])->name('paypal.createBillingPlan');

    Route::post('process', [PayPalController::class, 'process'])->name('paypal.process');
    Route::get('execute', [PayPalController::class, 'execute'])->name('paypal.execute');
    Route::get('cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

    Route::post('webhook', HandlePayPalWebhook::class);
});

/**
 *
 * Users
 */
Route::prefix('user')->group(function () {
    Route::delete('disable', DisableAccount::class)->name('user.disable');
});

require __DIR__ . '/auth.php';
