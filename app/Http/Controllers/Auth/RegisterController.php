<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailSubscription;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * 显示注册表单
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * 处理注册
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 创建邮件订阅记录
        $subscription = EmailSubscription::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'subscribed_to_daily' => true,
            'subscribed_to_weekly' => true,
            'subscribed_to_notifications' => true,
        ]);

        // 发送欢迎邮件（使用后台模板 key=welcome）
        app(EmailNotificationService::class)->sendFromTemplateByKey(
            'welcome',
            $user,
            [
                'trial_days' => 3,
                'trial_end_date' => now()->addDays(3)->format('Y-m-d'),
                'unsubscribe_url' => $subscription->getUnsubscribeUrl(),
                'preferences_url' => route('subscriptions.preferences'),
            ],
            'welcome'
        );

        Auth::login($user);

        return redirect('/dashboard');
    }

}
