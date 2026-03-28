#!/usr/bin/env php
<?php

/**
 * 浏览历史功能测试脚本
 */

require __DIR__.'/vendor/autoload.php';

echo "🧪 浏览历史功能测试\n";
echo "==================\n\n";

$passed = 0;
$failed = 0;

// 测试 1：ViewHistory 模型语法检查
echo "测试 1: ViewHistory 模型语法检查... ";
$modelFile = __DIR__.'/app/Models/ViewHistory.php';
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

// 测试 2：HistoryController 语法检查
echo "测试 2: HistoryController 语法检查... ";
$controllerFile = __DIR__.'/app/Http/Controllers/HistoryController.php';
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

// 测试 3：迁移文件语法检查
echo "测试 3: 迁移文件语法检查... ";
$migrationFile = __DIR__.'/database/migrations/2026_03_25_000002_create_view_histories_table.php';
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

// 测试 4：路由配置检查
echo "测试 4: 路由配置检查... ";
$routeFile = __DIR__.'/routes/web.php';
if (file_exists($routeFile)) {
    $content = file_get_contents($routeFile);
    if (strpos($content, 'HistoryController') !== false &&
        strpos($content, 'history.index') !== false) {
        echo "✅ 通过 (路由已配置)\n";
        $passed++;
    } else {
        echo "❌ 失败 (路由未配置)\n";
        $failed++;
    }
} else {
    echo "❌ 文件不存在\n";
    $failed++;
}

// 测试 5：浏览历史视图文件检查
echo "测试 5: 浏览历史视图文件检查... ";
$viewFile = __DIR__.'/resources/views/history/index.blade.php';
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

// 测试 6：视图内容检查
echo "测试 6: 视图内容检查... ";
$viewContent = file_get_contents($viewFile);
$requiredElements = [
    '浏览历史',
    '清空历史',
    '全部历史',
    '项目历史',
    '文章历史',
    '暂无浏览历史',
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

// 测试 7：ArticleController 浏览记录检查
echo "测试 7: ArticleController 浏览记录检查... ";
$articleController = file_get_contents(__DIR__.'/app/Http/Controllers/ArticleController.php');
if (strpos($articleController, 'ViewHistory::record') !== false) {
    echo "✅ 通过 (已添加浏览记录)\n";
    $passed++;
} else {
    echo "❌ 失败 (未添加浏览记录)\n";
    $failed++;
}

// 测试 8：ProjectController 浏览记录检查
echo "测试 8: ProjectController 浏览记录检查... ";
$projectController = file_get_contents(__DIR__.'/app/Http/Controllers/ProjectController.php');
if (strpos($projectController, 'ViewHistory::record') !== false) {
    echo "✅ 通过 (已添加浏览记录)\n";
    $passed++;
} else {
    echo "❌ 失败 (未添加浏览记录)\n";
    $failed++;
}

// 总结
echo "\n";
echo "==================\n";
echo "测试结果：{$passed} 通过，{$failed} 失败\n";

if ($failed === 0) {
    echo "🎉 所有测试通过！\n\n";
    echo "📝 下一步操作：\n";
    echo "1. 运行数据库迁移：docker compose exec php php artisan migrate\n";
    echo "2. 登录用户访问项目和文章会自动记录浏览历史\n";
    echo "3. 访问 /history 查看浏览历史列表\n";
    echo "4. 测试清空历史功能\n";
    exit(0);
} else {
    echo "⚠️  有测试失败，请检查代码\n";
    exit(1);
}
