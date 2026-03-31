<?php

namespace App\Services;

use App\Models\Article;
use App\Models\EmailLog;
use App\Models\EmailSubscription;
use App\Models\EmailTemplate;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

/**
 * 订阅日报/周报发送（与命令 emails:send-scheduled 共用逻辑，后台可点按钮复测）。
 */
class SubscriptionDigestMailer
{
    /** 与 Filament Toggle 落库一致：兼容 tinyint 1、布尔 true、字符串 "1" */
    private function scopeSubscribedToColumn(Builder $query, string $column): Builder
    {
        return $query->where(function (Builder $q) use ($column): void {
            $q->where($column, true)
                ->orWhere($column, 1)
                ->orWhere($column, '1');
        });
    }

    /**
     * 构建待发列表（未全局退订 + 当前送信日对应的订阅开关为开启）。
     */
    public function queryRecipients(bool $isMonday, ?string $onlyEmail, int $limit): Collection
    {
        /** @var Builder $q */
        $q = EmailSubscription::query()->with(['user'])->whereNull('unsubscribed_at');

        if ($onlyEmail !== null && $onlyEmail !== '') {
            $q->where('email', $onlyEmail);
            $q->where(function (Builder $inner): void {
                $inner
                    ->where(function (Builder $d): void {
                        $d->where('subscribed_to_daily', true)
                            ->orWhere('subscribed_to_daily', 1)
                            ->orWhere('subscribed_to_daily', '1');
                    })
                    ->orWhere(function (Builder $w): void {
                        $w->where('subscribed_to_weekly', true)
                            ->orWhere('subscribed_to_weekly', 1)
                            ->orWhere('subscribed_to_weekly', '1');
                    });
            });

            return $q->limit(1)->get();
        }

        $column = $isMonday ? 'subscribed_to_weekly' : 'subscribed_to_daily';

        return $this->scopeSubscribedToColumn($q, $column)
            ->limit(max(1, $limit))
            ->get();
    }

    /**
     * @return array{sent: int, failed: int, lines: list<string>}
     */
    public function runBatch(int $limit, ?string $onlyEmail = null): array
    {
        $today = now();
        $isMonday = $today->isMonday();
        $onlyEmailNorm = $onlyEmail !== null && $onlyEmail !== '' ? $onlyEmail : null;

        $subscriptions = $this->queryRecipients($isMonday, $onlyEmailNorm, $onlyEmailNorm ? 1 : $limit);

        $templateKey = ($onlyEmailNorm !== null || ! $isMonday) ? 'daily_digest' : 'weekly_summary';
        $template = EmailTemplate::query()->where('key', $templateKey)->first();

        if (! $template) {
            return [
                'sent' => 0,
                'failed' => 0,
                'lines' => ["未找到邮件模板 ({$templateKey})，请先执行迁移或 Seeder。"],
            ];
        }

        if ($subscriptions->isEmpty()) {
            return [
                'sent' => 0,
                'failed' => 0,
                'lines' => ['没有符合条件的订阅（未退订且当前送信日对应的订阅项为「开」）。'],
            ];
        }

        $sent = 0;
        $failed = 0;
        $lines = [];

        foreach ($subscriptions as $subscription) {
            try {
                $this->sendOneSubscription($subscription, $template, $templateKey, $today);
                $sent++;
                $lines[] = "✓ {$subscription->email}";
                usleep(100000);
            } catch (\Throwable $e) {
                $failed++;
                $lines[] = "✗ {$subscription->email}: ".$e->getMessage();
                EmailLog::query()->create([
                    'recipient' => $subscription->email,
                    'subject' => $template->subject,
                    'content' => $e->getMessage(),
                    'type' => $templateKey === 'weekly_summary' ? 'weekly_digest' : 'daily_digest',
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        return ['sent' => $sent, 'failed' => $failed, 'lines' => $lines];
    }

    /**
     * 单行「立即发送」：与 `emails:send-scheduled --email=` 一致（非周一用日报模板；周一仍可用日报测试）。
     */
    public function runForSingleEmail(string $email): array
    {
        return $this->runBatch(1, $email);
    }

    public function sendOneSubscription(
        EmailSubscription $subscription,
        EmailTemplate $template,
        string $templateKey,
        \DateTimeInterface $today,
    ): void {
        $user = $subscription->user;
        if (! $user) {
            $user = new \App\Models\User([
                'name' => explode('@', (string) $subscription->email)[0] ?: '用户',
                'email' => $subscription->email,
            ]);
            $user->exists = false;
        }

        $dateStr = \Carbon\Carbon::instance($today)->format('Y-m-d');
        $dayName = \Carbon\Carbon::instance($today)->dayName;
        $userName = $user->name ?? '用户';

        // 主题变量替换：兼容 {date} 与 {{date}} 两种写法
        $subject = str_replace(
            ['{{date}}', '{date}', '{{day}}', '{day}', '{{user.name}}', '{user.name}', '{{name}}', '{name}'],
            [$dateStr, $dateStr, $dayName, $dayName, $userName, $userName, $userName, $userName],
            (string) $template->subject
        );

        // 兜底：如果主题里出现 "{2026-03-31}" 这类包裹日期的花括号，自动去掉
        $subject = preg_replace('/\{(\d{4}-\d{2}-\d{2})\}/', '$1', $subject) ?? $subject;

        $content = $this->renderTemplateContent($template, $user, $subscription, $today);

        Mail::send(
            'emails.template',
            ['content' => $content, 'user' => $user],
            function ($message) use ($subscription, $subject): void {
                $message->to($subscription->email)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            }
        );

        EmailLog::query()->create([
            'recipient' => $subscription->email,
            'subject' => $subject,
            'content' => $content,
            'type' => $templateKey === 'weekly_summary' ? 'weekly_digest' : 'daily_digest',
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    private function renderTemplateContent(
        EmailTemplate $template,
        $user,
        EmailSubscription $subscription,
        \DateTimeInterface $today,
    ): string {
        $content = $template->content;
        $c = \Carbon\Carbon::instance($today);

        $projects = $this->getPopularProjects();
        $sideHustles = $this->getSideHustles();
        $resources = $this->getLearningResources();

        $replacements = [
            '{{name}}' => $user->name ?? '用户',
            '{{user.name}}' => $user->name ?? '用户',
            '{name}' => $user->name ?? '用户',
            '{user.name}' => $user->name ?? '用户',
            '{{date}}' => $c->format('Y-m-d'),
            '{date}' => $c->format('Y-m-d'),
            '{{time}}' => $c->format('H:i'),
            '{time}' => $c->format('H:i'),
            '{{day}}' => $c->dayName,
            '{day}' => $c->dayName,
            '{{projects}}' => $projects,
            '{projects}' => $projects,
            '{{side_hustles}}' => $sideHustles,
            '{side_hustles}' => $sideHustles,
            '{{resources}}' => $resources,
            '{resources}' => $resources,
            '{{issue_number}}' => (string) $c->dayOfYear,
            '{issue_number}' => (string) $c->dayOfYear,
            '{{unsubscribe_url}}' => url('/subscriptions/unsubscribe?token='.$subscription->unsubscribe_token),
            '{unsubscribe_url}' => url('/subscriptions/unsubscribe?token='.$subscription->unsubscribe_token),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    private function getPopularProjects(): string
    {
        $projects = Project::query()->where('is_featured', true)
            ->orderByDesc('stars')
            ->limit(5)
            ->get();

        if ($projects->isEmpty()) {
            return '<p>暂无推荐项目</p>';
        }

        $html = '<ul style="line-height: 2;">';
        foreach ($projects as $project) {
            $html .= '<li><strong>'.e($project->name).'</strong>';
            if ($project->url) {
                $html .= ' - <a href="'.e($project->url).'" style="color: #667eea;">查看项目</a>';
            }
            $html .= '</li>';
        }

        return $html.'</ul>';
    }

    private function getSideHustles(): string
    {
        $articles = Article::query()->where('is_published', true)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        if ($articles->isEmpty()) {
            return '<p>暂无副业灵感</p>';
        }

        $html = '<ul style="line-height: 2;">';
        foreach ($articles as $article) {
            $html .= '<li><strong>'.e($article->title).'</strong>';
            if ($article->slug) {
                $html .= ' - <a href="'.url('/articles/'.$article->slug).'" style="color: #667eea;">阅读更多</a>';
            }
            $html .= '</li>';
        }

        return $html.'</ul>';
    }

    private function getLearningResources(): string
    {
        return '<ul style="line-height: 2;">
            <li><a href="https://docs.openclaw.ai" style="color: #667eea;">OpenClaw 文档</a> - AI 助手框架</li>
            <li><a href="https://laravel.com" style="color: #667eea;">Laravel 文档</a> - PHP 框架</li>
            <li><a href="https://vuejs.org" style="color: #667eea;">Vue.js 文档</a> - 前端框架</li>
        </ul>';
    }
}
