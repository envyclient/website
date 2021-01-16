<?php

use App\Http\Controllers\API\Actions\LatestLauncher;
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
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::get('me', [AuthController::class, 'me']);
});

/**
 * Configs
 */
Route::prefix('configs')->group(function () {

    Route::get('user', GetConfigsForCurrentUser::class);
    Route::get('user/{name}', GetConfigsForUser::class);

    Route::get('/', [ConfigsController::class, 'index']);
    Route::get('{config}', [ConfigsController::class, 'show']);
    Route::post('/', [ConfigsController::class, 'store']);
    Route::put('{config}', [ConfigsController::class, 'update']);
    Route::put('{config}/favorite', FavoriteConfig::class);
    Route::delete('{config}', [ConfigsController::class, 'destroy']);
});

/**
 * Versions
 */
Route::prefix('versions')->group(function () {
    Route::get('/', [VersionsController::class, 'index']);
    Route::get('{version}/download-version', [VersionsController::class, 'downloadVersion']);
    Route::get('{version}/download-assets', [VersionsController::class, 'downloadAssets']);
});

/**
 * Minecraft
 */
Route::prefix('minecraft')->group(function () {
    Route::get('{uuid}', [MinecraftController::class, 'show']);
    Route::post('/', [MinecraftController::class, 'store']);
    Route::delete('/', [MinecraftController::class, 'destroy']);
});

/**
 * Notifications
 */
Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationsController::class, 'index']);
    Route::patch('{id}', [NotificationsController::class, 'update']);
});

/**
 * Launcher
 */
Route::get('launcher/latest', LatestLauncher::class);

