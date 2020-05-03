<?php

/** Auth */
Route::post('auth/login', 'API\AuthController@login');
Route::get('auth/me', 'API\AuthController@me');

/** Configs */
Route::get('configs/search/{name}', 'API\ConfigsController@searchConfigByName');
Route::put('configs/favorite', 'API\ConfigsController@favorite');
Route::get('configs/user', 'API\ConfigsController@getCurrentUserConfigs');
Route::get('configs/user/{name}', 'API\ConfigsController@getConfigsByUser');
Route::resource('configs', 'API\ConfigsController')->only([
    'index', 'show', 'store', 'destroy'
]);
