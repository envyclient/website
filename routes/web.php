<?php

Auth::routes(['verify' => true]);

/**
 * Home
 */
Route::get('/', 'HomeController@index')->name('home');
Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');
Route::get('admin', 'HomeController@admin')->name('dashboard.admin');

/**
 * Subscriptions
 */
Route::prefix('paypal')->group(function () {
    Route::post('subscribe', 'SubscriptionsController@subscribe')->name('subscriptions.subscribe');
    Route::post('cancel', 'SubscriptionsController@cancel')->name('subscriptions.cancel');
});

/**
 * Payments
 */
Route::prefix('paypal')->group(function () {
    Route::post('create', 'PayPalController@create')->name('paypal.create');
    Route::get('success', 'PayPalController@success')->name('paypal.success');
    Route::get('cancel', 'PayPalController@cancel')->name('paypal.cancel');
});

/**
 * Users
 */
Route::prefix('user')->group(function () {
    Route::put('update/password', 'UsersController@updatePassword')->name('user.update-password');
    Route::delete('{user}', 'UsersController@disable')->name('user.disable');
});

/**
 * Downloads
 */
Route::prefix('downloads')->group(function () {
    Route::get('/create', 'DownloadsController@create')->name('downloads.create');
    Route::post('/', 'DownloadsController@store')->name('downloads.store');
});
