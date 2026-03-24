<?php

namespace App\Filament\Pages;

use App\Models\EmailSetting;
use App\Models\EmailLog;
use App\Models\EmailSubscription;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class EmailManager extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static string $view = 'filament.pages.email-manager';
    protected static ?string $navigationLabel = '邮件管理';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = '系统设置';
    
    public ?string $recipient = '';
    public ?string $sendTime = '10:00';
    public array $recipients = [];
    public ?string $bulkEmails = '';
    public array $selectedForBulk = [];

    public function mount(): void
    {
        $this->recipients = EmailSetting::getRecipients();
        $this->sendTime = EmailSetting::getSendTime();
        $this->recipient = '';
    }

    public function addRecipient(): void
    {
        if (empty($this->recipient)) {
            Notification::make()
                ->title('请输入邮箱地址')
                ->warning()
                ->send();
            return;
        }

        if (!filter_var($this->recipient, FILTER_VALIDATE_EMAIL)) {
            Notification::make()
                ->title('邮箱格式不正确')
                ->danger()
                ->send();
            return;
        }

        if (in_array($this->recipient, $this->recipients)) {
            Notification::make()
                ->title('该邮箱已存在')
                ->warning()
                ->send();
            return;
        }

        // 添加到收件人列表
        $this->recipients[] = $this->recipient;
        EmailSetting::set('email_recipients', json_encode($this->recipients), '邮件接收人列表');
        
        // 同时创建订阅记录（默认全选）
        EmailSubscription::updateOrCreate(
            ['email' => $this->recipient],
            [
                'subscribed_to_daily' => true,
                'subscribed_to_weekly' => true,
                'subscribed_to_notifications' => true,
                'unsubscribed_at' => null,
            ]
        );
        
        $this->recipient = '';
        
        Notification::make()
            ->title('✅ 添加成功')
            ->body('已添加到收件人列表，默认订阅所有类型邮件')
            ->success()
            ->send();
    }

    public function removeRecipient(string $email): void
    {
        $this->recipients = array_values(array_filter($this->recipients, fn($e) => $e !== $email));
        EmailSetting::set('email_recipients', json_encode($this->recipients), '邮件接收人列表');
        
        // 同时删除订阅记录
        $subscription = EmailSubscription::where('email', $email)->first();
        if ($subscription) {
            $subscription->delete();
        }
        
        Notification::make()
            ->title('✅ 删除成功')
            ->success()
            ->send();
    }

    /**
     * 获取收件人的订阅状态
     */
    public function getSubscriptionStatus(string $email): array
    {
        $subscription = EmailSubscription::where('email', $email)->first();
        
        if (!$subscription) {
            return [
                'daily' => true,
                'weekly' => true,
                'notifications' => true,
                'exists' => false,
            ];
        }
        
        return [
            'daily' => $subscription->subscribed_to_daily && !$subscription->unsubscribed_at,
            'weekly' => $subscription->subscribed_to_weekly && !$subscription->unsubscribed_at,
            'notifications' => $subscription->subscribed_to_notifications && !$subscription->unsubscribed_at,
            'exists' => true,
        ];
    }

    /**
     * 发送测试邮件到单个邮箱
     */
    public function sendTestEmail(): void
    {
        // 如果没有指定收件人，发送给所有收件人
        $emails = !empty($this->recipient) 
            ? [$this->recipient] 
            : $this->recipients;
        
        if (empty($emails)) {
            Notification::make()
                ->title('⚠️ 请先添加收件人')
                ->body('在收件人列表中添加邮箱后再发送测试')
                ->warning()
                ->send();
            return;
        }
        
        $success = 0;
        $failed = 0;
        $errors = [];
        
        foreach ($emails as $email) {
            try {
                // 创建简单的测试邮件
                $subject = '🧪 邮件测试 - AI 副业情报局';
                $content = "这是一封测试邮件，用于验证邮件发送功能是否正常。\n\n" .
                           "发送时间：" . now()->format('Y-m-d H:i:s') . "\n" .
                           "接收邮箱：{$email}\n\n" .
                           "如果你收到这封邮件，说明邮件发送功能正常工作！\n\n" .
                           "AI 副业情报局";
                
                // 记录邮件日志
                $emailLog = EmailLog::create([
                    'recipient' => $email,
                    'subject' => $subject,
                    'content' => $content,
                    'type' => 'test',
                    'status' => 'pending',
                ]);
                
                // 发送邮件
                \Illuminate\Support\Facades\Mail::raw($content, function ($message) use ($email, $subject) {
                    $message->to($email)
                            ->subject($subject);
                });
                
                $emailLog->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                
                $success++;
            } catch (\Exception $e) {
                // 记录错误
                if (isset($emailLog)) {
                    $emailLog->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
                
                $failed++;
                $errors[] = "{$email}: " . $e->getMessage();
            }
        }
        
        // 显示结果
        if ($success > 0 && $failed === 0) {
            Notification::make()
                ->title('✅ 邮件已发送')
                ->body("成功发送 {$success} 封测试邮件")
                ->success()
                ->send();
        } elseif ($success > 0 && $failed > 0) {
            Notification::make()
                ->title('⚠️ 部分发送成功')
                ->body("成功：{$success} 封，失败：{$failed} 封\n" . implode("\n", $errors))
                ->warning()
                ->send();
        } else {
            Notification::make()
                ->title('❌ 发送失败')
                ->body("所有邮件发送失败\n" . implode("\n", $errors))
                ->danger()
                ->send();
        }
    }

    public function getRecentLogsProperty()
    {
        return EmailLog::latest()->limit(10)->get();
    }

    public function bulkImport(): void
    {
        if (empty(trim($this->bulkEmails ?? ''))) {
            Notification::make()
                ->title('请输入邮箱地址')
                ->body('每行一个邮箱地址')
                ->warning()
                ->send();
            return;
        }

        $lines = explode("\n", $this->bulkEmails);
        $added = 0;
        $skipped = 0;
        $invalid = 0;

        foreach ($lines as $line) {
            $email = trim($line);
            
            if (empty($email)) {
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $invalid++;
                continue;
            }

            if (in_array($email, $this->recipients)) {
                $skipped++;
                continue;
            }

            $this->recipients[] = $email;
            $added++;
        }

        EmailSetting::set('email_recipients', json_encode($this->recipients), '邮件接收人列表');
        $this->bulkEmails = '';

        $message = "成功添加 {$added} 个邮箱";
        if ($skipped > 0) $message .= "，跳过 {$skipped} 个重复";
        if ($invalid > 0) $message .= "，{$invalid} 个格式无效";

        Notification::make()
            ->title('批量导入完成')
            ->body($message)
            ->success()
            ->send();
    }

    public function exportRecipients(): void
    {
        $content = implode("\n", $this->recipients);
        
        response()
            ->streamDownload(function () use ($content) {
                echo $content;
            }, 'email-recipients-' . now()->format('Y-m-d') . '.txt', [
                'Content-Type' => 'text/plain',
            ]);
    }

    public function toggleBulkSelect(string $email): void
    {
        if (in_array($email, $this->selectedForBulk)) {
            $this->selectedForBulk = array_values(array_filter($this->selectedForBulk, fn($e) => $e !== $email));
        } else {
            $this->selectedForBulk[] = $email;
        }
    }

    public function bulkDelete(): void
    {
        if (empty($this->selectedForBulk)) {
            Notification::make()
                ->title('请先选择要删除的邮箱')
                ->warning()
                ->send();
            return;
        }

        $this->recipients = array_values(array_filter($this->recipients, fn($e) => !in_array($e, $this->selectedForBulk)));
        EmailSetting::set('email_recipients', json_encode($this->recipients), '邮件接收人列表');
        
        $count = count($this->selectedForBulk);
        $this->selectedForBulk = [];
        
        Notification::make()
            ->title("已删除 {$count} 个邮箱")
            ->success()
            ->send();
    }

    public function exportRecipientsAction()
    {
        $content = implode("\n", $this->recipients);
        
        return response()
            ->streamDownload(function () use ($content) {
                echo $content;
            }, 'email-recipients-' . now()->format('Y-m-d') . '.txt', [
                'Content-Type' => 'text/plain',
            ]);
    }
}
