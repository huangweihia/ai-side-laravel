<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    public function sendFromTemplateByKey(string $templateKey, User $user, array $extraData = [], ?string $type = null): bool
    {
        $template = EmailTemplate::query()
            ->where('key', $templateKey)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            return false;
        }

        $data = array_merge([
            'name' => $user->name,
            'email' => $user->email,
            'date' => now()->format('Y-m-d H:i'),
            'dashboard_url' => url('/dashboard'),
            'vip_url' => route('vip'),
        ], $extraData);

        $subject = $this->replaceVariables($template->subject, $data);
        $content = $template->render($data);

        $emailLog = EmailLog::create([
            'recipient' => $user->email,
            'subject' => $subject,
            'content' => $content,
            'type' => $type ?? $templateKey,
            'template_id' => $template->id,
            'status' => 'pending',
        ]);

        try {
            // 使用 html() 方法发送 HTML 邮件
            Mail::html($content, function ($message) use ($user, $subject): void {
                $message->to($user->email)
                    ->subject($subject)
                    ->from(
                        config('mail.from.address', '2801359160@qq.com'),
                        config('mail.from.name', 'AI 副业情报局')
                    );
            });

            $emailLog->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return true;
        } catch (\Throwable $e) {
            $emailLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function replaceVariables(string $text, array $data): string
    {
        foreach ($data as $key => $value) {
            $text = str_replace('{{' . $key . '}}', (string) $value, $text);
        }

        return $text;
    }
}
