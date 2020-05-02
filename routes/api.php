<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'API\AuthController@login');
    Route::post('logout', 'API\AuthController@logout');
    Route::post('refresh', 'API\AuthController@refresh');
    Route::post('me', 'API\AuthController@me');
});

Route::put('configs/favorite', 'API\ConfigsController@favorite');
Route::get('user/configs', 'API\ConfigsController@getCurrentUserConfigs');
Route::get('configs/user/{name}', 'API\ConfigsController@getConfigsByUser');
Route::resource('configs', 'API\ConfigsController')->only([
    'index', 'show', 'store', 'destroy'
]);
