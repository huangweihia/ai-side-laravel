<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\UserAction;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * 文章列表
     */
    public function index(Request $request)
    {
        $query = Article::query();
        
        // 分类筛选 - 简化处理
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }
        
        // 搜索
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('summary', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        
        // 只获取已发布的文章
        $query->where('is_published', true);
        
        $articles = $query->latest('published_at')->paginate(10)->withQueryString();
        
        return view('articles.index', compact('articles'));
    }

    /**
     * 文章详情
     */
    public function show($id)
    {
        $article = Article::with(['author', 'category', 'comments.user'])->findOrFail($id);
        
        // 增加浏览次数
        $article->increment('view_count');
        
        // 记录浏览历史
        if (auth()->check()) {
            \App\Models\ViewHistory::record(auth()->user(), $article);
        }
        
        // 评论：精品评论置顶 + 其余按最新（首屏 10 条）
        $featuredComment = $article->comments()
            ->whereNull('parent_id')
            ->where('is_hidden', false)
            ->with(['user', 'replies.user', 'replies.replyTo.user'])
            ->orderByDesc('like_count')
            ->orderByDesc('id')
            ->first();

        $commentsQuery = $article->comments()
            ->whereNull('parent_id')
            ->where('is_hidden', false)
            ->with(['user', 'replies.user', 'replies.replyTo.user'])
            ->latest();

        if ($featuredComment) {
            $commentsQuery->where('id', '!=', $featuredComment->id);
        }

        $comments = collect();
        if ($featuredComment) {
            $comments->push($featuredComment);
        }
        $comments = $comments->concat($commentsQuery->get());

        $commentsTotal = $article->comments()
            ->whereNull('parent_id')
            ->where('is_hidden', false)
            ->count();
        
        // 检查用户互动状态
        $isFavorited = auth()->check() && $article->isFavoritedBy(auth()->user());
        $isLiked = auth()->check() && auth()->user()->hasLiked($article);

        // 相关文章推荐
        $relatedArticles = $article->getRelatedArticles(5);

        // VIP 全文：后端鉴权，视图不得输出未授权正文
        $canViewFullArticle = $article->userCanViewFullContent(auth()->user());

        $likedCommentIds = auth()->check()
            ? UserAction::query()
                ->where('user_id', auth()->id())
                ->where('type', 'comment_like')
                ->where('actionable_type', Comment::class)
                ->pluck('actionable_id')
                ->map(fn ($id) => (int) $id)
                ->all()
            : [];

        return view('articles.show', compact('article', 'comments', 'commentsTotal', 'featuredComment', 'isFavorited', 'isLiked', 'likedCommentIds', 'relatedArticles', 'canViewFullArticle'));
    }

    /**
     * 收藏/取消收藏文章
     */
    public function toggleFavorite($id)
    {
        $article = Article::findOrFail($id);
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => '请先登录',
            ], 401);
        }

        $favorited = UserAction::toggleAction($user->id, 'favorite', $article);

        if ($favorited) {
            $article->increment('favorite_count');
            Favorite::firstOrCreate([
                'user_id' => $user->id,
                'favoritable_type' => Article::class,
                'favoritable_id' => $article->id,
            ]);
        } else {
            $article->decrement('favorite_count');
            Favorite::where('user_id', $user->id)
                ->where('favoritable_type', Article::class)
                ->where('favoritable_id', $article->id)
                ->delete();
        }

        return response()->json([
            'success' => true,
            'isFavorited' => $favorited,
            'favorites_count' => $article->favorite_count,
        ]);
    }

    /**
     * 点赞文章
     */
    public function toggleLike($id)
    {
        $article = Article::findOrFail($id);
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => '请先登录',
            ], 401);
        }

        $liked = UserAction::toggleAction($user->id, 'like', $article);

        if ($liked) {
            $article->increment('like_count');
        } else {
            $article->decrement('like_count');
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $article->like_count,
        ]);
    }

    /**
     * 发表评论
     */
    public function storeComment($id, Request $request)
    {
        $article = Article::findOrFail($id);
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => '请先登录',
            ], 401);
        }
        
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:comments,id',
            'reply_to_id' => 'nullable|integer|exists:comments,id',
        ]);

        $parentId = $request->input('parent_id');
        $replyToId = $request->input('reply_to_id');
        if ($parentId) {
            $parentComment = Comment::find($parentId);
            if (!$parentComment || $parentComment->commentable_type !== Article::class || (int) $parentComment->commentable_id !== (int) $article->id) {
                return response()->json([
                    'success' => false,
                    'message' => '回复目标无效',
                ], 422);
            }

            if ($replyToId) {
                $replyTarget = Comment::find($replyToId);
                if (!$replyTarget || (int) ($replyTarget->parent_id ?: $replyTarget->id) !== (int) $parentComment->id) {
                    return response()->json([
                        'success' => false,
                        'message' => '引用目标无效',
                    ], 422);
                }
            }
        }

        $comment = $article->comments()->create([
            'user_id' => $user->id,
            'content' => $request->content,
            'parent_id' => $parentId,
            'reply_to_id' => $replyToId,
        ]);
        
        return response()->json([
            'success' => true,
            'comment' => $comment->load('user'),
            'total' => $article->comments()->whereNull('parent_id')->where('is_hidden', false)->count(),
        ]);
    }
    
    /**
     * VIP 专属文章列表
     */
    public function vipArticles()
    {
        $articles = Article::where('is_vip', true)
            ->where('is_published', true)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);
        
        return view('articles.vip-index', compact('articles'));
    }
}
