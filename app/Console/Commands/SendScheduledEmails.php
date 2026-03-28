<?php

namespace App\Console\Commands;

use App\Models\EmailSubscription;
use App\Models\EmailTemplate;
use App\Models\EmailLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendScheduledEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send-scheduled {--limit=100 : 每次发送的最大数量}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送定时邮件（日报/周报）';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int) $this->option('limit');
        $this->info("开始发送定时邮件，限制数量：{$limit}");

        $today = now();
        $isMonday = $today->isMonday();

        // 获取待发送的邮件订阅用户
        $subscriptions = EmailSubscription::with(['user'])
            ->where('unsubscribed_at', null)
            ->where(function($query) use ($isMonday) {
                $query->where($isMonday ? 'subscribed_to_weekly' : 'subscribed_to_daily', true);
            })
            ->limit($limit)
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('没有待发送的邮件订阅用户');
            return 0;
        }

        $this->info("找到 {$subscriptions->count()} 个订阅用户");

        // 获取邮件模板
        $templateKey = $isMonday ? 'weekly_summary' : 'daily_digest';
        $template = EmailTemplate::where('key', $templateKey)->first();
        
        if (!$template) {
            $this->warn("未找到邮件模板 ({$templateKey})，跳过发送");
            return 0;
        }

        $this->info("使用模板：{$template->name}");

        $sentCount = 0;
        $failedCount = 0;

        foreach ($subscriptions as $subscription) {
            try {
                // 检查用户是否存在
                if (!$subscription->user) {
                    $this->warn("用户 ID {$subscription->user_id} 不存在，跳过");
                    continue;
                }

                // 准备邮件内容
                $subject = str_replace(
                    ['{date}', '{day}', '{user.name}'],
                    [$today->format('Y-m-d'), $today->dayName, $subscription->user->name ?? '用户'],
                    $template->subject
                );

                $content = $this->renderTemplate($template, $subscription->user, $subscription);

                // 发送邮件
                Mail::send(
                    'emails.template',
                    ['content' => $content, 'user' => $subscription->user],
                    function ($message) use ($subscription, $subject) {
                        $message->to($subscription->email)
                                ->subject($subject)
                                ->from(config('mail.from.address'), config('mail.from.name'));
                    }
                );

                // 记录发送日志
                EmailLog::create([
                    'recipient' => $subscription->email,
                    'subject' => $subject,
                    'content' => $content,
                    'type' => $isMonday ? 'weekly_digest' : 'daily_digest',
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                $sentCount++;
                $this->line("✓ 已发送给：{$subscription->email}");

                // 避免发送过快
                usleep(100000); // 100ms 延迟

            } catch (\Exception $e) {
                $failedCount++;
                $this->error("✗ 发送失败 ({$subscription->email}): {$e->getMessage()}");

                // 记录失败日志
                EmailLog::create([
                    'recipient' => $subscription->email,
                    'subject' => $template->subject,
                    'content' => $e->getMessage(),
                    'type' => $isMonday ? 'weekly_digest' : 'daily_digest',
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        $this->info("发送完成！成功：{$sentCount}, 失败：{$failedCount}");
        
        return 0;
    }

    /**
     * 渲染邮件模板
     */
    private function renderTemplate($template, $user, $subscription)
    {
        $content = $template->content;
        $today = now();
        
        // 获取热门项目（示例数据，实际应该从数据库获取）
        $projects = $this->getPopularProjects();
        $sideHustles = $this->getSideHustles();
        $resources = $this->getLearningResources();
        
        // 替换模板变量（支持 {{var}} 和 {var} 两种格式）
        $replacements = [
            // 用户信息
            '{{name}}' => $user->name ?? '用户',
            '{{user.name}}' => $user->name ?? '用户',
            '{name}' => $user->name ?? '用户',
            '{user.name}' => $user->name ?? '用户',
            
            // 日期时间
            '{{date}}' => $today->format('Y-m-d'),
            '{date}' => $today->format('Y-m-d'),
            '{{time}}' => $today->format('H:i'),
            '{time}' => $today->format('H:i'),
            '{{day}}' => $today->dayName,
            '{day}' => $today->dayName,
            
            // 内容区域
            '{{projects}}' => $projects,
            '{projects}' => $projects,
            '{{side_hustles}}' => $sideHustles,
            '{side_hustles}' => $sideHustles,
            '{{resources}}' => $resources,
            '{resources}' => $resources,
            
            // 退订链接
            '{{unsubscribe_url}}' => url('/subscriptions/unsubscribe?token=' . $subscription->unsubscribe_token),
            '{unsubscribe_url}' => url('/subscriptions/unsubscribe?token=' . $subscription->unsubscribe_token),
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $content
        );
    }

    /**
     * 获取热门项目
     */
    private function getPopularProjects()
    {
        $projects = \App\Models\Project::where('is_featured', true)
            ->orderByDesc('stars')
            ->limit(5)
            ->get();
        
        if ($projects->isEmpty()) {
            return '<p>暂无推荐项目</p>';
        }
        
        $html = '<ul style="line-height: 2;">';
        foreach ($projects as $project) {
            $html .= '<li><strong>' . e($project->name) . '</strong>';
            if ($project->url) {
                $html .= ' - <a href="' . e($project->url) . '" style="color: #667eea;">查看项目</a>';
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        
        return $html;
    }

    /**
     * 获取副业灵感
     */
    private function getSideHustles()
    {
        $articles = \App\Models\Article::where('is_published', true)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();
        
        if ($articles->isEmpty()) {
            return '<p>暂无副业灵感</p>';
        }
        
        $html = '<ul style="line-height: 2;">';
        foreach ($articles as $article) {
            $html .= '<li><strong>' . e($article->title) . '</strong>';
            if ($article->slug) {
                $html .= ' - <a href="' . url('/articles/' . $article->slug) . '" style="color: #667eea;">阅读更多</a>';
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        
        return $html;
    }

    /**
     * 获取学习资源
     */
    private function getLearningResources()
    {
        return '<ul style="line-height: 2;">
            <li><a href="https://docs.openclaw.ai" style="color: #667eea;">OpenClaw 文档</a> - AI 助手框架</li>
            <li><a href="https://laravel.com" style="color: #667eea;">Laravel 文档</a> - PHP 框架</li>
            <li><a href="https://vuejs.org" style="color: #667eea;">Vue.js 文档</a> - 前端框架</li>
        </ul>';
    }
}
