<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

echo "=== OpenClaw Webhook API 测试 ===\n\n";

// 测试数据
$testData = [
    'articles' => [
        'type' => 'articles',
        'items' => [
            [
                'title' => 'GPT-5 正式发布：性能提升 10 倍',
                'summary' => 'OpenAI 今日发布 GPT-5，性能全面提升',
                'content' => 'OpenAI 今日正式发布 GPT-5，相比 GPT-4 性能提升 10 倍...',
                'url' => 'https://example.com/gpt5-' . time(),
            ],
            [
                'title' => 'AI 智能体开发入门教程',
                'summary' => '手把手教你开发 AI 智能体',
                'content' => '本文将详细介绍 AI 智能体的开发流程...',
                'url' => 'https://example.com/agent-' . time(),
            ],
        ],
    ],
    'projects' => [
        'type' => 'projects',
        'items' => [
            [
                'name' => 'test-project-' . time(),
                'description' => '这是一个测试项目',
                'url' => 'https://github.com/test/test-' . time(),
                'stars' => 1000,
                'forks' => 100,
                'language' => 'Python',
            ],
        ],
    ],
];

echo "1️⃣ 测试文章保存...\n";
$saved = 0;
foreach ($testData['articles']['items'] as $item) {
    try {
        Article::firstOrCreate(
            ['source_url' => $item['url']],
            [
                'title' => $item['title'],
                'slug' => \Illuminate\Support\Str::slug($item['title']) . '-' . time(),
                'summary' => $item['summary'],
                'content' => $item['content'],
                'is_published' => true,
                'published_at' => now(),
            ]
        );
        echo "   ✅ 保存成功：{$item['title']}\n";
        $saved++;
    } catch (\Exception $e) {
        echo "   ❌ 保存失败：" . $e->getMessage() . "\n";
    }
}
echo "   共保存 {$saved} 篇文章\n\n";

echo "2️⃣ 测试项目保存...\n";
$saved = 0;
$category = \App\Models\Category::firstOrCreate(['slug' => 'ai-tools'], ['name' => 'AI 工具']);
foreach ($testData['projects']['items'] as $item) {
    try {
        Project::firstOrCreate(
            ['url' => $item['url']],
            [
                'name' => $item['name'],
                'full_name' => $item['name'],
                'description' => $item['description'],
                'stars' => $item['stars'],
                'forks' => $item['forks'],
                'language' => $item['language'],
                'category_id' => $category->id,
                'monetization' => 'medium',
                'difficulty' => 'medium',
                'is_featured' => false,
                'collected_at' => now(),
            ]
        );
        echo "   ✅ 保存成功：{$item['name']}\n";
        $saved++;
    } catch (\Exception $e) {
        echo "   ❌ 保存失败：" . $e->getMessage() . "\n";
    }
}
echo "   共保存 {$saved} 个项目\n\n";

echo "3️⃣ 验证数据库...\n";
$latestArticle = Article::latest()->first();
if ($latestArticle) {
    echo "   ✅ 最新文章：{$latestArticle->title}\n";
} else {
    echo "   ❌ 无文章数据\n";
}

$latestProject = Project::latest()->first();
if ($latestProject) {
    echo "   ✅ 最新项目：{$latestProject->name}\n";
} else {
    echo "   ❌ 无项目数据\n";
}

echo "\n=== 测试完成 ===\n";
