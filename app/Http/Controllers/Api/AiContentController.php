<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Project;
use App\Models\Job;
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
        
        // 验证 Token
        $token = $request->header('X-API-Token');
        $expectedToken = env('OPENCLAW_WEBHOOK_TOKEN', 'openclaw-ai-fetcher-2026');
        
        if ($token !== $expectedToken) {
            Log::error("❌ Token 认证失败");
            return response()->json(['success' => false, 'message' => '认证失败'], 401);
        }
        
        $data = $request->json()->all();
        $typeRaw = strtolower((string) ($data['type'] ?? ''));
        $type = match ($typeRaw) {
            'job', 'position', 'positions' => 'jobs',
            'article' => 'articles',
            'project' => 'projects',
            default => $typeRaw,
        };

        Log::info("📥 数据类型：" . $type);
        
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
                case 'learning_materials':
                    return $this->saveLearningMaterials($data['items'] ?? []);
                case 'platform_articles':
                    return $this->savePlatformArticles($data['items'] ?? []);
                default:
                    Log::error("❌ 未知类型：" . $type);
                    return response()->json(['success' => false, 'message' => '未知类型'], 400);
            }
        } catch (\Exception $e) {
            Log::error("❌ 保存失败：" . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * 验证 URL 是否有效（防止 AI 编造链接）
     */
    protected function isValidUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }
        
        // 检查 URL 格式
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        
        // 检查是否是编造的假链接（太短的网盘链接）
        if (preg_match('/pan\.baidu\.com\/s\/[a-zA-Z0-9_-]{5,15}$/', $url)) {
            Log::warning("⚠️ 检测到可能是编造的百度网盘链接：{$url}");
            return false;
        }
        
        // 允许的真实链接平台
        $validDomains = [
            'github.com',
            'zhihu.com',
            'zhihu',
            'jianshu.com',
            'juejin.cn',
            'jiqizhixin.com',
            'qbitai.com',
            'csdn.net',
            'cnblogs.com',
            'youtube.com',
            'youtu.be',
            'bilibili.com',
            'b23.tv',
            'huggingface.co',
            'pytorch.org',
            'tensorflow.org',
            'google.com',
            'bing.com',
        ];
        
        foreach ($validDomains as $domain) {
            if (strpos($url, $domain) !== false) {
                return true;
            }
        }
        
        // 其他链接需要更严格验证
        Log::warning("⚠️ 未知域名链接：{$url}");
        return false;
    }
    
    /**
     * 保存文章
     */
    protected function saveArticles(array $items)
    {
        Log::info("📝 开始保存文章，数量：" . count($items));
        $saved = 0;
        $failed = 0;
        $skipped = 0;
        
        foreach ($items as $index => $item) {
            try {
                // 验证 URL
                if (!$this->isValidUrl($item['url'] ?? null)) {
                    Log::warning("⏭️ [{$index}] URL 无效，跳过");
                    $skipped++;
                    continue;
                }
                
                $article = Article::firstOrCreate(
                    ['source_url' => $item['url']],
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
                $failed++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "成功保存 {$saved} 篇文章",
            'saved' => $saved,
            'failed' => $failed,
            'skipped' => $skipped
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
                if (!$this->isValidUrl($item['url'] ?? null)) {
                    continue;
                }
                
                Project::firstOrCreate(
                    ['url' => $item['url']],
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
        
        $adminUser = \App\Models\User::where('role', 'admin')->first();
        $userId = $adminUser?->id ?? \App\Models\User::query()->orderBy('id')->value('id');
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => '无可用用户'], 500);
        }
        
        $saved = 0;
        $failed = 0;
        $skipped = 0;
        
        foreach ($items as $index => $item) {
            try {
                // 验证 URL（职位必须是真实招聘链接）
                if (!$this->isValidUrl($item['url'] ?? null)) {
                    Log::warning("⏭️ [{$index}] 职位 URL 无效，跳过");
                    $skipped++;
                    continue;
                }
                
                // 检查是否已存在
                if (\App\Models\Job::where('source_url', $item['url'])->exists()) {
                    Log::info("⏭️ [{$index}] 职位已存在，跳过");
                    continue;
                }
                
                $desc = $item['description'] ?? $item['content'] ?? '';
                $job = Job::create([
                    'user_id' => $userId,
                    'title' => $item['title'] ?? '未知职位',
                    'company_name' => $item['company_name'] ?? $item['company'] ?? '未知公司',
                    'location' => $item['city'] ?? $item['location'] ?? '不限',
                    'salary_range' => $item['salary'] ?? $item['salary_range'] ?? '面议',
                    'requirements' => $desc,
                    'description' => $desc,
                    'source_url' => \Illuminate\Support\Str::limit($item['url'], 255, ''),
                    'is_published' => true,
                    'published_at' => now(),
                ]);
                
                Log::info("✅ [{$index}] 职位保存成功，ID: " . $job->id);
                $saved++;
            } catch (\Exception $e) {
                Log::error("❌ [{$index}] 保存职位失败：" . $e->getMessage());
                $failed++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "成功保存 {$saved} 个职位",
            'saved' => $saved,
            'failed' => $failed,
            'skipped' => $skipped
        ]);
    }
    
    /**
     * 保存知识库
     */
    protected function saveKnowledge(array $items)
    {
        $adminUser = \App\Models\User::where('role', 'admin')->first() ?? \App\Models\User::first();
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
    
    /**
     * 保存学习资料（PDF/网盘/视频教程）
     */
    protected function saveLearningMaterials(array $items)
    {
        Log::info("📖 开始保存学习资料，数量：" . count($items));
        
        $adminUser = \App\Models\User::where('role', 'admin')->first() ?? \App\Models\User::first();
        $userId = $adminUser ? $adminUser->id : 1;
        
        $knowledgeBase = KnowledgeBase::firstOrCreate(
            ['title' => 'AI 学习资料库'],
            [
                'user_id' => $userId,
                'description' => '收集 AI 相关的 PDF 文档、网盘资源、视频教程、电子书等各种学习资料',
                'category' => 'learning_materials',
                'is_public' => true,
                'is_vip_only' => false,
            ]
        );
        
        $saved = 0;
        $failed = 0;
        $skipped = 0;
        
        foreach ($items as $index => $item) {
            try {
                // 验证 URL（必须真实可访问）
                if (!$this->isValidUrl($item['url'] ?? null)) {
                    Log::warning("⏭️ [{$index}] URL 无效，跳过：" . ($item['url'] ?? '无 URL'));
                    $skipped++;
                    continue;
                }
                
                // 检查是否已存在
                $exists = KnowledgeDocument::where('title', $item['title'] ?? '')
                    ->orWhere('content', 'like', '%' . ($item['url'] ?? '') . '%')
                    ->exists();
                
                if ($exists) {
                    Log::info("⏭️ [{$index}] 已存在，跳过");
                    continue;
                }
                
                $resourceType = $item['resource_type'] ?? '其他';
                $resourceIcon = match($resourceType) {
                    'PDF' => '📄',
                    '网盘' => '💾',
                    '视频' => '🎥',
                    '电子书' => '📚',
                    default => '📎'
                };
                
                $content = <<<HTML
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.8; color: #1a202c;">
    <h1 style="font-size: 28px; margin-bottom: 20px; color: #2d3748;">{$resourceIcon} {$item['title']}</h1>
    
    <div style="background: linear-gradient(135deg, rgba(66, 153, 225, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%); padding: 20px; border-radius: 8px; margin: 20px 0;">
        <p style="margin: 0;"><strong>资源类型：</strong>{$resourceType}</p>
        <p style="margin: 10px 0 0;"><strong>来源平台：</strong>{$item['platform'] ?? '未知'}</p>
        <p style="margin: 10px 0 0;"><strong>更新时间：</strong>" . now()->format('Y-m-d H:i:s') . "</p>
    </div>
    
    <h2 style="font-size: 22px; margin: 30px 0 15px; color: #4a5568;">📝 资源描述</h2>
    <p style="line-height: 1.8;">{$item['description'] ?? '暂无详细描述'}</p>
    
    <div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, rgba(72, 187, 120, 0.1) 0%, rgba(66, 153, 225, 0.1) 100%); border-radius: 8px; border: 1px solid rgba(72, 187, 120, 0.3);">
        <p style="margin: 0; color: #2f855a; font-weight: 600;">🔗 访问链接：</p>
        <p style="margin: 10px 0 0;">
            <a href="{$item['url']}" target="_blank" style="color: #3182ce; text-decoration: none; word-break: break-all;">
                {$item['url']}
            </a>
        </p>
    </div>
</div>
HTML;
                
                KnowledgeDocument::create([
                    'knowledge_base_id' => $knowledgeBase->id,
                    'title' => $item['title'] ?? '无标题',
                    'content' => $content,
                    'file_type' => strtolower($resourceType),
                    'chunks' => preg_split('/\n\n+/', $item['description'] ?? ''),
                ]);
                
                Log::info("✅ [{$index}] 保存成功");
                $saved++;
            } catch (\Exception $e) {
                Log::error("❌ [{$index}] 保存失败：" . $e->getMessage());
                $failed++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "成功保存 {$saved} 个学习资料",
            'saved' => $saved,
            'failed' => $failed,
            'skipped' => $skipped
        ]);
    }
    
    /**
     * 保存多平台文章
     */
    protected function savePlatformArticles(array $items)
    {
        Log::info("📰 开始保存多平台文章，数量：" . count($items));
        
        $saved = 0;
        $failed = 0;
        $skipped = 0;
        $platformStats = [];
        
        foreach ($items as $index => $item) {
            try {
                // 验证 URL
                if (!$this->isValidUrl($item['url'] ?? null)) {
                    Log::warning("⏭️ [{$index}] URL 无效，跳过");
                    $skipped++;
                    continue;
                }
                
                // 检查是否已存在
                if (!empty($item['url']) && Article::where('source_url', $item['url'])->exists()) {
                    Log::info("⏭️ [{$index}] 文章已存在，跳过");
                    continue;
                }
                
                $platform = $item['platform'] ?? '未知';
                $platformSlug = 'platform-' . strtolower($platform);
                $category = Category::firstOrCreate(
                    ['slug' => $platformSlug],
                    ['name' => $platform . '精选']
                );
                
                $article = Article::create([
                    'category_id' => $category->id,
                    'title' => $item['title'] ?? '无标题',
                    'slug' => \Illuminate\Support\Str::slug($item['title']) . '-' . time() . '-' . rand(1000, 9999),
                    'summary' => $item['summary'] ?? '',
                    'content' => $this->buildPlatformArticleContent($item),
                    'source_url' => $item['url'] ?? null,
                    'cover_image' => null,
                    'is_published' => true,
                    'published_at' => now(),
                ]);
                
                Log::info("✅ [{$index}] {$platform}文章保存成功，ID: " . $article->id);
                $saved++;
                $platformStats[$platform] = ($platformStats[$platform] ?? 0) + 1;
            } catch (\Exception $e) {
                Log::error("❌ [{$index}] 保存失败：" . $e->getMessage());
                $failed++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "成功保存 {$saved} 篇多平台文章",
            'saved' => $saved,
            'failed' => $failed,
            'skipped' => $skipped,
            'platform_stats' => $platformStats
        ]);
    }
    
    /**
     * 构建多平台文章内容 HTML
     */
    protected function buildPlatformArticleContent(array $item): string
    {
        $platform = $item['platform'] ?? '未知';
        $platformColors = [
            '知乎' => '#0084ff',
            '简书' => '#ea6f5a',
            '掘金' => '#1e80ff',
            '机器之心' => '#000000',
            '量子位' => '#0066cc',
        ];
        $color = $platformColors[$platform] ?? '#666666';
        
        return <<<HTML
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.8; color: #1a202c;">
    <div style="display: flex; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid {$color};">
        <div>
            <h1 style="font-size: 24px; margin: 0; color: #2d3748;">{$item['title']}</h1>
            <p style="margin: 5px 0 0; color: #718096; font-size: 14px;">来源：{$platform} | 作者：{$item['author'] ?? '佚名'}</p>
        </div>
    </div>
    
    <h2 style="font-size: 20px; margin: 25px 0 15px; color: #4a5568; border-left: 4px solid {$color}; padding-left: 12px;">文章摘要</h2>
    <p style="line-height: 1.8; color: #2d3748;">{$item['summary'] ?? '暂无摘要'}</p>
    
    <div style="margin-top: 30px; padding: 20px; background: #f7fafc; border-radius: 8px;">
        <p style="margin: 0; color: #4a5568; font-weight: 600;">📌 原文链接：</p>
        <p style="margin: 10px 0 0;">
            <a href="{$item['url']}" target="_blank" style="color: {$color}; text-decoration: none; word-break: break-all;">
                {$item['url']}
            </a>
        </p>
    </div>
</div>
HTML;
    }
}
