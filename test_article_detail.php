#!/usr/bin/env php
<?php

/**
 * 文章详情页功能测试脚本
 */

require __DIR__.'/vendor/autoload.php';

echo "🧪 文章详情页功能测试\n";
echo "====================\n\n";

$passed = 0;
$failed = 0;

// 测试 1：Article 模型语法检查
echo "测试 1: Article 模型语法检查... ";
$modelFile = __DIR__.'/app/Models/Article.php';
if (file_exists($modelFile)) {
    $output = shell_exec("php -l {$modelFile} 2>&1");
    if (strpos($output, 'No syntax errors') !== false) {
        echo "✅ 通过\n";
        $passed++;
    } else {
        echo "❌ 失败\n";
        echo "错误：{$output}\n";
        $failed++;
    }
} else {
    echo "❌ 文件不存在\n";
    $failed++;
}

// 测试 2：ArticleController 语法检查
echo "测试 2: ArticleController 语法检查... ";
$controllerFile = __DIR__.'/app/Http/Controllers/ArticleController.php';
if (file_exists($controllerFile)) {
    $output = shell_exec("php -l {$controllerFile} 2>&1");
    if (strpos($output, 'No syntax errors') !== false) {
        echo "✅ 通过\n";
        $passed++;
    } else {
        echo "❌ 失败\n";
        echo "错误：{$output}\n";
        $failed++;
    }
} else {
    echo "❌ 文件不存在\n";
    $failed++;
}

// 测试 3：路由配置检查
echo "测试 3: 路由配置检查... ";
$routeFile = __DIR__.'/routes/web.php';
if (file_exists($routeFile)) {
    $output = shell_exec("php -l {$routeFile} 2>&1");
    if (strpos($output, 'No syntax errors') !== false) {
        echo "✅ 通过\n";
        $passed++;
    } else {
        echo "❌ 失败\n";
        echo "错误：{$output}\n";
        $failed++;
    }
} else {
    echo "❌ 文件不存在\n";
    $failed++;
}

// 测试 4：文章详情页视图文件检查
echo "测试 4: 文章详情页视图文件检查... ";
$viewFile = __DIR__.'/resources/views/articles/show.blade.php';
if (file_exists($viewFile)) {
    $size = filesize($viewFile);
    if ($size > 5000) {
        echo "✅ 通过 (文件大小：" . number_format($size) . " 字节)\n";
        $passed++;
    } else {
        echo "❌ 文件太小\n";
        $failed++;
    }
} else {
    echo "❌ 文件不存在\n";
    $failed++;
}

// 测试 5：视图内容检查
echo "测试 5: 视图内容检查... ";
$viewContent = file_get_contents($viewFile);
$requiredElements = [
    'reading-progress',
    'author',
    '相关文章',
    '收藏',
    '点赞',
    '评论',
    'VIP',
];

$missingElements = [];
foreach ($requiredElements as $element) {
    if (strpos($viewContent, $element) === false) {
        $missingElements[] = $element;
    }
}

if (empty($missingElements)) {
    echo "✅ 通过 (所有关键元素都存在)\n";
    $passed++;
} else {
    echo "❌ 失败 (缺少：" . implode(', ', $missingElements) . ")\n";
    $failed++;
}

// 测试 6：VIP 遮罩组件检查
echo "测试 6: VIP 遮罩组件检查... ";
$overlayFile = __DIR__.'/resources/views/components/vip-article-overlay.blade.php';
if (file_exists($overlayFile)) {
    $size = filesize($overlayFile);
    if ($size > 1000) {
        echo "✅ 通过 (文件大小：" . number_format($size) . " 字节)\n";
        $passed++;
    } else {
        echo "❌ 文件太小\n";
        $failed++;
    }
} else {
    echo "❌ 文件不存在\n";
    $failed++;
}

// 总结
echo "\n";
echo "====================\n";
echo "测试结果：{$passed} 通过，{$failed} 失败\n";

if ($failed === 0) {
    echo "🎉 所有测试通过！\n\n";
    echo "📝 下一步操作：\n";
    echo "1. 在 Docker 容器内运行迁移（如需要）：docker compose exec php php artisan migrate\n";
    echo "2. 访问文章详情页测试功能\n";
    echo "3. 测试 VIP 遮罩功能\n";
    echo "4. 测试评论功能\n";
    exit(0);
} else {
    echo "⚠️  有测试失败，请检查代码\n";
    exit(1);
}
