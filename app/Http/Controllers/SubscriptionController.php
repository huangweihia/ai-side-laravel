<?php

namespace App\Http\Controllers;

use App\Models\EmailSubscription;
use Illuminate\Http\Request;
use Filament\Notifications\Notification;

class SubscriptionController extends Controller
{
    /**
     * 显示退订页面
     */
    public function showUnsubscribe(string $token)
    {
        $subscription = EmailSubscription::getByToken($token);
        
        if (!$subscription) {
            abort(404, '订阅不存在');
        }
        
        return view('subscriptions.unsubscribe', compact('subscription'));
    }

    /**
     * 处理退订
     */
    public function unsubscribe(Request $request, string $token)
    {
        $subscription = EmailSubscription::getByToken($token);
        
        if (!$subscription) {
            abort(404, '订阅不存在');
        }
        
        $type = $request->input('type', 'all');
        
        match ($type) {
            'daily' => $subscription->update(['subscribed_to_daily' => false]),
            'weekly' => $subscription->update(['subscribed_to_weekly' => false]),
            'notifications' => $subscription->update(['subscribed_to_notifications' => false]),
            default => $subscription->unsubscribeAll(),
        };
        
        return view('subscriptions.unsubscribed', compact('subscription', 'type'));
    }

    /**
     * 重新订阅
     */
    public function resubscribe(string $token)
    {
        $subscription = EmailSubscription::getByToken($token);
        
        if (!$subscription) {
            abort(404, '订阅不存在');
        }
        
        $subscription->resubscribe();
        
        return view('subscriptions.resubscribed', compact('subscription'));
    }

    /**
     * 显示订阅偏好设置（需要登录）
     */
    public function preferences()
    {
        $user = auth()->user();
        $subscription = EmailSubscription::getByEmail($user->email);
        
        if (!$subscription) {
            $subscription = EmailSubscription::create([
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        }
        
        return view('subscriptions.preferences', compact('subscription'));
    }

    /**
     * 更新订阅偏好
     */
    public function updatePreferences(Request $request)
    {
        $user = auth()->user();
        $subscription = EmailSubscription::getByEmail($user->email);
        
        if (!$subscription) {
            $subscription = EmailSubscription::create([
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        }
        
        $request->validate([
            'subscribed_to_daily' => 'boolean',
            'subscribed_to_weekly' => 'boolean',
            'subscribed_to_notifications' => 'boolean',
        ]);
        
        $subscription->update([
            'subscribed_to_daily' => $request->boolean('subscribed_to_daily'),
            'subscribed_to_weekly' => $request->boolean('subscribed_to_weekly'),
            'subscribed_to_notifications' => $request->boolean('subscribed_to_notifications'),
            'unsubscribed_at' => null,
        ]);
        
        Notification::make()
            ->title('订阅偏好已更新')
            ->success()
            ->send();
        
        return redirect()->route('subscriptions.preferences');
    }
}
