<?php

/**
 * Auth
 */

use Illuminate\Support\Facades\Storage;

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
    Route::get('users/chart', 'API\AdminController@usersChart')->name('api.admin.users.chart');

    Route::put('users/ban/{user}', 'API\AdminController@ban')->name('api.admin.users.ban');
    Route::put('users/credits/{user}', 'API\AdminController@credits')->name('api.admin.users.credits');

    Route::get('transactions', 'API\AdminController@transactions')->name('api.admin.transactions');
    Route::get('transactions/chart', 'API\AdminController@transactionsChart')->name('api.admin.transactions.chart');

    Route::get('versions/chart', 'API\AdminController@versionDownloadsChart')->name('api.admin.versions.chart');
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
 * Download a cape
 */
Route::middleware('auth:api')->get('capes/{cape}', function ($cape) {
    return Storage::disk('minio')->download('capes/' . $cape . '.png');
})->name('capes');
