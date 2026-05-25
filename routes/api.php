<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\CacheController;
use App\Http\Controllers\Api\V1\SchemaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public API endpoints for frontend
Route::prefix('v1')->group(function () {
    // Settings & Content
    Route::get('/hero-slides', [ApiController::class, 'heroSlides']);
    Route::get('/did-you-know', [ApiController::class, 'didYouKnowFacts']);
    Route::get('/settings', [ApiController::class, 'settings']);
    Route::get('/timeline', [ApiController::class, 'timeline']);
    Route::get('/batches', [ApiController::class, 'batches']);
    Route::get('/organization', [ApiController::class, 'organization']);

    // Schema endpoints (BDUI)
    Route::prefix('schema')->group(function () {
        Route::get('/enums/{name}', [SchemaController::class, 'enums']);
        Route::get('/forms/{name}', [SchemaController::class, 'forms']);
        Route::get('/tables/{name}', [SchemaController::class, 'tables']);
        Route::get('/navigation', [SchemaController::class, 'navigation']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/permissions', [SchemaController::class, 'permissions']);
        });
    });

    // Cache versioning endpoints (for real-time sync)
    Route::prefix('cache')->group(function () {
        Route::get('/versions', [CacheController::class, 'versions']);
        Route::get('/version/{resource}', [CacheController::class, 'version']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/invalidate', [CacheController::class, 'invalidate']);
        });
    });

    // Activities (Kegiatan)
    Route::get('/activities', [ApiController::class, 'activities']);
    Route::get('/activities/{slug}', [ApiController::class, 'activityDetail']);
    Route::get('/activities/{slug}/related', [ApiController::class, 'activityRelated']);

    // Albums (Gallery)
    Route::get('/albums', [ApiController::class, 'albums']);
    Route::get('/albums/{slug}', [ApiController::class, 'albumDetail']);
    Route::get('/albums/{slug}/others', [ApiController::class, 'albumsOther']);

    // FAQ
    Route::get('/faqs', [ApiController::class, 'faqs']);

    // Suggestions
    Route::post('/suggestions', [ApiController::class, 'suggestion']);

    // Newsletter
    Route::post('/newsletter/subscribe', [ApiController::class, 'newsletterSubscribe']);

    // Contact
    Route::post('/contact', [ApiController::class, 'contact']);

    // Auth routes
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/user', [AuthController::class, 'user']);
    });
});
