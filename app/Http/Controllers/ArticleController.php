<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * 文章列表
     */
    public function index(Request $request)
    {
        $query = Article::query();
        
        // 分类筛选
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
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
        $article = Article::with(['author', 'category'])->findOrFail($id);
        return view('articles.show', compact('article'));
    }
}
