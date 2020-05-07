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
Route::post('subscriptions/subscribe', 'SubscriptionsController@subscribe')->name('subscriptions.subscribe');
Route::post('subscriptions/cancel', 'SubscriptionsController@cancel')->name('subscriptions.cancel');

/**
 * Payments
 */
Route::post('paypal/create', 'PayPalController@create')->name('paypal.create');
Route::get('paypal/success', 'PayPalController@success')->name('paypal.success');
Route::get('paypal/cancel', 'PayPalController@cancel')->name('paypal.cancel');

/**
 * Users
 */
Route::put('user/update/password', 'UsersController@updatePassword');
Route::delete('user/{user}', 'UsersController@destroy');
