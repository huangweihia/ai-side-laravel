<?php

namespace App\Filament\Pages;

use App\Models\EmailSetting;
use App\Models\EmailLog;
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

        $this->recipients[] = $this->recipient;
        EmailSetting::set('email_recipients', json_encode($this->recipients), '邮件接收人列表');
        
        $this->recipient = '';
        
        Notification::make()
            ->title('添加成功')
            ->success()
            ->send();
    }

    public function removeRecipient(string $email): void
    {
        $this->recipients = array_values(array_filter($this->recipients, fn($e) => $e !== $email));
        EmailSetting::set('email_recipients', json_encode($this->recipients), '邮件接收人列表');
        
        Notification::make()
            ->title('删除成功')
            ->success()
            ->send();
    }

    public function sendTestEmail(): void
    {
        try {
            \Artisan::call('ai-projects:send-daily', [
                '--email' => $this->recipient ?: ($this->recipients[0] ?? '2801359160@qq.com'),
            ]);
            
            Notification::make()
                ->title('邮件已发送')
                ->body('请检查收件箱')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('发送失败')
                ->body($e->getMessage())
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
