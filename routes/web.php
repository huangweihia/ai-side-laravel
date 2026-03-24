<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SubscriptionController;

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
    
    // 订阅偏好设置
    Route::get('subscriptions/preferences', [SubscriptionController::class, 'preferences'])->name('subscriptions.preferences');
    Route::post('subscriptions/preferences', [SubscriptionController::class, 'updatePreferences'])->name('subscriptions.update');
});

// 公开订阅路由（退订等）
Route::get('unsubscribe/{token}', [SubscriptionController::class, 'showUnsubscribe'])->name('unsubscribe.show');
Route::post('unsubscribe/{token}', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe.confirm');
Route::get('resubscribe/{token}', [SubscriptionController::class, 'resubscribe'])->name('resubscribe');

// 邮件管理导出
Route::get('admin/email-manager/export', function() {
    $recipients = \App\Models\EmailSetting::getRecipients();
    $content = implode("\n", $recipients);
    return response()->streamDownload(function () use ($content) {
        echo $content;
    }, 'email-recipients-' . now()->format('Y-m-d') . '.txt', [
        'Content-Type' => 'text/plain',
    ]);
})->middleware('auth');
