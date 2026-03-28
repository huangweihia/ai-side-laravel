#!/usr/bin/env php
<?php

/**
 * 收藏列表功能测试脚本
 */

require __DIR__.'/vendor/autoload.php';

echo "🧪 收藏列表功能测试\n";
echo "==================\n\n";

$passed = 0;
$failed = 0;

// 测试 1：FavoriteController 语法检查
echo "测试 1: FavoriteController 语法检查... ";
$controllerFile = __DIR__.'/app/Http/Controllers/FavoriteController.php';
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

// 测试 2：路由配置检查
echo "测试 2: 路由配置检查... ";
$routeFile = __DIR__.'/routes/web.php';
if (file_exists($routeFile)) {
    $content = file_get_contents($routeFile);
    if (strpos($content, 'FavoriteController') !== false &&
        strpos($content, 'favorites.index') !== false) {
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

// 测试 3：收藏列表视图文件检查
echo "测试 3: 收藏列表视图文件检查... ";
$viewFile = __DIR__.'/resources/views/favorites/index.blade.php';
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

// 测试 4：视图内容检查
echo "测试 4: 视图内容检查... ";
$viewContent = file_get_contents($viewFile);
$requiredElements = [
    '我的收藏',
    '取消收藏',
    '全部收藏',
    '收藏项目',
    '收藏文章',
    '暂无收藏',
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

// 测试 5：Favorite 模型检查
echo "测试 5: Favorite 模型检查... ";
$modelFile = __DIR__.'/app/Models/Favorite.php';
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

// 总结
echo "\n";
echo "==================\n";
echo "测试结果：{$passed} 通过，{$failed} 失败\n";

if ($failed === 0) {
    echo "🎉 所有测试通过！\n\n";
    echo "📝 下一步操作：\n";
    echo "1. 登录用户访问 /favorites 查看收藏列表\n";
    echo "2. 测试分类筛选功能\n";
    echo "3. 测试取消收藏功能\n";
    exit(0);
} else {
    echo "⚠️  有测试失败，请检查代码\n";
    exit(1);
}
