<?php

use App\Http\Controllers\Actions\DisableAccount;
use App\Http\Controllers\Actions\DownloadLauncher;
use App\Http\Controllers\Actions\UploadVersion;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PayPal\Actions\HandlePayPalWebhook;
use App\Http\Controllers\PayPal\PayPalController;
use App\Http\Controllers\Subscriptions\Actions\SubscribeToFreePlan;
use App\Http\Controllers\Subscriptions\SubscriptionsController;
use Illuminate\Support\Facades\Route;

/**
 * Home
 */
Route::group([], function () {
    Route::get('/', [PagesController::class, 'index'])->name('index');
    Route::get('dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
    Route::get('security', [PagesController::class, 'security'])->name('pages.security');
    Route::get('subscriptions', [PagesController::class, 'subscriptions'])->name('pages.subscriptions');
    Route::get('terms', [PagesController::class, 'terms'])->name('pages.terms');
});

/**
 * Admin
 */
Route::prefix('admin')->group(function () {

    // list users and versions
    Route::get('users', [PagesController::class, 'users'])->name('admin.users');
    Route::get('versions', [PagesController::class, 'versions'])->name('admin.versions');

    // upload version
    Route::post('versions', UploadVersion::class)->name('admin.versions.upload');
});

/**
 * Subscriptions
 */
Route::prefix('subscriptions')->group(function () {
    Route::post('free', SubscribeToFreePlan::class)->name('subscriptions.free');
    Route::post('cancel', [SubscriptionsController::class, 'delete'])->name('subscriptions.cancel');
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

/**
 * Extra
 */
Route::get('launcher', DownloadLauncher::class)->name('download-launcher');

require __DIR__ . '/auth.php';
