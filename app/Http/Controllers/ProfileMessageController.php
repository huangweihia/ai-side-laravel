<?php

namespace App\Http\Controllers;

use App\Models\EmailSubscription;
use App\Models\ProfileMessage;
use App\Models\User;
use App\Models\VipUrgentNotificationLog;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfileMessageController extends Controller
{
    public function store(Request $request, User $user)
    {
        $visitor = auth()->user();
        if ((int) $visitor->id === (int) $user->id) {
            return back()->with('error', '不能给自己留言');
        }

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        ProfileMessage::create([
            'recipient_id' => $user->id,
            'sender_id' => $visitor->id,
            'body' => $request->input('body'),
        ]);

        return back()->with('success', '留言已发送');
    }

    public function sendUrgent(Request $request, User $user, ProfileMessage $message, EmailNotificationService $emailNotificationService)
    {
        $owner = auth()->user();
        abort_unless((int) $owner->id === (int) $user->id, 403);
        abort_unless((int) $message->recipient_id === (int) $user->id, 404);
        abort_unless($owner->isVip(), 403);

        $request->validate([
            'urgent_note' => 'nullable|string|max:500',
        ]);

        if (VipUrgentNotificationLog::query()
            ->where('sender_user_id', $owner->id)
            ->whereDate('sent_at', now()->toDateString())
            ->exists()
        ) {
            return back()->with('error', '今日紧急通知次数已用完（每位 VIP 每天仅可发送 1 次）');
        }

        $recipientUser = $message->sender;
        if (!$recipientUser) {
            return back()->with('error', '收件用户不存在');
        }

        if (! EmailSubscription::wantsSystemNotifications($recipientUser)) {
            return back()->with('error', '对方未开启「系统通知」邮件偏好，无法发送紧急通知邮件');
        }

        $defaultNote = '请登录网站查看对方主页或站内互动。本通知为对方通过 VIP 紧急通道发送。';
        $note = trim((string) $request->input('urgent_note', ''));
        $urgentNoteText = $note !== '' ? $note : $defaultNote;

        $ok = $emailNotificationService->sendFromTemplateByKey(
            'profile_message_urgent',
            $recipientUser,
            [
                'recipient_name' => $recipientUser->name,
                'profile_owner_name' => $user->name,
                'message_excerpt' => Str::limit(strip_tags($message->body), 200),
                'urgent_note' => $urgentNoteText,
                'profile_url' => route('users.show', $user->id),
            ],
            'profile_message_urgent'
        );

        if ($ok) {
            VipUrgentNotificationLog::create([
                'sender_user_id' => $owner->id,
                'recipient_user_id' => $recipientUser->id,
                'profile_message_id' => $message->id,
                'sent_at' => now(),
            ]);

            return back()->with('success', '紧急通知邮件已发送至对方邮箱');
        }

        return back()->with('error', '邮件发送失败，请稍后重试或检查邮件配置');
    }
}
