<?php

Route::get('api/configs/user/{name}', 'ConfigsController@getConfigsByUser');
Route::resource('configs', 'ConfigsController')->only([
    'index', 'show', 'store'
]);
