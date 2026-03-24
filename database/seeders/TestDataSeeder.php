<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\EmailLog;
use App\Models\JobListing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 开始生成测试数据...');
        
        // 1. 生成分类
        $this->command->info('📁 创建分类...');
        $categories = $this->createCategories();
        
        // 2. 生成用户
        $this->command->info('👥 创建用户...');
        $users = $this->createUsers();
        
        // 3. 生成项目
        $this->command->info('🚀 创建项目...');
        $this->createProjects($categories);
        
        // 4. 生成文章
        $this->command->info('📰 创建文章...');
        $this->createArticles($categories, $users);
        
        // 5. 生成邮件日志
        $this->command->info('📧 创建邮件日志...');
        $this->createEmailLogs();
        
        // 6. 生成职位
        $this->command->info('💼 创建职位...');
        $this->createJobListings();
        
        $this->command->info('');
        $this->command->info('✅ 测试数据生成完成！');
        $this->command->info('   分类：' . Category::count());
        $this->command->info('   用户：' . User::count());
        $this->command->info('   项目：' . Project::count());
        $this->command->info('   文章：' . Article::count());
        $this->command->info('   邮件日志：' . EmailLog::count());
        $this->command->info('   职位：' . JobListing::count());
    }

    private function createCategories(): array
    {
        $cats = [
            ['name' => 'AI 工具', 'slug' => 'ai-tools', 'description' => 'AI 相关工具和平台'],
            ['name' => '副业项目', 'slug' => 'side-projects', 'description' => '副业和创业项目'],
            ['name' => '学习资源', 'slug' => 'learning', 'description' => '教程和学习资料'],
            ['name' => '行业资讯', 'slug' => 'news', 'description' => 'AI 行业动态'],
            ['name' => '变现案例', 'slug' => 'monetization', 'description' => '成功案例分享'],
        ];

        foreach ($cats as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        return Category::all()->keyBy('slug')->toArray();
    }

    private function createUsers(): array
    {
        // 管理员
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => '管理员',
                'password' => bcrypt('password123'),
                'role' => 'admin',
            ]
        );

        // VIP 用户
        $vipUsers = [
            ['name' => '张三', 'email' => 'vip1@example.com'],
            ['name' => '李四', 'email' => 'vip2@example.com'],
            ['name' => '王五', 'email' => 'vip3@example.com'],
        ];

        foreach ($vipUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password123'),
                    'role' => 'vip',
                    'subscription_ends_at' => now()->addMonths(6),
                ]
            );
        }

        // 普通用户
        $normalUsers = [
            ['name' => '小明', 'email' => 'user1@example.com'],
            ['name' => '小红', 'email' => 'user2@example.com'],
            ['name' => '小刚', 'email' => 'user3@example.com'],
            ['name' => '小丽', 'email' => 'user4@example.com'],
            ['name' => '小强', 'email' => 'user5@example.com'],
        ];

        foreach ($normalUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password123'),
                    'role' => 'user',
                ]
            );
        }

        return User::all()->toArray();
    }

    private function createProjects(array $categories): void
    {
        $projects = [
            [
                'name' => 'AI 写作助手',
                'description' => '基于 GPT-4 的智能写作工具，支持文章生成、润色、改写等功能',
                'url' => 'https://github.com/example/ai-writer',
                'github_url' => 'https://github.com/example/ai-writer',
                'status' => 'in_progress',
                'revenue' => '1000-5000 元/月',
                'tech_stack' => ['Python', 'GPT-4', 'FastAPI'],
                'category_id' => Category::where('slug', 'ai-tools')->first()?->id,
            ],
            [
                'name' => 'AI 绘画变现',
                'description' => '使用 Midjourney 和 Stable Diffusion 接商单，月入过万的副业项目',
                'url' => 'https://example.com/ai-art',
                'github_url' => null,
                'status' => 'completed',
                'revenue' => '10000+ 元/月',
                'tech_stack' => ['Midjourney', 'Stable Diffusion', 'Photoshop'],
                'category_id' => Category::where('slug', 'side-projects')->first()?->id,
            ],
            [
                'name' => 'AI 客服机器人',
                'description' => '为企业定制 AI 客服系统，按项目收费，单项目 5000-30000 元',
                'url' => 'https://github.com/example/ai-chatbot',
                'github_url' => 'https://github.com/example/ai-chatbot',
                'status' => 'in_progress',
                'revenue' => '5000-20000 元/月',
                'tech_stack' => ['Python', 'LangChain', 'Vue.js'],
                'category_id' => Category::where('slug', 'ai-tools')->first()?->id,
            ],
            [
                'name' => 'AI 生成短视频',
                'description' => '批量生成短视频内容，通过流量分成和带货变现',
                'url' => 'https://example.com/ai-video',
                'github_url' => null,
                'status' => 'in_progress',
                'revenue' => '3000-15000 元/月',
                'tech_stack' => ['Python', 'FFmpeg', '剪映 API'],
                'category_id' => Category::where('slug', 'monetization')->first()?->id,
            ],
            [
                'name' => 'AI 翻译服务',
                'description' => '接入 DeepL 和大模型 API，提供专业领域翻译服务',
                'url' => 'https://github.com/example/ai-translate',
                'github_url' => 'https://github.com/example/ai-translate',
                'status' => 'planning',
                'revenue' => '2000-8000 元/月',
                'tech_stack' => ['Node.js', 'DeepL API', 'React'],
                'category_id' => Category::where('slug', 'ai-tools')->first()?->id,
            ],
            [
                'name' => 'AI 数据分析报告',
                'description' => '自动生成数据分析报告，面向中小企业，按报告收费',
                'url' => 'https://example.com/ai-analytics',
                'github_url' => null,
                'status' => 'in_progress',
                'revenue' => '5000-20000 元/月',
                'tech_stack' => ['Python', 'Pandas', 'Chart.js'],
                'category_id' => Category::where('slug', 'side-projects')->first()?->id,
            ],
            [
                'name' => 'AI 头像定制',
                'description' => '使用 LoRA 模型定制专属头像，小红书引流变现',
                'url' => 'https://example.com/ai-avatar',
                'github_url' => null,
                'status' => 'completed',
                'revenue' => '1000-5000 元/月',
                'tech_stack' => ['Stable Diffusion', 'LoRA', '小红书'],
                'category_id' => Category::where('slug', 'monetization')->first()?->id,
            ],
            [
                'name' => 'AI 课程分销',
                'description' => '代理 AI 相关课程，通过内容营销获客，佣金 30-50%',
                'url' => 'https://example.com/ai-course',
                'github_url' => null,
                'status' => 'in_progress',
                'revenue' => '3000-15000 元/月',
                'tech_stack' => ['微信公众号', '知识付费', '私域流量'],
                'category_id' => Category::where('slug', 'monetization')->first()?->id,
            ],
            [
                'name' => 'AI 提示词市场',
                'description' => '收集和售卖优质 Prompt，单条售价 9.9-99 元',
                'url' => 'https://example.com/prompt-market',
                'github_url' => null,
                'status' => 'planning',
                'revenue' => '1000-10000 元/月',
                'tech_stack' => ['Next.js', 'Stripe', 'Notion'],
                'category_id' => Category::where('slug', 'side-projects')->first()?->id,
            ],
            [
                'name' => 'AI 代写服务',
                'description' => '承接论文润色、简历优化、文案写作等服务',
                'url' => 'https://example.com/ai-writing-service',
                'github_url' => null,
                'status' => 'completed',
                'revenue' => '5000-20000 元/月',
                'tech_stack' => ['GPT-4', '淘宝店', '私域流量'],
                'category_id' => Category::where('slug', 'monetization')->first()?->id,
            ],
        ];

        foreach ($projects as $project) {
            Project::firstOrCreate(
                ['name' => $project['name']],
                $project
            );
        }
    }

    private function createArticles(array $categories, array $users): void
    {
        $articles = [
            [
                'title' => '2024 年最适合普通人的 10 个 AI 副业项目',
                'slug' => 'top-10-ai-side-hustles-2024',
                'summary' => '盘点 2024 年门槛最低、变现最快的 AI 副业项目，总有一个适合你',
                'content' => '<p>随着 AI 技术的普及，越来越多的副业机会涌现...</p>',
                'author_id' => $users[0]['id'] ?? 1,
                'category_id' => Category::where('slug', 'side-projects')->first()?->id,
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => '从零开始做 AI 绘画，我如何月入 2 万',
                'slug' => 'ai-art-monthly-income',
                'summary' => '真实案例分享，从 0 到 1 的完整过程和变现方法',
                'content' => '<p>3 个月前我还是一个 AI 小白，现在已经靠 AI 绘画月入 2 万+...</p>',
                'author_id' => $users[0]['id'] ?? 1,
                'category_id' => Category::where('slug', 'monetization')->first()?->id,
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Midjourney 新手入门教程（2024 最新版）',
                'slug' => 'midjourney-tutorial-2024',
                'summary' => '从注册到出图，手把手教你使用 Midjourney',
                'content' => '<p>本教程包含：账号注册、基础命令、参数详解、高级技巧...</p>',
                'author_id' => $users[0]['id'] ?? 1,
                'category_id' => Category::where('slug', 'learning')->first()?->id,
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'GPT-4 提示词工程：写出高质量 Prompt 的 7 个技巧',
                'slug' => 'gpt4-prompt-engineering',
                'summary' => '掌握这些技巧，让 AI 输出更符合你的预期',
                'content' => '<p>提示词工程是 AI 时代的核心技能之一...</p>',
                'author_id' => $users[0]['id'] ?? 1,
                'category_id' => Category::where('slug', 'learning')->first()?->id,
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'AI 工具周报：第 12 期（3.17-3.24）',
                'slug' => 'ai-tools-weekly-12',
                'summary' => '本周 AI 工具动态：Sora 开放测试、Claude 3 发布、GPT-5 传闻',
                'content' => '<p>本周 AI 圈发生了很多大事...</p>',
                'author_id' => $users[0]['id'] ?? 1,
                'category_id' => Category::where('slug', 'news')->first()?->id,
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Stable Diffusion 本地部署完整指南',
                'slug' => 'stable-diffusion-local-setup',
                'summary' => 'Windows/Mac/Linux全平台部署教程，包含常见问题解决',
                'content' => '<p>Stable Diffusion 是最流行的开源 AI 绘画工具...</p>',
                'author_id' => $users[0]['id'] ?? 1,
                'category_id' => Category::where('slug', 'learning')->first()?->id,
                'is_published' => true,
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'AI 写作变现：从入门到精通',
                'slug' => 'ai-writing-monetization',
                'summary' => '公众号文案、知乎回答、小红书笔记，全方位变现指南',
                'content' => '<p>AI 写作是目前门槛最低的 AI 副业...</p>',
                'author_id' => $users[0]['id'] ?? 1,
                'category_id' => Category::where('slug', 'monetization')->first()?->id,
                'is_published' => true,
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => '2024 AI 行业趋势分析',
                'slug' => 'ai-industry-trends-2024',
                'summary' => '深度分析 2024 年 AI 行业的发展方向和机会',
                'content' => '<p>2024 年 AI 行业将呈现以下趋势...</p>',
                'author_id' => $users[0]['id'] ?? 1,
                'category_id' => Category::where('slug', 'news')->first()?->id,
                'is_published' => true,
                'published_at' => now()->subDays(30),
            ],
        ];

        foreach ($articles as $article) {
            Article::firstOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }

    private function createEmailLogs(): void
    {
        $emails = [
            ['recipient' => '2801359160@qq.com', 'subject' => '🤖 AI & 副业资讯日报 - 2026-03-24', 'type' => 'job_daily', 'status' => 'sent'],
            ['recipient' => '2801359160@qq.com', 'subject' => '🤖 AI & 副业资讯日报 - 2026-03-23', 'type' => 'job_daily', 'status' => 'sent'],
            ['recipient' => '2801359160@qq.com', 'subject' => '🤖 AI & 副业资讯日报 - 2026-03-22', 'type' => 'job_daily', 'status' => 'sent'],
            ['recipient' => 'vip1@example.com', 'subject' => '🎉 欢迎加入 AI 副业情报局！', 'type' => 'welcome', 'status' => 'sent'],
            ['recipient' => 'user1@example.com', 'subject' => '🎉 欢迎加入 AI 副业情报局！', 'type' => 'welcome', 'status' => 'sent'],
            ['recipient' => '2801359160@qq.com', 'subject' => '📊 本周 AI 副业精选 - 第 12 周', 'type' => 'weekly', 'status' => 'sent'],
            ['recipient' => 'test@example.com', 'subject' => '🧪 邮件测试 - AI 副业情报局', 'type' => 'test', 'status' => 'failed'],
        ];

        foreach ($emails as $email) {
            EmailLog::create([
                'recipient' => $email['recipient'],
                'subject' => $email['subject'],
                'content' => '测试邮件内容...',
                'type' => $email['type'],
                'status' => $email['status'],
                'sent_at' => $email['status'] === 'sent' ? now()->subDays(rand(0, 7)) : null,
            ]);
        }
    }

    private function createJobListings(): void
    {
        $jobs = [
            [
                'title' => '高级 PHP 开发工程师',
                'company' => '某科技公司',
                'location' => '杭州',
                'salary' => '15-30k',
                'description' => '负责核心业务开发，要求 3 年以上 PHP 经验',
                'url' => 'https://example.com/job/1',
                'source' => 'boss',
            ],
            [
                'title' => 'PHP 全栈工程师',
                'company' => '创业公司 A',
                'location' => '杭州',
                'salary' => '12-25k',
                'description' => 'PHP+Vue全栈开发，有创业经验者优先',
                'url' => 'https://example.com/job/2',
                'source' => 'boss',
            ],
            [
                'title' => '资深后端工程师（PHP）',
                'company' => '互联网大厂',
                'location' => '杭州',
                'salary' => '20-40k',
                'description' => '高并发系统设计，微服务架构经验',
                'url' => 'https://example.com/job/3',
                'source' => 'boss',
            ],
            [
                'title' => 'PHP 技术专家',
                'company' => '电商平台',
                'location' => '杭州',
                'salary' => '25-45k',
                'description' => '技术架构设计，团队管理，代码 review',
                'url' => 'https://example.com/job/4',
                'source' => 'boss',
            ],
            [
                'title' => 'PHP 开发工程师',
                'company' => '金融科技公司',
                'location' => '杭州',
                'salary' => '10-20k',
                'description' => '金融系统开发，熟悉支付流程优先',
                'url' => 'https://example.com/job/5',
                'source' => 'boss',
            ],
        ];

        foreach ($jobs as $job) {
            JobListing::firstOrCreate(
                ['url' => $job['url']],
                $job
            );
        }
    }
}
