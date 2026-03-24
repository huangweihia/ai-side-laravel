<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // 每日上午 10 点发送 AI 副业项目推荐邮件（核心功能）
        $schedule->command('ai-projects:send-daily', ['--email' => '2801359160@qq.com'])
                 ->dailyAt('10:00')
                 ->timezone('Asia/Shanghai')
                 ->withoutOverlapping();
        
        // 每日上午 10 点发送职位推荐邮件（次要功能）
        $schedule->command('jobs:send-daily', ['--email' => '2801359160@qq.com'])
                 ->dailyAt('10:30')
                 ->timezone('Asia/Shanghai')
                 ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
