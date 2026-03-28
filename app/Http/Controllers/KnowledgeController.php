<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBase;
use App\Models\KnowledgeDocument;
use App\Models\Comment;
use App\Services\KnowledgeSearchService;
use Illuminate\Http\Request;

class KnowledgeController extends Controller
{
    protected $searchService;

    public function __construct(KnowledgeSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * 知识库首页
     */
    public function index(Request $request)
    {
        // 暂时跳转到首页，避免报错
        return redirect()->route('home')
            ->with('info', '知识库功能开发中，敬请期待');
    }

    /**
     * 搜索
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1|max:200',
        ]);
        
        $query = $request->input('q');
        $user = auth()->user();
        
        // 检查搜索配额
        if ($user) {
            $quota = $this->searchService->checkSearchQuota($user->id);
            
            if (!$quota['can_search']) {
                return redirect()->route('knowledge.index')
                    ->with('error', "搜索次数已达上限，升级为 VIP 可无限搜索");
            }
        }
        
        // 执行搜索
        $results = $this->searchService->search($query, $user?->id);
        
        return view('knowledge.search', compact('results', 'query'));
    }

    /**
     * 知识库详情
     */
    public function show(KnowledgeBase $knowledgeBase)
    {
        // 权限检查
        if (!$knowledgeBase->is_public) {
            abort(403, '知识库未公开');
        }
        
        if ($knowledgeBase->is_vip_only && (!auth()->check() || (!auth()->user()->isVip() && !auth()->user()->isAdmin()))) {
            return redirect()->route('vip', ['redirect' => request()->fullUrl()])
                ->with('error', '此知识库仅 VIP 用户可访问');
        }
        
        $documents = $knowledgeBase->documents()
            ->latest()
            ->paginate(20);
        
        return view('knowledge.show', compact('knowledgeBase', 'documents'));
    }

    /**
     * 文档详情
     */
    public function showDocument(KnowledgeDocument $document)
    {
        $knowledgeBase = $document->knowledgeBase;
        
        // 权限检查
        if (!$knowledgeBase->is_public) {
            abort(403, '文档未公开');
        }
        
        if ($knowledgeBase->is_vip_only && (!auth()->check() || (!auth()->user()->isVip() && !auth()->user()->isAdmin()))) {
            return redirect()->route('vip', ['redirect' => request()->fullUrl()])
                ->with('error', '此文档仅 VIP 用户可访问');
        }
        
        // 增加浏览次数
        $document->incrementViewCount();
        
        // 记录浏览历史
        if (auth()->check()) {
            \App\Models\ViewHistory::record(auth()->user(), $document);
        }
        
        // 获取评论（最新 10 条）
        $comments = $document->comments()
            ->whereNull('parent_id')
            ->where('is_hidden', false)
            ->with(['user', 'replies.user'])
            ->latest()
            ->limit(10)
            ->get();
        
        $commentsTotal = $document->comments()
            ->whereNull('parent_id')
            ->where('is_hidden', false)
            ->count();
        
        return view('knowledge.document', compact('document', 'knowledgeBase', 'comments', 'commentsTotal'));
    }

    /**
     * 发表评论
     */
    public function storeComment(Request $request, $documentId)
    {
        $document = KnowledgeDocument::findOrFail($documentId);
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => '请先登录后评论',
            ], 401);
        }

        $knowledgeBase = $document->knowledgeBase;
        if (!$knowledgeBase->is_public) {
            return response()->json(['success' => false, 'message' => '无权评论'], 403);
        }
        if ($knowledgeBase->is_vip_only && (!$user->isVip() && !$user->isAdmin())) {
            return response()->json(['success' => false, 'message' => '仅 VIP 可参与讨论'], 403);
        }
        
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        $comment = $document->comments()->create([
            'user_id' => $user->id,
            'content' => $request->content,
        ]);
        
        return response()->json([
            'success' => true,
            'comment' => $comment->load('user'),
            'total' => $document->comments()->whereNull('parent_id')->where('is_hidden', false)->count(),
        ]);
    }
}
