<?php

Auth::routes(['verify' => true]);

/**
 * Home
 */
Route::get('/', 'HomeController@dashboard')->name('home');
Route::get('home', 'HomeController@dashboard')->name('dashboard');

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
