<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\GameController;
use App\Http\Controllers\api\RatingController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);

    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/{slug}', [GameController::class, 'show']);
    Route::get('/best-games', [GameController::class, 'showTopGames']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::delete('/categories/{slug}', [CategoryController::class, 'destroy']);

        Route::post('/games', [GameController::class, 'store']);
        Route::delete('/games/{slug}', [GameController::class, 'destroy']);
        Route::post('/games/{slug}/rating', [RatingController::class, 'store']);
        Route::post('/games/{slug}/comment', [CommentController::class, 'store']);
    });
});
