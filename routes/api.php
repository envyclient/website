<?php

/** Auth */
Route::post('auth/login', 'API\AuthController@login');
Route::get('auth/me', 'API\AuthController@me');

/** Configs */
Route::put('configs/favorite', 'API\ConfigsController@favorite');
Route::get('user/configs', 'API\ConfigsController@getCurrentUserConfigs');
Route::get('configs/user/{name}', 'API\ConfigsController@getConfigsByUser');
Route::resource('configs', 'API\ConfigsController')->only([
    'index', 'show', 'store', 'destroy'
]);
