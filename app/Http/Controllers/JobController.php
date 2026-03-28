<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Comment;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * 职位列表
     */
    public function index(Request $request)
    {
        $query = Job::query()->where('is_published', true);
        
        // 搜索
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }
        
        // 地点筛选
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        
        $jobs = $query->latest('published_at')->paginate(20)->withQueryString();
        
        return view('jobs.index', compact('jobs'));
    }

    /**
     * 职位详情
     */
    public function show($id)
    {
        $job = Job::with(['user', 'comments.user'])->findOrFail($id);
        
        // 增加浏览次数
        $job->incrementViewCount();
        
        // 记录浏览历史
        if (auth()->check()) {
            \App\Models\ViewHistory::record(auth()->user(), $job);
        }
        
        // 检查是否可以查看联系方式
        $canViewContact = $job->canViewContact(auth()->user());
        
        // 获取评论（最新 10 条）
        $comments = $job->comments()
            ->whereNull('parent_id')
            ->where('is_hidden', false)
            ->with(['user', 'replies.user'])
            ->latest()
            ->limit(10)
            ->get();
        
        $commentsTotal = $job->comments()
            ->whereNull('parent_id')
            ->where('is_hidden', false)
            ->count();
        
        // 获取相关职位
        $relatedJobs = Job::query()
            ->where('id', '!=', $job->id)
            ->where('is_published', true)
            ->where(function ($q) use ($job) {
                $q->where('location', $job->location)
                  ->orWhere('salary_range', 'like', '%' . substr($job->salary_range, 0, 3) . '%');
            })
            ->limit(5)
            ->get();
        
        return view('jobs.show', compact('job', 'canViewContact', 'comments', 'commentsTotal', 'relatedJobs'));
    }

    /**
     * 申请职位
     */
    public function apply(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => '请先登录后申请职位',
            ], 401);
        }
        
        $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);
        
        // 记录申请
        $job->increment('apply_count');
        
        // TODO: 发送通知给职位发布者
        
        return response()->json([
            'success' => true,
            'message' => '申请已提交，祝您求职顺利！',
            'apply_count' => $job->apply_count,
        ]);
    }

    /**
     * 发表评论
     */
    public function storeComment(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => '请先登录后评论',
            ], 401);
        }
        
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        $comment = $job->comments()->create([
            'user_id' => $user->id,
            'content' => $request->content,
        ]);
        
        return response()->json([
            'success' => true,
            'comment' => $comment->load('user'),
            'total' => $job->comments()->whereNull('parent_id')->where('is_hidden', false)->count(),
        ]);
    }
}
