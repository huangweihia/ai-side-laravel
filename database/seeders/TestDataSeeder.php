<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\EmailLog;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 开始生成测试数据...');
        
        $this->command->info('📁 创建分类...');
        $this->createCategories();
        
        $this->command->info('👥 创建用户...');
        $users = $this->createUsers();
        
        $this->command->info('🚀 创建项目...');
        $this->createProjects();
        
        $this->command->info('📰 创建文章...');
        $this->createArticles($users);
        
        $this->command->info('📧 创建邮件日志...');
        $this->createEmailLogs();
        
        $this->command->info('');
        $this->command->info('✅ 测试数据生成完成！');
        $this->command->info('   分类：' . Category::count());
        $this->command->info('   用户：' . User::count());
        $this->command->info('   项目：' . Project::count());
        $this->command->info('   文章：' . Article::count());
        $this->command->info('   邮件日志：' . EmailLog::count());
    }

    private function createCategories(): void
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
    }

    private function createUsers(): array
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => '管理员', 'password' => bcrypt('password123'), 'role' => 'admin']
        );

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

        $normalUsers = [
            ['name' => '小明', 'email' => 'user1@example.com'],
            ['name' => '小红', 'email' => 'user2@example.com'],
            ['name' => '小刚', 'email' => 'user3@example.com'],
        ];

        foreach ($normalUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                ['name' => $userData['name'], 'password' => bcrypt('password123'), 'role' => 'user']
            );
        }

        return User::all()->toArray();
    }

    private function createProjects(): void
    {
        $projects = [
            [
                'name' => 'ai-writer',
                'full_name' => 'AI 写作助手',
                'description' => '基于 GPT-4 的智能写作工具，支持文章生成、润色、改写等功能',
                'url' => 'https://github.com/example/ai-writer',
                'language' => 'Python',
                'stars' => 15420,
                'forks' => 2340,
                'score' => 8.5,
                'tags' => ['AI', '写作', 'GPT'],
                'monetization' => 'high',
                'difficulty' => 'medium',
                'revenue' => '1000-5000 元/月',
                'is_featured' => true,
            ],
            [
                'name' => 'ai-art',
                'full_name' => 'AI 绘画变现',
                'description' => '使用 Midjourney 和 Stable Diffusion 接商单，月入过万的副业项目',
                'url' => 'https://example.com/ai-art',
                'language' => 'JavaScript',
                'stars' => 8920,
                'forks' => 1230,
                'score' => 7.8,
                'tags' => ['AI 绘画', 'Midjourney', '副业'],
                'monetization' => 'high',
                'difficulty' => 'easy',
                'revenue' => '10000+ 元/月',
                'is_featured' => true,
            ],
            [
                'name' => 'ai-chatbot',
                'full_name' => 'AI 客服机器人',
                'description' => '为企业定制 AI 客服系统，按项目收费，单项目 5000-30000 元',
                'url' => 'https://github.com/example/ai-chatbot',
                'language' => 'Python',
                'stars' => 12300,
                'forks' => 1890,
                'score' => 8.2,
                'tags' => ['AI', '客服', '企业服务'],
                'monetization' => 'high',
                'difficulty' => 'hard',
                'revenue' => '5000-20000 元/月',
                'is_featured' => false,
            ],
            [
                'name' => 'ai-video',
                'full_name' => 'AI 生成短视频',
                'description' => '批量生成短视频内容，通过流量分成和带货变现',
                'url' => 'https://example.com/ai-video',
                'language' => 'Python',
                'stars' => 6540,
                'forks' => 890,
                'score' => 7.5,
                'tags' => ['AI', '短视频', '自媒体'],
                'monetization' => 'medium',
                'difficulty' => 'medium',
                'revenue' => '3000-15000 元/月',
                'is_featured' => false,
            ],
            [
                'name' => 'ai-translate',
                'full_name' => 'AI 翻译服务',
                'description' => '接入 DeepL 和大模型 API，提供专业领域翻译服务',
                'url' => 'https://github.com/example/ai-translate',
                'language' => 'Node.js',
                'stars' => 4320,
                'forks' => 670,
                'score' => 7.0,
                'tags' => ['AI', '翻译', 'API'],
                'monetization' => 'medium',
                'difficulty' => 'easy',
                'revenue' => '2000-8000 元/月',
                'is_featured' => false,
            ],
        ];

        foreach ($projects as $project) {
            try {
                Project::firstOrCreate(
                    ['name' => $project['name']],
                    $project
                );
                $this->command->info("  ✅ " . $project['full_name']);
            } catch (\Exception $e) {
                $this->command->warn("  ⚠️ 项目创建失败：" . $project['full_name']);
            }
        }
    }

    private function createArticles(array $users): void
    {
        $articles = [
            [
                'title' => '2024 年最适合普通人的 10 个 AI 副业项目',
                'slug' => 'top-10-ai-side-hustles-2024',
                'summary' => '盘点 2024 年门槛最低、变现最快的 AI 副业项目',
                'content' => '<p>随着 AI 技术的普及，越来越多的副业机会涌现...</p>',
                'author_id' => $users[0]['id'] ?? 1,
                'category_id' => Category::where('slug', 'side-projects')->first()?->id,
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => '从零开始做 AI 绘画，我如何月入 2 万',
                'slug' => 'ai-art-monthly-income',
                'summary' => '真实案例分享，从 0 到 1 的完整过程',
                'content' => '<p>3 个月前我还是一个 AI 小白...</p>',
                'author_id' => $users[0]['id'] ?? 1,
                'category_id' => Category::where('slug', 'monetization')->first()?->id,
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
        ];

        foreach ($articles as $article) {
            try {
                Article::firstOrCreate(['slug' => $article['slug']], $article);
                $this->command->info("  ✅ " . $article['title']);
            } catch (\Exception $e) {
                $this->command->warn("  ⚠️ 文章创建失败：" . $article['title']);
            }
        }
    }

    private function createEmailLogs(): void
    {
        $emails = [
            ['recipient' => '2801359160@qq.com', 'subject' => '🤖 AI 副业资讯日报 - 2026-03-24', 'type' => 'job_daily', 'status' => 'sent'],
            ['recipient' => 'vip1@example.com', 'subject' => '🎉 欢迎加入 AI 副业情报局！', 'type' => 'welcome', 'status' => 'sent'],
        ];

        foreach ($emails as $email) {
            EmailLog::create([
                'recipient' => $email['recipient'],
                'subject' => $email['subject'],
                'content' => '测试邮件内容...',
                'type' => $email['type'],
                'status' => $email['status'],
                'sent_at' => now()->subDays(rand(0, 7)),
            ]);
        }
    }
}
