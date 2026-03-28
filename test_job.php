<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Jobs\ProcessAsyncTaskJob;
use App\Models\AsyncTask;

echo "创建测试任务...\n";
$task = AsyncTask::createTask('测试任务', 'fetch_articles');
echo "任务创建成功，ID: {$task->id}\n";

echo "执行任务...\n";
try {
    $job = new ProcessAsyncTaskJob($task);
    $job->handle();
    echo "✅ 任务执行成功！\n";
} catch (Exception $e) {
    echo "❌ 执行失败：" . $e->getMessage() . "\n";
    echo "堆栈：\n" . $e->getTraceAsString() . "\n";
}
