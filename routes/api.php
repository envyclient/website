<?php

use App\Http\Controllers\API\Actions\GetLauncherVersion;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Configs\Actions\FavoriteConfig;
use App\Http\Controllers\API\Configs\Actions\GetConfigsForCurrentUser;
use App\Http\Controllers\API\Configs\Actions\GetConfigsForUser;
use App\Http\Controllers\API\Configs\ConfigsController;
use App\Http\Controllers\API\MinecraftController;
use App\Http\Controllers\API\NotificationsController;
use App\Http\Controllers\API\VersionsController;
use Illuminate\Support\Facades\Route;

/**
 * Auth
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::get('me', [AuthController::class, 'me']);
});

/**
 * Configs
 */
Route::group([], function () {

    Route::group(['prefix' => 'configs'], function () {
        Route::get('user', GetConfigsForCurrentUser::class);
        Route::get('user/{name}', GetConfigsForUser::class);
        Route::put('{config}/favorite', FavoriteConfig::class);
    });

    Route::resource('configs', ConfigsController::class);
});

/**
 * Versions
 */
Route::group(['prefix' => 'versions'], function () {
    Route::get('/', [VersionsController::class, 'index']);
    Route::get('{version}/download-version', [VersionsController::class, 'downloadVersion']);
    Route::get('{version}/download-assets', [VersionsController::class, 'downloadAssets']);
});

/**
 * Minecraft
 */
Route::group(['prefix' => 'minecraft'], function () {
    Route::get('{uuid}', [MinecraftController::class, 'show']);
    Route::post('/', [MinecraftController::class, 'store']);
    Route::delete('/', [MinecraftController::class, 'destroy']);
});

/**
 * Notifications
 */
Route::group(['prefix' => 'notifications'], function () {
    Route::get('/', [NotificationsController::class, 'index']);
    Route::patch('{id}', [NotificationsController::class, 'update']);
});

/**
 * Launcher
 */
Route::get('launcher/latest', GetLauncherVersion::class);

