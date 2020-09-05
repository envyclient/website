<?php

/**
 * Auth
 */

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', 'API\AuthController@login');
    Route::get('me', 'API\AuthController@me');
});

/**
 * Configs
 */
Route::prefix('configs')->group(function () {
    Route::put('favorite/{config}', 'API\ConfigsController@favorite');

    Route::get('user', 'API\ConfigsController@getCurrentUserConfigs');
    Route::get('user/{name}', 'API\ConfigsController@getConfigsByUser');

    Route::get('/', 'API\ConfigsController@index');
    Route::get('{config}', 'API\ConfigsController@show');
    Route::post('/', 'API\ConfigsController@store');
    Route::put('{config}', 'API\ConfigsController@update');
    Route::delete('{config}', 'API\ConfigsController@destroy');
});

/**
 * Admin
 */
Route::prefix('admin')->group(function () {
    Route::get('transactions', 'API\AdminController@transactions')->name('api.admin.transactions');
    Route::get('users', 'API\AdminController@users')->name('api.admin.users');
    Route::put('users/ban/{user}', 'API\AdminController@ban')->name('api.admin.users.ban');
});

/**
 * Charts
 */
Route::prefix('charts')->group(function () {
    Route::get('users', 'API\ChartsController@users')->name('api.charts.users');
    Route::get('versions', 'API\ChartsController@versions')->name('api.charts.versions');

    Route::prefix('sessions')->group(function () {
        Route::get('onTime', 'API\ChartsController@ontime')->name('api.charts.sessions.ontime');
        Route::get('toggles', 'API\ChartsController@toggles')->name('api.charts.sessions.toggles');
    });
});

/**
 * Versions
 */
Route::prefix('versions')->group(function () {
    Route::get('/', 'API\VersionsController@index')->name('api.versions.index');
    Route::get('{version}', 'API\VersionsController@show')->name('api.versions.show');
    Route::delete('{version}', 'API\VersionsController@destroy')->name('api.versions.delete');
});

/**
 * Game Sessions
 */
Route::prefix('sessions')->group(function () {
    Route::post('/', 'API\GameSessionsController@store');
    Route::put('{session}', 'API\GameSessionsController@update');
});

/**
 * Extra
 */
Route::get('assets', 'API\VersionsController@assets');
Route::post('paypal', 'API\HandlePayPalWebhook');
