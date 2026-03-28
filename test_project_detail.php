#!/usr/bin/env php
<?php

/**
 * 项目详情页功能测试脚本
 */

require __DIR__.'/vendor/autoload.php';

echo "🧪 项目详情页功能测试\n";
echo "====================\n\n";

$passed = 0;
$failed = 0;

// 测试 1：迁移文件语法检查
echo "测试 1: 迁移文件语法检查... ";
$migrationFile = __DIR__.'/database/migrations/2026_03_25_000001_enhance_projects_and_create_comments.php';
if (file_exists($migrationFile)) {
    $output = shell_exec("php -l {$migrationFile} 2>&1");
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

// 测试 2：Project 模型语法检查
echo "测试 2: Project 模型语法检查... ";
$modelFile = __DIR__.'/app/Models/Project.php';
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

// 测试 3：Comment 模型语法检查
echo "测试 3: Comment 模型语法检查... ";
$commentFile = __DIR__.'/app/Models/Comment.php';
if (file_exists($commentFile)) {
    $output = shell_exec("php -l {$commentFile} 2>&1");
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

// 测试 4：Favorite 模型语法检查
echo "测试 4: Favorite 模型语法检查... ";
$favoriteFile = __DIR__.'/app/Models/Favorite.php';
if (file_exists($favoriteFile)) {
    $output = shell_exec("php -l {$favoriteFile} 2>&1");
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

// 测试 5：ProjectController 语法检查
echo "测试 5: ProjectController 语法检查... ";
$controllerFile = __DIR__.'/app/Http/Controllers/ProjectController.php';
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

// 测试 6：项目详情页视图文件检查
echo "测试 6: 项目详情页视图文件检查... ";
$viewFile = __DIR__.'/resources/views/projects/show.blade.php';
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

// 测试 7：视图内容检查
echo "测试 7: 视图内容检查... ";
$viewContent = file_get_contents($viewFile);
$requiredElements = [
    '变现分析',
    '技术栈',
    '教程资源',
    '收藏',
    '评论',
    '相关项目',
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

// 测试 8：测试文件检查
echo "测试 8: 测试文件检查... ";
$testFile = __DIR__.'/tests/Feature/ProjectDetailTest.php';
if (file_exists($testFile)) {
    $output = shell_exec("php -l {$testFile} 2>&1");
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

// 总结
echo "\n";
echo "====================\n";
echo "测试结果：{$passed} 通过，{$failed} 失败\n";

if ($failed === 0) {
    echo "🎉 所有测试通过！\n\n";
    echo "📝 下一步操作：\n";
    echo "1. 在 Docker 容器内运行迁移：docker compose exec php php artisan migrate\n";
    echo "2. 访问项目详情页测试功能\n";
    exit(0);
} else {
    echo "⚠️  有测试失败，请检查代码\n";
    exit(1);
}
