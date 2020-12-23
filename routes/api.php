<?php

use App\Http\Controllers\Actions\DownloadAssets;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChartsController;
use App\Http\Controllers\API\Configs\Actions\FavoriteConfig;
use App\Http\Controllers\API\Configs\Actions\GetConfigsForCurrentUser;
use App\Http\Controllers\API\Configs\Actions\GetConfigsForUser;
use App\Http\Controllers\API\Configs\ConfigsController;
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
    Route::put('favorite/{config}', FavoriteConfig::class);

    Route::get('user', GetConfigsForCurrentUser::class);
    Route::get('user/{name}', GetConfigsForUser::class);

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
 * Extra
 */
Route::get('assets', DownloadAssets::class);
