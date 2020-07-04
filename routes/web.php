<?php

Auth::routes(['verify' => true]);

/**
 * Home
 */
Route::get('/', 'HomeController@index')->name('home');
Route::get('terms', 'HomeController@terms')->name('terms');
Route::get('admin', 'HomeController@admin')->name('admin');

/**
 * Subscriptions
 */
Route::prefix('subscriptions')->group(function () {
    Route::post('free', 'SubscriptionsController@free')->name('subscriptions.free');
    Route::post('cancel', 'SubscriptionsController@cancel')->name('subscriptions.cancel');
});

/**
 * Payments
 */
Route::prefix('paypal')->group(function () {
    // admin
    Route::get('createBillingPlan', 'PayPalController@createBillingPlan')->name('paypal.createBillingPlan');

    Route::post('process', 'PayPalController@process')->name('paypal.process');
    Route::get('execute', 'PayPalController@execute')->name('paypal.execute');
    Route::get('cancel', 'PayPalController@cancel')->name('paypal.cancel');
});

/**
 *
 * Users
 */
Route::prefix('user')->group(function () {
    Route::put('update/password', 'UsersController@updatePassword')->name('user.update-password');
    Route::delete('disable', 'UsersController@disable')->name('user.disable');
});

/**
 * Versions
 */
Route::prefix('versions')->group(function () {
    Route::post('/', 'VersionsController@store')->name('versions.store');
    Route::get('launcher', 'VersionsController@downloadLauncher')->name('versions.launcher');
});
