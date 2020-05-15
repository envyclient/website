<?php

use App\User;
use Carbon\Carbon;

/**
 * Auth
 */
Route::prefix('auth')->group(function () {
    Route::post('login', 'API\AuthController@login')->name('api.auth.login');
    Route::get('me', 'API\AuthController@me')->name('api.auth.me');
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
    Route::delete('{config}', 'API\ConfigsController@destroy');
});

/**
 * Admin
 */
Route::prefix('admin')->group(function () {
    Route::get('users', 'API\AdminController@users')->name('api.admin.users');
    Route::get('users/stats', 'API\AdminController@usersStats')->name('api.admin.users.stats');
    
    Route::put('users/ban/{user}', 'API\AdminController@ban')->name('api.admin.users.ban');
    Route::put('users/credits/{user}', 'API\AdminController@credits')->name('api.admin.users.credits');

    Route::get('transactions', 'API\AdminController@transactions')->name('api.admin.transactions');
    Route::get('transactions/stats', 'API\AdminController@transactionsStats')->name('api.admin.transactions.stats');
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
 * Get all active users
 */
Route::get('users/active', function () {
    return User::where('last_launch_at', '>=', Carbon::now()->subtract(5, 'minutes'))->pluck('last_launch_user');
});
