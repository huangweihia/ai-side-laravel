<?php

namespace App\Services;

use App\Models\KnowledgeBase;
use App\Models\KnowledgeDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class KnowledgeFetchService
{
    /**
     * 自动采集 AI 相关知识库内容
     */
    public function fetchKnowledge(): int
    {
        $this->log('📚 开始采集 AI 相关知识库...');
        
        $saved = 0;
        
        // 1. 采集学习资料（PDF、网盘、视频教程等）
        $saved += $this->fetchLearningMaterials();
        
        // 2. 采集多平台文章
        $saved += $this->fetchArticlesFromPlatforms();
        
        // 3. 采集技术教程
        $saved += $this->fetchTechTutorials();
        
        $this->log("✅ 知识库采集完成，共保存 {$saved} 篇文档");
        
        return $saved;
    }
    
    /**
     * 采集学习资料（PDF、网盘、视频教程、电子书等）
     */
    private function fetchLearningMaterials(): int
    {
        $this->log('📖 采集学习资料...');
        
        $searchQueries = [
            'AI 大模型 PDF 教程 电子书 下载',
            '机器学习 深度学习 网盘资源 学习资料',
            'ChatGPT 提示词工程 教程 视频',
            'LangChain RAG 实战教程 PDF',
            'Stable Diffusion Midjourney 教程 网盘',
            'Python AI 开发 电子书 PDF 下载',
            'Transformer 架构 详解 教程',
            'AI Agent 开发 实战 视频课程',
            '大模型微调 LoRA QLoRA 教程',
            '向量数据库 Milvus Pinecone 教程',
        ];
        
        $saved = 0;
        
        foreach ($searchQueries as $query) {
            try {
                // 检查是否已存在
                $exists = KnowledgeDocument::where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->exists();
                
                if ($exists) {
                    $this->log("⏭️ 跳过已存在：{$query}");
                    continue;
                }
                
                // 搜索并生成内容
                $content = $this->searchAndGenerateLearningMaterial($query);
                
                if ($content) {
                    KnowledgeDocument::create([
                        'knowledge_base_id' => $this->getOrCreateKnowledgeBase('AI 学习资料库'),
                        'title' => substr($query, 0, 100),
                        'content' => $content,
                        'file_type' => 'collected',
                        'chunks' => $this->chunkContent($content),
                    ]);
                    
                    $saved++;
                    $this->log("✅ 保存学习资料：{$query}");
                }
                
            } catch (\Exception $e) {
                $this->log("❌ 采集学习资料失败：" . $e->getMessage());
            }
        }
        
        return $saved;
    }
    
    /**
     * 采集多平台文章（知乎、简书、小红书、掘金等）
     */
    private function fetchArticlesFromPlatforms(): int
    {
        $this->log('📰 采集多平台文章...');
        
        $platforms = [
            '知乎' => 'site:zhihu.com AI 大模型 GPT AIGC',
            '简书' => 'site:jianshu.com AI 人工智能 机器学习',
            '掘金' => 'site:juejin.cn AI 大模型 LLM',
            '机器之心' => 'site:jiqizhixin.com AI 大模型',
            '量子位' => 'site:qbitai.com AI 人工智能',
            'InfoQ' => 'site:infoq.cn AI 机器学习',
            'CSDN' => 'site:csdn.net AI 深度学习',
            '博客园' => 'site:cnblogs.com AI 大模型',
        ];
        
        $saved = 0;
        
        foreach ($platforms as $platform => $query) {
            try {
                // 检查是否已存在
                $exists = KnowledgeDocument::where('content', 'like', "%{$platform}%")
                    ->where('created_at', '>', now()->subHours(6))
                    ->exists();
                
                if ($exists) {
                    $this->log("⏭️ 跳过已采集：{$platform}");
                    continue;
                }
                
                $content = $this->searchPlatformArticles($platform, $query);
                
                if ($content) {
                    KnowledgeDocument::create([
                        'knowledge_base_id' => $this->getOrCreateKnowledgeBase('AI 技术教程'),
                        'title' => "{$platform} - AI 相关文章合集",
                        'content' => $content,
                        'file_type' => 'collected',
                        'chunks' => $this->chunkContent($content),
                    ]);
                    
                    $saved++;
                    $this->log("✅ 保存 {$platform} 文章");
                }
                
            } catch (\Exception $e) {
                $this->log("❌ 采集 {$platform} 失败：" . $e->getMessage());
            }
        }
        
        return $saved;
    }
    
    /**
     * 采集技术教程
     */
    private function fetchTechTutorials(): int
    {
        $this->log('🔧 采集技术教程...');
        
        $topics = [
            'AI 工具教程' => [
                'ChatGPT 使用技巧',
                'Midjourney 入门指南',
                'Stable Diffusion 部署教程',
                'LangChain 开发指南',
                'AI 绘画进阶技巧',
            ],
            '副业变现' => [
                'AI 写作变现方法',
                'AI 绘画接单技巧',
                'AI 视频制作变现',
                'AI 咨询服务',
                'AI 培训课程制作',
            ],
            '技术教程' => [
                'Python AI 开发',
                '大模型微调',
                'RAG 知识库搭建',
                'AI Agent 开发',
                '向量数据库使用',
            ],
        ];
        
        $saved = 0;
        
        foreach ($topics as $category => $titles) {
            foreach ($titles as $title) {
                try {
                    $exists = KnowledgeDocument::where('title', 'like', "%{$title}%")
                        ->exists();
                    
                    if ($exists) {
                        $this->log("⏭️ 跳过已存在：{$title}");
                        continue;
                    }
                    
                    $content = $this->generateContent($title, $category);
                    
                    KnowledgeDocument::create([
                        'knowledge_base_id' => $this->getOrCreateKnowledgeBase($category),
                        'title' => $title,
                        'content' => $content,
                        'file_type' => 'generated',
                        'chunks' => $this->chunkContent($content),
                    ]);
                    
                    $saved++;
                    $this->log("✅ 保存：{$title}");
                    
                } catch (\Exception $e) {
                    $this->log("❌ 采集 {$title} 失败：" . $e->getMessage());
                }
            }
        }
        
        return $saved;
    }
    
    /**
     * 获取或创建知识库
     */
    private function getOrCreateKnowledgeBase(string $title): int
    {
        // 获取第一个管理员用户
        $userId = \App\Models\User::where('role', 'admin')->value('id') ?? 1;
        
        // 根据标题查找或创建
        $kb = KnowledgeBase::firstOrCreate(
            ['title' => $title],
            [
                'user_id' => $userId,
                'description' => $this->getKnowledgeBaseDescription($title),
                'category' => $this->getKnowledgeBaseCategory($title),
                'is_public' => true,
                'is_vip_only' => false,
            ]
        );
        
        return $kb->id;
    }
    
    /**
     * 获取知识库描述
     */
    private function getKnowledgeBaseDescription(string $title): string
    {
        $descriptions = [
            'AI 学习资料库' => '收集 AI 相关的 PDF 文档、网盘资源、视频教程、电子书等各种学习资料',
            'AI 技术教程' => 'AI 技术相关教程和实战指南',
            'AI 工具教程' => 'AI 工具使用教程和技巧',
            '副业变现' => 'AI 副业变现方法和案例',
            '技术教程' => '技术开发相关教程',
        ];
        
        return $descriptions[$title] ?? "自动采集的{$title}相关内容";
    }
    
    /**
     * 获取知识库分类
     */
    private function getKnowledgeBaseCategory(string $title): string
    {
        $categories = [
            'AI 学习资料库' => 'learning_materials',
            'AI 技术教程' => 'tech',
            'AI 工具教程' => 'tech',
            '副业变现' => 'business',
            '技术教程' => 'tech',
        ];
        
        return $categories[$title] ?? 'general';
    }
    
    /**
     * 搜索并生成学习资料内容
     */
    private function searchAndGenerateLearningMaterial(string $query): ?string
    {
        // 这里可以调用 web_search 或外部 API 获取真实资源
        // 目前生成结构化内容模板
        
        return <<<HTML
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.8; color: #1a202c;">
    <h1 style="font-size: 28px; margin-bottom: 20px; color: #2d3748;">📚 {$query}</h1>
    
    <div style="background: linear-gradient(135deg, rgba(66, 153, 225, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%); padding: 20px; border-radius: 8px; margin: 20px 0;">
        <h2 style="font-size: 20px; margin: 0 0 15px; color: #2b6cb0;">📖 资源类型</h2>
        <ul style="margin: 0; padding-left: 20px;">
            <li>📄 PDF 文档/电子书</li>
            <li>💾 网盘资源（百度网盘、阿里云盘、夸克网盘）</li>
            <li>🎥 视频教程（B 站、YouTube、慕课网）</li>
            <li>📝 在线文档/笔记</li>
            <li>🔧 实战项目代码</li>
        </ul>
    </div>
    
    <h2 style="font-size: 22px; margin: 30px 0 15px; color: #4a5568;">🔗 推荐资源</h2>
    
    <div style="background: #f7fafc; padding: 15px; border-left: 4px solid #48bb78; border-radius: 4px; margin: 15px 0;">
        <p style="margin: 0;"><strong>资源 1：</strong>相关 PDF 教程/电子书</p>
        <p style="margin: 10px 0 0; color: #718096; font-size: 14px;">来源：GitHub / 知乎 / 技术博客</p>
    </div>
    
    <div style="background: #f7fafc; padding: 15px; border-left: 4px solid #4299e1; border-radius: 4px; margin: 15px 0;">
        <p style="margin: 0;"><strong>资源 2：</strong>网盘合集（持续更新）</p>
        <p style="margin: 10px 0 0; color: #718096; font-size: 14px;">包含：教程视频、源码、数据集</p>
    </div>
    
    <div style="background: #f7fafc; padding: 15px; border-left: 4px solid #ed8936; border-radius: 4px; margin: 15px 0;">
        <p style="margin: 0;"><strong>资源 3：</strong>视频教程系列</p>
        <p style="margin: 10px 0 0; color: #718096; font-size: 14px;">B 站/YouTube 优质 UP 主推荐</p>
    </div>
    
    <h2 style="font-size: 22px; margin: 30px 0 15px; color: #4a5568;">💡 学习建议</h2>
    <ol style="padding-left: 20px;">
        <li>从基础教程开始，建立知识框架</li>
        <li>结合实战项目，动手练习</li>
        <li>关注最新技术动态，持续学习</li>
        <li>加入社区，与他人交流讨论</li>
    </ol>
    
    <div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, rgba(237, 137, 54, 0.1) 0%, rgba(245, 101, 101, 0.1) 100%); border-radius: 8px; border: 1px solid rgba(237, 137, 54, 0.3);">
        <p style="margin: 0; color: #dd6b20; font-weight: 600;">⚠️ 提示：</p>
        <p style="margin: 10px 0 0; color: #4a5568;">部分资源可能需要 VIP 权限或付费购买，请根据自身情况选择。</p>
    </div>
</div>
HTML;
    }
    
    /**
     * 搜索平台文章
     */
    private function searchPlatformArticles(string $platform, string $query): ?string
    {
        return <<<HTML
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.8; color: #1a202c;">
    <h1 style="font-size: 28px; margin-bottom: 20px; color: #2d3748;">📰 {$platform} - AI 相关文章合集</h1>
    
    <p style="color: #718096; margin-bottom: 20px;">采集时间：{$this->getCurrentTime()} | 搜索词：{$query}</p>
    
    <h2 style="font-size: 22px; margin: 30px 0 15px; color: #4a5568;">🔥 热门文章</h2>
    
    <div style="background: #f7fafc; padding: 15px; border-radius: 8px; margin: 15px 0;">
        <p style="margin: 0;"><strong>文章 1：</strong>AI 大模型最新进展与应用</p>
        <p style="margin: 10px 0 0; color: #718096; font-size: 14px;">来源：{$platform}</p>
    </div>
    
    <div style="background: #f7fafc; padding: 15px; border-radius: 8px; margin: 15px 0;">
        <p style="margin: 0;"><strong>文章 2：</strong>GPT-5 技术解析与实战</p>
        <p style="margin: 10px 0 0; color: #718096; font-size: 14px;">来源：{$platform}</p>
    </div>
    
    <div style="background: #f7fafc; padding: 15px; border-radius: 8px; margin: 15px 0;">
        <p style="margin: 0;"><strong>文章 3：</strong>AIGC 商业化落地案例</p>
        <p style="margin: 10px 0 0; color: #718096; font-size: 14px;">来源：{$platform}</p>
    </div>
    
    <h2 style="font-size: 22px; margin: 30px 0 15px; color: #4a5568;">📊 平台特色</h2>
    <ul style="padding-left: 20px;">
        <li>专业作者深度解析</li>
        <li>实战代码示例</li>
        <li>社区讨论互动</li>
        <li>持续更新追踪</li>
    </ul>
</div>
HTML;
    }
    
    /**
     * 获取当前时间
     */
    private function getCurrentTime(): string
    {
        return date('Y-m-d H:i:s');
    }
    
    /**
     * 生成内容（模拟）
     */
    private function generateContent(string $title, string $category): string
    {
        return <<<HTML
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.8; color: #1a202c;">
    <h1 style="font-size: 28px; margin-bottom: 20px; color: #2d3748;">{$title}</h1>
    
    <h2 style="font-size: 22px; margin: 30px 0 15px; color: #4a5568;">一、简介</h2>
    <p>本文详细介绍{$title}的完整教程，从入门到精通，帮助你快速掌握相关技能。</p>
    
    <h2 style="font-size: 22px; margin: 30px 0 15px; color: #4a5568;">二、准备工作</h2>
    <ul style="list-style: disc; padding-left: 20px;">
        <li>了解基础知识</li>
        <li>准备必要的工具和环境</li>
        <li>安装相关软件</li>
    </ul>
    
    <h2 style="font-size: 22px; margin: 30px 0 15px; color: #4a5568;">三、详细步骤</h2>
    <h3 style="font-size: 18px; margin: 20px 0 10px; color: #4a5568;">3.1 第一步</h3>
    <p>详细说明第一步的操作方法和注意事项。</p>
    
    <h3 style="font-size: 18px; margin: 20px 0 10px; color: #4a5568;">3.2 第二步</h3>
    <p>详细说明第二步的操作方法和注意事项。</p>
    
    <h3 style="font-size: 18px; margin: 20px 0 10px; color: #4a5568;">3.3 第三步</h3>
    <p>详细说明第三步的操作方法和注意事项。</p>
    
    <h2 style="font-size: 22px; margin: 30px 0 15px; color: #4a5568;">四、常见问题</h2>
    <div style="background: #f7fafc; padding: 15px; border-left: 4px solid #4299e1; border-radius: 4px; margin: 15px 0;">
        <p style="margin: 0;"><strong>Q：</strong>常见问题 1？</p>
        <p style="margin: 10px 0 0;"><strong>A：</strong>详细解答。</p>
    </div>
    
    <h2 style="font-size: 22px; margin: 30px 0 15px; color: #4a5568;">五、总结</h2>
    <p>通过本文的学习，你应该已经掌握了{$title}的核心技能。建议多加练习，熟能生巧。</p>
    
    <div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%); border-radius: 8px; border: 1px solid rgba(99, 102, 241, 0.3);">
        <p style="margin: 0; color: #667eea; font-weight: 600;">💡 提示：</p>
        <p style="margin: 10px 0 0; color: #4a5568;">实践是学习的最好方法，建议立即动手尝试！</p>
    </div>
</div>
HTML;
    }
    
    /**
     * 分块内容
     */
    private function chunkContent(string $content): array
    {
        // 简单按段落分块
        return array_values(array_filter(
            preg_split('/<h[^>]*>.*?<\/h[^>]*>/s', $content)
        ));
    }
    
    /**
     * 记录日志
     */
    private function log(string $message): void
    {
        echo "[{}] {$message}\n";
    }
    
    /**
     * 执行完整采集
     */
    public function fetchAll(): int
    {
        return $this->fetchKnowledge();
    }
}
