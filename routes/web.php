<?php

Auth::routes(['verify' => true]);

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');


/**
 * Payments
 */
Route::post('paypal/create', 'PayPalController@create')->name('paypal.create');
Route::get('paypal/success', 'PayPalController@success')->name('paypal.success');
Route::get('paypal/cancel', 'PayPalController@cancel')->name('paypal.cancel');
