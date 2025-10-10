<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\ReviewController;

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
    
    // Business endpoints (stubs)
    Route::apiResource('businesses', BusinessController::class)->only(['index', 'show']);

    // Review endpoints (stubs)
    Route::apiResource('reviews', ReviewController::class)->only(['index', 'show']);
});
