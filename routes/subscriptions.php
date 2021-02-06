<?php

use App\Http\Controllers\Coinbase\Actions\HandleCoinbaseWebhook;
use App\Http\Controllers\Coinbase\CoinbaseController;
use App\Http\Controllers\PayPal\Actions\HandlePayPalWebhook;
use App\Http\Controllers\PayPal\PayPalController;
use App\Http\Controllers\Stripe\Actions\CreateStripeSource;
use App\Http\Controllers\Stripe\Actions\HandleStripeWebhook;
use App\Http\Controllers\Stripe\StripeController;
use App\Http\Controllers\Subscriptions\SubscriptionsController;
use App\Http\Livewire\ShowStripeSource;

/**
 * Subscriptions
 */
Route::group(['prefix' => 'subscriptions'], function () {
    Route::post('cancel', [SubscriptionsController::class, 'cancel'])
        ->name('subscriptions.cancel');
});

/**
 * Paypal
 */
Route::group(['prefix' => 'paypal'], function () {
    Route::post('process', [PayPalController::class, 'process'])
        ->name('paypal.process');

    Route::get('execute', [PayPalController::class, 'execute']);
    Route::get('cancel', [PayPalController::class, 'cancel']);

    Route::post('webhook', HandlePayPalWebhook::class);
});

/**
 * Stripe Checkout & Stripe Source
 */
Route::group([], function () {
    Route::group(['prefix' => 'stripe'], function () {
        Route::post('checkout', [StripeController::class, 'checkout'])
            ->name('stripe.checkout');

        Route::get('success', [StripeController::class, 'success'])
            ->name('stripe.success');

        Route::get('cancel', [StripeController::class, 'cancel'])
            ->name('stripe.cancel');

        Route::post('webhook', HandleStripeWebhook::class);
    });

    Route::group(['prefix' => 'stripe-source'], function () {
        Route::get('{id}', ShowStripeSource::class)
            ->name('stripe-source.show');

        Route::post('/', CreateStripeSource::class)
            ->name('stripe-source.store');
    });
});

/**
 * Coinbase
 */
Route::group(['prefix' => 'coinbase'], function () {
    Route::get('cancel', [CoinbaseController::class, 'cancel'])->name('coinbase.cancel');
    Route::post('/', [CoinbaseController::class, 'store'])->name('coinbase.store');
    Route::post('webhook', HandleCoinbaseWebhook::class);
});
