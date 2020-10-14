<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChartsController;
use App\Http\Controllers\API\ConfigsController;
use App\Http\Controllers\API\DownloadAssets;
use App\Http\Controllers\API\GameSessionsController;
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
    Route::put('favorite/{config}', [ConfigsController::class, 'favorite']);

    Route::get('user', [ConfigsController::class, 'getCurrentUserConfigs']);
    Route::get('user/{name}', [ConfigsController::class, 'getConfigsByUser']);

    Route::get('/', [ConfigsController::class, 'index']);
    Route::get('{config}', [ConfigsController::class, 'show']);
    Route::post('/', [ConfigsController::class, 'store']);
    Route::put('{config}', [ConfigsController::class, 'update']);
    Route::delete('{config}', [ConfigsController::class, 'destroy']);
});

/**
 * Charts
 */
Route::prefix('charts')->group(function () {
    Route::get('users', [ChartsController::class, 'users'])->name('api.charts.users');
    Route::get('versions', [ChartsController::class, 'versions'])->name('api.charts.versions');

    Route::prefix('sessions')->group(function () {
        Route::get('onTime', [ChartsController::class, 'onTime'])->name('api.charts.sessions.ontime');
        Route::get('toggles', [ChartsController::class, 'toggles'])->name('api.charts.sessions.toggles');
    });
});

/**
 * Versions
 */
Route::prefix('versions')->group(function () {
    Route::get('/', [VersionsController::class, 'index'])->name('api.versions.index');
    Route::get('{version}', [VersionsController::class, 'show'])->name('api.versions.show');
});

/**
 * Game Sessions
 */
Route::prefix('sessions')->group(function () {
    Route::post('/', [GameSessionsController::class, 'store']);
    Route::put('{session}', [GameSessionsController::class, 'update']);
});

/**
 * Extra
 */
Route::get('assets', DownloadAssets::class);
