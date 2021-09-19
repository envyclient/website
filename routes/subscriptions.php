<?php

use App\Http\Controllers\Actions\CancelSubscription;
use App\Http\Controllers\PayPal\Actions\HandlePayPalWebhook;
use App\Http\Controllers\PayPal\PayPalController;
use App\Http\Controllers\Stripe\Actions\CreateStripeSource;
use App\Http\Controllers\Stripe\Actions\HandleStripeWebhook;
use App\Http\Controllers\Stripe\StripeController;
use App\Http\Livewire\ShowStripeSource;
use App\Http\Middleware\Custom\VerifyPaypalWebhookSignature;
use App\Http\Middleware\Custom\VerifyStripeWebhookSignature;
use Illuminate\Support\Facades\Route;

/**
 * Cancel Subscription
 */
Route::post('cancel', CancelSubscription::class)
    ->prefix('subscriptions')
    ->middleware(['auth', 'verified', 'subscribed'])
    ->name('subscriptions.cancel');

/**
 * Paypal
 */
Route::group(['prefix' => 'paypal'], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::post('process', [PayPalController::class, 'checkout'])->name('paypal.checkout');
        Route::get('success', [PayPalController::class, 'success'])->name('paypal.success');
        Route::get('cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
    });

    Route::post('webhook', HandlePayPalWebhook::class)
        ->middleware(VerifyPaypalWebhookSignature::class);
});

/**
 * Stripe Checkout
 */
Route::group(['prefix' => 'stripe'], function () {
    Route::post('checkout', [StripeController::class, 'checkout'])->name('stripe.checkout');
    Route::get('success', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
    Route::post('webhook', HandleStripeWebhook::class)->middleware(VerifyStripeWebhookSignature::class);
});

/**
 * Stripe Source
 */
Route::group(['prefix' => 'stripe-source'], function () {
    Route::get('{id}', ShowStripeSource::class)->name('stripe-source.show');
    Route::post('/', CreateStripeSource::class)->name('stripe-source.store');
});
