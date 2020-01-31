<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('api/configs/user/{name}', 'ConfigsController@getConfigsByUser');
Route::resource('configs', 'ConfigsController')->only([
    'index', 'show', 'store'
]);
