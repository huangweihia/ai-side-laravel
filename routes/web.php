<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 首页
Route::get('/', [HomeController::class, 'index'])->name('home');

// 认证路由
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// 登出
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// 需要登录的路由
Route::middleware('auth')->group(function () {
    // 个人中心
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    // 项目列表
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
    
    // 文章列表
    Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
});
