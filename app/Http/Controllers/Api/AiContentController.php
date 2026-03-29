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
        Log::info("📥 ==================== API 接收请求 ====================");
        Log::info("📥 请求时间：" . now()->format('Y-m-d H:i:s'));
        Log::info("📥 Token: " . $request->header('X-API-Token'));
        Log::info("📥 Content-Type: " . $request->header('Content-Type'));
        
        // 验证 Token（简单认证）
        $token = $request->header('X-API-Token');
        $expectedToken = env('OPENCLAW_WEBHOOK_TOKEN', 'openclaw-ai-fetcher-2026');
        
        Log::info("📥 期望 Token: " . $expectedToken);
        Log::info("📥 Token 匹配：" . ($token === $expectedToken ? '✅' : '❌'));
        
        if ($token !== $expectedToken) {
            Log::error("❌ Token 认证失败");
            return response()->json(['success' => false, 'message' => '认证失败'], 401);
        }
        
        $data = $request->json()->all();
        $type = $data['type'] ?? '';
        
        Log::info("📥 数据类型：" . $type);
        Log::info("📥 数据数量：" . count($data['items'] ?? []));
        Log::info("📥 完整数据：" . json_encode($data, JSON_UNESCAPED_UNICODE));
        
        try {
            switch ($type) {
                case 'articles':
                    Log::info("📝 开始保存文章...");
                    $result = $this->saveArticles($data['items'] ?? []);
                    Log::info("✅ 文章保存完成：" . json_encode($result));
                    return $result;
                    
                case 'projects':
                    Log::info("💻 开始保存项目...");
                    $result = $this->saveProjects($data['items'] ?? []);
                    Log::info("✅ 项目保存完成：" . json_encode($result));
                    return $result;
                    
                case 'jobs':
                    Log::info("💼 开始保存职位...");
                    $result = $this->saveJobs($data['items'] ?? []);
                    Log::info("✅ 职位保存完成：" . json_encode($result));
                    return $result;
                    
                case 'knowledge':
                    Log::info("📚 开始保存知识库...");
                    $result = $this->saveKnowledge($data['items'] ?? []);
                    Log::info("✅ 知识库保存完成：" . json_encode($result));
                    return $result;
                    
                default:
                    Log::error("❌ 未知类型：" . $type);
                    return response()->json(['success' => false, 'message' => '未知类型'], 400);
            }
        } catch (\Exception $e) {
            Log::error("❌ 保存失败：" . $e->getMessage());
            Log::error("❌ 错误堆栈：" . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * 保存文章
     */
    protected function saveArticles(array $items)
    {
        Log::info("📝 开始保存文章，数量：" . count($items));
        $saved = 0;
        $failed = 0;
        
        foreach ($items as $index => $item) {
            try {
                Log::info("📝 [{$index}] 保存文章：" . ($item['title'] ?? '无标题'));
                
                $article = Article::firstOrCreate(
                    ['source_url' => $item['url'] ?? md5($item['title'])],
                    [
                        'title' => $item['title'] ?? '无标题',
                        'slug' => \Illuminate\Support\Str::slug($item['title']) . '-' . time() . '-' . rand(1000, 9999),
                        'summary' => $item['summary'] ?? '',
                        'content' => $item['content'] ?? '',
                        'cover_image' => $item['cover_image'] ?? null,
                        'is_published' => true,
                        'published_at' => now(),
                    ]
                );
                
                Log::info("✅ [{$index}] 文章保存成功，ID: " . $article->id);
                $saved++;
            } catch (\Exception $e) {
                Log::error("❌ [{$index}] 保存文章失败：" . $e->getMessage());
                Log::error("❌ 文章数据：" . json_encode($item, JSON_UNESCAPED_UNICODE));
                $failed++;
            }
        }
        
        Log::info("📝 文章保存完成：成功 {$saved}, 失败 {$failed}");
        
        return response()->json([
            'success' => true,
            'message' => "成功保存 {$saved} 篇文章",
            'saved' => $saved,
            'failed' => $failed
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
        Log::info("💼 开始保存职位，数量：" . count($items));
        
        // 获取管理员用户 ID
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        $userId = $adminUser ? $adminUser->id : 1;
        Log::info("💼 使用管理员 user_id: {$userId}");
        
        $saved = 0;
        $failed = 0;
        
        foreach ($items as $index => $item) {
            try {
                Log::info("💼 [{$index}] 保存职位：" . ($item['title'] ?? '无标题') . " - " . ($item['company_name'] ?? '未知公司'));
                
                // 优先使用 url 去重，如果没有则使用 source_url
                $uniqueUrl = $item['url'] ?? $item['source_url'] ?? null;
                
                // 如果有 URL，先检查是否已存在
                if ($uniqueUrl) {
                    $exists = \App\Models\Job::where('source_url', $uniqueUrl)->exists();
                    if ($exists) {
                        Log::info("⏭️ [{$index}] 职位已存在，跳过：" . $uniqueUrl);
                        continue;
                    }
                }
                
                // 保存到 positions 表（后台管理的表）
                $job = \App\Models\Job::create([
                    'user_id' => $userId,
                    'title' => $item['title'] ?? '未知职位',
                    'company_name' => $item['company_name'] ?? $item['company'] ?? '未知公司',
                    'location' => $item['city'] ?? $item['location'] ?? '不限',
                    'salary_range' => $item['salary'] ?? '面议',
                    'requirements' => $item['description'] ?? '',
                    'description' => $item['description'] ?? '',
                    'source_url' => $uniqueUrl,
                    'is_published' => true,
                    'published_at' => now(),
                ]);
                
                Log::info("✅ [{$index}] 职位保存成功，ID: " . $job->id . ", URL: " . ($uniqueUrl ?? '无'));
                $saved++;
            } catch (\Exception $e) {
                Log::error("❌ [{$index}] 保存职位失败：" . $e->getMessage());
                Log::error("❌ 职位数据：" . json_encode($item, JSON_UNESCAPED_UNICODE));
                $failed++;
            }
        }
        
        Log::info("💼 职位保存完成：成功 {$saved}, 失败 {$failed}");
        
        return response()->json([
            'success' => true,
            'message' => "成功保存 {$saved} 个职位",
            'saved' => $saved,
            'failed' => $failed
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
