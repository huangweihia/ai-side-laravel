<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleInteractionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 文章互动（需要登录）
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/articles/{article}/like', [ArticleInteractionController::class, 'toggleLike']);
    Route::post('/articles/{article}/favorite', [ArticleInteractionController::class, 'toggleFavorite']);
    Route::post('/articles/{article}/unlock', [ArticleInteractionController::class, 'unlockArticle']);
});
