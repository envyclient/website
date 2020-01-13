<?php

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');


// TODO: change routes
Route::get('paypal/ec-checkout', 'PayPalController@getExpressCheckout');
Route::get('paypal/ec-checkout-success', 'PayPalController@getExpressCheckoutSuccess');
