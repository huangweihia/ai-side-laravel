#!/usr/bin/env php
<?php

/**
 * 首页功能测试脚本
 * 不依赖数据库，只测试代码语法和路由
 */

require __DIR__.'/vendor/autoload.php';

echo "🧪 首页功能测试\n";
echo "==============\n\n";

$passed = 0;
$failed = 0;

// 测试 1：HomeController 语法检查
echo "测试 1: HomeController 语法检查... ";
$controllerFile = __DIR__.'/app/Http/Controllers/HomeController.php';
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

// 测试 2：首页视图文件检查
echo "测试 2: 首页视图文件检查... ";
$viewFile = __DIR__.'/resources/views/home/index.blade.php';
if (file_exists($viewFile)) {
    $size = filesize($viewFile);
    if ($size > 1000) { // 确保文件有内容
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

// 测试 3：路由检查
echo "测试 3: 路由配置检查... ";
try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    $routes = \Route::getRoutes();
    $homeRoute = null;
    
    foreach ($routes as $route) {
        if ($route->uri() === '/' && in_array('GET', $route->methods())) {
            $homeRoute = $route;
            break;
        }
    }
    
    if ($homeRoute) {
        echo "✅ 通过 (路由存在)\n";
        $passed++;
    } else {
        echo "❌ 失败 (路由不存在)\n";
        $failed++;
    }
} catch (\Exception $e) {
    echo "❌ 失败 (" . $e->getMessage() . ")\n";
    $failed++;
}

// 测试 4：HomeController 方法检查
echo "测试 4: HomeController 方法检查... ";
if (class_exists('App\Http\Controllers\HomeController')) {
    $controller = new \App\Http\Controllers\HomeController();
    if (method_exists($controller, 'index')) {
        echo "✅ 通过 (index 方法存在)\n";
        $passed++;
    } else {
        echo "❌ 失败 (index 方法不存在)\n";
        $failed++;
    }
} else {
    echo "❌ 失败 (类不存在)\n";
    $failed++;
}

// 测试 5：视图内容检查
echo "测试 5: 视图内容检查... ";
$viewContent = file_get_contents($viewFile);
$requiredElements = [
    '每天 10 分钟，发现 AI 副业机会',
    '今日精选项目',
    '今日精选文章',
    '热门分类',
    '免费注册',
    '开通 VIP',
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

// 总结
echo "\n";
echo "==============\n";
echo "测试结果：{$passed} 通过，{$failed} 失败\n";

if ($failed === 0) {
    echo "🎉 所有测试通过！\n";
    exit(0);
} else {
    echo "⚠️  有测试失败，请检查代码\n";
    exit(1);
}
