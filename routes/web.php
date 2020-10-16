<?php

use App\Http\Controllers\API\HandlePayPalWebhook;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/**
 * Home
 */
Route::group([], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('security', [HomeController::class, 'security'])->name('pages.security');
    Route::get('subscriptions', [HomeController::class, 'subscriptions'])->name('pages.subscriptions');
    Route::get('terms', [HomeController::class, 'terms'])->name('pages.terms');
});

/**
 * Admin
 */
Route::prefix('admin')->group(function () {
    Route::get('users', [HomeController::class, 'users'])->name('admin.users');
    Route::get('versions', [HomeController::class, 'versions'])->name('admin.versions');
    Route::get('sessions', [HomeController::class, 'sessions'])->name('admin.sessions');
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
    Route::delete('disable', [UsersController::class, 'disable'])->name('user.disable');
});
