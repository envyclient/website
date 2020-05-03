<?php

/** Auth */

use App\User;
use Carbon\Carbon;

Route::post('auth/login', 'API\AuthController@login');
Route::get('auth/me', 'API\AuthController@me');

/** Configs */
Route::get('configs/search/{name}', 'API\ConfigsController@searchConfigByName');
Route::put('configs/favorite/{id}', 'API\ConfigsController@favorite');
Route::get('configs/user', 'API\ConfigsController@getCurrentUserConfigs');
Route::get('configs/user/{name}', 'API\ConfigsController@getConfigsByUser');
Route::resource('configs', 'API\ConfigsController')->only([
    'index', 'show', 'store', 'destroy'
]);

/** thingy */
Route::get('users/active', function () {
    // check if the user was active in the last 5 mins
    return User::whereDate('end_date', '>=', Carbon::now()->subtract(5, 'minutes'));
})->middleware('auth:api');
