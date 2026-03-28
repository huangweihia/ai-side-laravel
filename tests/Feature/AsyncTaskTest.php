<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AsyncTask;
use App\Models\User;
use App\Jobs\ProcessAsyncTaskJob;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AsyncTaskTest extends TestCase
{
    // 不使用 RefreshDatabase，避免清空数据库
    // use RefreshDatabase;

    /** @test */
    public function it_can_create_async_task()
    {
        $task = AsyncTask::createTask('测试任务', 'fetch_articles');
        
        $this->assertEquals('pending', $task->status);
        $this->assertEquals('测试任务', $task->name);
        $this->assertEquals('fetch_articles', $task->type);
    }

    /** @test */
    public function it_can_start_task()
    {
        $task = AsyncTask::createTask('测试任务', 'fetch_articles');
        $task->start();
        
        $this->assertEquals('running', $task->status);
        $this->assertNotNull($task->started_at);
    }

    /** @test */
    public function it_can_complete_task()
    {
        $task = AsyncTask::createTask('测试任务', 'fetch_articles', 100);
        $task->start();
        $task->complete(95, 5);
        
        $this->assertEquals('completed', $task->status);
        $this->assertEquals(100, $task->processed);
        $this->assertEquals(95, $task->success);
        $this->assertEquals(5, $task->failed);
        $this->assertNotNull($task->completed_at);
    }

    /** @test */
    public function it_can_fail_task()
    {
        $task = AsyncTask::createTask('测试任务', 'fetch_articles');
        $task->start();
        $task->fail('测试错误信息');
        
        $this->assertEquals('failed', $task->status);
        $this->assertEquals('测试错误信息', $task->error_message);
        $this->assertNotNull($task->completed_at);
    }

    /** @test */
    public function it_can_update_progress()
    {
        $task = AsyncTask::createTask('测试任务', 'fetch_articles', 100);
        $task->start();
        $task->updateProgress(50, 48, 2);
        
        $this->assertEquals(50, $task->processed);
        $this->assertEquals(48, $task->success);
        $this->assertEquals(2, $task->failed);
        $this->assertEquals(50.0, $task->progress);
    }

    /** @test */
    public function process_async_task_job_dispatches_correctly()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $task = AsyncTask::createTask('文章采集', 'fetch_articles', 0, ['user_id' => $user->id]);
        
        // 测试 Job 可以正常实例化
        $job = new ProcessAsyncTaskJob($task);
        
        $this->assertInstanceOf(ProcessAsyncTaskJob::class, $job);
        $this->assertEquals($task->id, $job->task->id);
    }
}
