<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HistoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 首页
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('users/{id}', [HomeController::class, 'userProfile'])->name('users.show');

// 自检页（登录后可用）
Route::get('self-check', function () {
    $hasSubmissionsTable = \Illuminate\Support\Facades\Schema::hasTable('content_submissions');
    $hasCommentLikesTable = \Illuminate\Support\Facades\Schema::hasTable('comment_likes');

    return response()->json([
        'ok' => true,
        'env' => app()->environment(),
        'db_default' => config('database.default'),
        'current_user' => auth()->user()?->only(['id', 'name', 'email', 'role']),
        'routes' => [
            'submissions.index' => \Illuminate\Support\Facades\Route::has('submissions.index'),
            'submissions.create' => \Illuminate\Support\Facades\Route::has('submissions.create'),
            'submissions.store' => \Illuminate\Support\Facades\Route::has('submissions.store'),
        ],
        'tables' => [
            'content_submissions' => $hasSubmissionsTable,
            'comment_likes' => $hasCommentLikesTable,
        ],
        'time' => now()->toDateTimeString(),
    ]);
})->middleware('auth')->name('self-check');

// 公开页面
Route::get('vip', function() {
    return view('vip');
})->name('vip');

// 知识库（暂时占位）
Route::get('knowledge', [\App\Http\Controllers\KnowledgeController::class, 'index'])->name('knowledge.index');

// 职位
Route::get('jobs', [\App\Http\Controllers\JobController::class, 'index'])->name('jobs.index');
Route::get('jobs/{id}', [\App\Http\Controllers\JobController::class, 'show'])->name('jobs.show');
Route::post('jobs/{id}/apply', [\App\Http\Controllers\JobController::class, 'apply'])->name('jobs.apply');
Route::post('jobs/{id}/comments', [\App\Http\Controllers\JobController::class, 'storeComment'])->name('jobs.comments.store');

Route::get('about', function() {
    return view('about');
})->name('about');

Route::get('contact', function() {
    return view('contact');
})->name('contact');

Route::get('privacy', function() {
    return view('privacy');
})->name('privacy');

Route::get('learning', function() {
    return view('articles.index');
})->name('learning');

Route::get('tools', function() {
    return view('projects.index');
})->name('tools');

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
    // VIP 投稿
    Route::middleware('vip')->group(function () {
        Route::get('submissions', [\App\Http\Controllers\SubmissionController::class, 'index'])->name('submissions.index');
        Route::get('submissions/create', [\App\Http\Controllers\SubmissionController::class, 'create'])->name('submissions.create');
        Route::post('submissions', [\App\Http\Controllers\SubmissionController::class, 'store'])->name('submissions.store');
        Route::get('submissions/{id}', [\App\Http\Controllers\SubmissionController::class, 'show'])->name('submissions.show');
        Route::get('submissions/{id}/edit', [\App\Http\Controllers\SubmissionController::class, 'edit'])->name('submissions.edit');
        Route::put('submissions/{id}', [\App\Http\Controllers\SubmissionController::class, 'update'])->name('submissions.update');
        
        // 图片上传（Trix 编辑器）
        Route::post('admin/upload-image', [\App\Http\Controllers\SubmissionController::class, 'uploadImage'])->name('admin.upload-image');
    });
    // 个人中心
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::post('profile/upload-avatar', [HomeController::class, 'uploadAvatar'])->name('profile.upload-avatar');
    
    // 项目列表（公开）
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
    
    // 项目收藏/取消收藏（供前端 projects.show 调用）
    Route::post('projects/{id}/favorite', [ProjectController::class, 'toggleFavorite'])->name('projects.favorite');

    // 项目发表评论（供前端 projects.show 调用）
    Route::post('projects/{id}/comments', [ProjectController::class, 'storeComment'])->name('projects.comments.store');
    
    // 文章列表（公开）
    Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
    
    // 文章互动
    Route::post('articles/{id}/favorite', [ArticleController::class, 'toggleFavorite'])->name('articles.favorite');
    Route::post('articles/{id}/like', [ArticleController::class, 'toggleLike'])->name('articles.like');
    Route::post('articles/{id}/comments', [ArticleController::class, 'storeComment'])->name('articles.comments.store');
    
    // 订阅偏好设置
    Route::get('subscriptions/preferences', [SubscriptionController::class, 'preferences'])->name('subscriptions.preferences');
    Route::post('subscriptions/preferences', [SubscriptionController::class, 'updatePreferences'])->name('subscriptions.update');
    
    // VIP 专属内容
    Route::middleware('vip')->group(function () {
        Route::get('vip/articles', [ArticleController::class, 'vipArticles'])->name('articles.vip');
        Route::get('vip/projects', [ProjectController::class, 'vipProjects'])->name('projects.vip');
    });
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

// 知识库路由
Route::prefix('knowledge')->group(function () {
    Route::get('/', [KnowledgeController::class, 'index'])->name('knowledge.index');
    Route::get('/search', [KnowledgeController::class, 'search'])->name('knowledge.search');
    Route::get('/{knowledgeBase}', [KnowledgeController::class, 'show'])->name('knowledge.show');
    Route::get('/doc/{document}', [KnowledgeController::class, 'showDocument'])->name('knowledge.documents.show');
Route::post('/doc/{document}/comments', [KnowledgeController::class, 'storeComment'])->name('knowledge.documents.comments.store');
});

// 互动功能路由（需要登录）
Route::middleware('auth')->group(function () {
    // 点赞/收藏（注意：评论点赞路由必须放在通配路由前面）
    Route::post('interactions/comments/{id}/like', [InteractionController::class, 'likeComment'])->name('interactions.comments.like');
    Route::post('interactions/{type}/{id}/like', [InteractionController::class, 'like'])->name('interactions.like');
    Route::post('interactions/{type}/{id}/favorite', [InteractionController::class, 'favorite'])->name('interactions.favorite');
    
    // 积分解锁
    Route::post('interactions/articles/{id}/unlock', [InteractionController::class, 'unlockArticle'])->name('interactions.unlock');
    
    // 用户积分
    Route::get('user/points', [HomeController::class, 'userPoints'])->name('user.points');
    
    // 收藏列表
    Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::delete('favorites/{type}/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('favorites/bulk-delete', [FavoriteController::class, 'bulkDestroy'])->name('favorites.bulk-destroy');
    
    // 浏览历史
    Route::get('history', [HistoryController::class, 'index'])->name('history.index');
    Route::delete('history/{id}', [HistoryController::class, 'destroy'])->name('history.destroy');
    Route::post('history/clear', [HistoryController::class, 'clear'])->name('history.clear');
});
