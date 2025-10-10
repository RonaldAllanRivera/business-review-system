<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the framework and grouped under the "/api" prefix.
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/health', [HealthController::class, 'index']);

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    // Public read endpoints
    Route::apiResource('businesses', BusinessController::class)->only(['index', 'show']);
    Route::apiResource('reviews', ReviewController::class)->only(['index', 'show']);

    // Protected write endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('businesses', BusinessController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('reviews', ReviewController::class)->only(['store', 'update', 'destroy']);
    });
});
