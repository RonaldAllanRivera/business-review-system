<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewModerationController;

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
        // Profile
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar']);

        Route::apiResource('businesses', BusinessController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('reviews', ReviewController::class)->only(['store', 'update', 'destroy']);

        // Review moderation (requires permission: review.moderate)
        Route::middleware('permission:review.moderate')->group(function () {
            Route::get('/reviews/moderation/pending', [ReviewModerationController::class, 'indexPending']);
            Route::get('/reviews/moderation/all', [ReviewModerationController::class, 'indexAll']);
            Route::post('/reviews/{review}/approve', [ReviewModerationController::class, 'approve']);
            Route::post('/reviews/{review}/reject', [ReviewModerationController::class, 'reject']);
        });
    });
});
