<?php

use App\Http\Controllers\API\Actions\HandleDiscordWebhook;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Configs\Actions\FavoriteConfig;
use App\Http\Controllers\API\Configs\Actions\GetConfigsForUser;
use App\Http\Controllers\API\Configs\ConfigsController;
use App\Http\Controllers\API\MinecraftController;
use App\Http\Controllers\API\VersionsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

Route::supportBubble();

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
Route::group(['middleware' => ['auth:api', 'subscribed']], function () {
    Route::group(['prefix' => 'configs'], function () {
        Route::get('user/{name?}', GetConfigsForUser::class);
        Route::put('{config}/favorite', FavoriteConfig::class);
    });

    Route::resource('configs', ConfigsController::class);
});

/**
 * Versions
 */
Route::group(['prefix' => 'versions', 'middleware' => ['auth:api', 'subscribed']], function () {
    Route::get('/', [VersionsController::class, 'index']);
    Route::get('{version}', [VersionsController::class, 'show']);
});

/**
 * Minecraft
 */
Route::group(['prefix' => 'minecraft', 'middleware' => ['auth:api', 'subscribed']], function () {
    Route::get('{uuid}', [MinecraftController::class, 'show']);
    Route::post('/', [MinecraftController::class, 'store']);
    Route::delete('/', [MinecraftController::class, 'destroy']);
});

/**
 * Download Loader
 */
Route::get('download-loader', fn() => Storage::download('loader.exe'))
    ->middleware(['auth:api', 'subscribed']);

/**
 * Handle Discord webhook from bot
 */
Route::post('discord/webhook', HandleDiscordWebhook::class);

/**
 * Ban User
 */
Route::post('user/ban', function (Request $request) {

    // ban the user
    $request->user()->update([
        'banned' => true,
    ]);

    return response()->noContent();
})->middleware(['auth:api', 'admin']);
