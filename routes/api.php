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

/** thingy */
Route::get('users/active', function () {
    // TODO: fix
    // check if the user was active in the last 5 mins
    return User::whereDate('end_date', '>=', Carbon::now()->subtract(5, 'minutes'));
})->middleware('auth:api');

/**
 * Admin
 */
Route::prefix('admin')->group(function () {
    Route::get('users', 'API\AdminController@users')->name('api.admin.users');
    Route::get('users-stats', 'API\AdminController@usersStats')->name('api.admin.users.stats');

    Route::put('credits', 'API\AdminController@credits')->name('api.admin.users.credits');

    Route::get('transactions', 'API\AdminController@transactions')->name('api.admin.transactions');
    Route::get('transaction-stats', 'API\AdminController@transactionsStats')->name('api.admin.transactions.stats');

    Route::put('ban', 'API\AdminController@ban')->name('api.admin.users.ban');
    Route::put('un-ban', 'API\AdminController@unBan')->name('api.admin.users.unban');
});

/**
 * Download
 */
Route::prefix('downloads')->group(function () {
    Route::get('/', 'API\DownloadsController@index')->name('api.downloads.index');
    Route::get('{download}', 'API\DownloadsController@show')->name('api.downloads.show');
});
