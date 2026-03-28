<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Project;
use App\Models\JobListing;
use App\Models\KnowledgeDocument;
use App\Models\KnowledgeBase;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiContentController extends Controller
{
    /**
     * 接收 OpenClaw 推送的 AI 内容
     */
    public function storeContent(Request $request)
    {
        // 验证 Token（简单认证）
        $token = $request->header('X-API-Token');
        $expectedToken = env('OPENCLAW_WEBHOOK_TOKEN', 'openclaw-ai-fetcher-2026');
        
        if ($token !== $expectedToken) {
            return response()->json(['success' => false, 'message' => '认证失败'], 401);
        }
        
        $data = $request->json()->all();
        $type = $data['type'] ?? '';
        
        Log::info("📥 收到 OpenClaw 推送：{$type}", ['data' => $data]);
        
        try {
            switch ($type) {
                case 'articles':
                    return $this->saveArticles($data['items'] ?? []);
                    
                case 'projects':
                    return $this->saveProjects($data['items'] ?? []);
                    
                case 'jobs':
                    return $this->saveJobs($data['items'] ?? []);
                    
                case 'knowledge':
                    return $this->saveKnowledge($data['items'] ?? []);
                    
                default:
                    return response()->json(['success' => false, 'message' => '未知类型'], 400);
            }
        } catch (\Exception $e) {
            Log::error("❌ 保存失败：" . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * 保存文章
     */
    protected function saveArticles(array $items)
    {
        $saved = 0;
        foreach ($items as $item) {
            try {
                Article::firstOrCreate(
                    ['source_url' => $item['url'] ?? md5($item['title'])],
                    [
                        'title' => $item['title'] ?? '无标题',
                        'slug' => \Illuminate\Support\Str::slug($item['title']) . '-' . time(),
                        'summary' => $item['summary'] ?? '',
                        'content' => $item['content'] ?? '',
                        'cover_image' => $item['cover_image'] ?? null,
                        'is_published' => true,
                        'published_at' => now(),
                    ]
                );
                $saved++;
            } catch (\Exception $e) {
                Log::error("保存文章失败：" . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "成功保存 {$saved} 篇文章",
            'saved' => $saved
        ]);
    }
    
    /**
     * 保存项目
     */
    protected function saveProjects(array $items)
    {
        $category = Category::firstOrCreate(
            ['slug' => 'ai-tools'],
            ['name' => 'AI 工具']
        );
        
        $saved = 0;
        foreach ($items as $item) {
            try {
                Project::firstOrCreate(
                    ['url' => $item['url'] ?? md5($item['name'])],
                    [
                        'name' => $item['name'] ?? '未知项目',
                        'full_name' => $item['name'] ?? '未知项目',
                        'description' => $item['description'] ?? '暂无描述',
                        'stars' => (int) ($item['stars'] ?? 0),
                        'forks' => (int) ($item['forks'] ?? 0),
                        'language' => $item['language'] ?? null,
                        'category_id' => $category->id,
                        'monetization' => 'medium',
                        'difficulty' => 'medium',
                        'is_featured' => ($item['stars'] ?? 0) > 5000,
                        'collected_at' => now(),
                    ]
                );
                $saved++;
            } catch (\Exception $e) {
                Log::error("保存项目失败：" . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "成功保存 {$saved} 个项目",
            'saved' => $saved
        ]);
    }
    
    /**
     * 保存职位
     */
    protected function saveJobs(array $items)
    {
        $saved = 0;
        foreach ($items as $item) {
            try {
                // 根据 DATABASE_SCHEMA.md，job_listings 表字段是 company 不是 company_name
                JobListing::firstOrCreate(
                    [
                        'title' => $item['title'] ?? '未知职位',
                        'company' => $item['company_name'] ?? $item['company'] ?? '未知公司'
                    ],
                    [
                        'salary' => $item['salary'] ?? '面议',
                        'location' => $item['city'] ?? '不限',
                        'description' => $item['description'] ?? '',
                        'url' => $item['url'] ?? null,
                        'source' => $item['source'] ?? 'openclaw_ai',
                    ]
                );
                $saved++;
            } catch (\Exception $e) {
                Log::error("保存职位失败：" . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "成功保存 {$saved} 个职位",
            'saved' => $saved
        ]);
    }
    
    /**
     * 保存知识库
     */
    protected function saveKnowledge(array $items)
    {
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        if (!$adminUser) {
            $adminUser = \App\Models\User::first();
        }
        $userId = $adminUser ? $adminUser->id : 1;
        
        $knowledgeBase = KnowledgeBase::firstOrCreate(
            ['title' => 'AI 技术教程'],
            [
                'user_id' => $userId,
                'description' => 'AI 自动生成的技术文档',
                'category' => 'tech',
                'is_public' => true,
            ]
        );
        
        $saved = 0;
        foreach ($items as $item) {
            try {
                KnowledgeDocument::create([
                    'knowledge_base_id' => $knowledgeBase->id,
                    'title' => $item['title'] ?? '无标题',
                    'content' => $item['content'] ?? '',
                    'file_type' => 'ai_generated',
                    'chunks' => preg_split('/\n\n+/', $item['content'] ?? ''),
                ]);
                $saved++;
            } catch (\Exception $e) {
                Log::error("保存知识库失败：" . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "成功保存 {$saved} 篇知识库文档",
            'saved' => $saved
        ]);
    }
}
