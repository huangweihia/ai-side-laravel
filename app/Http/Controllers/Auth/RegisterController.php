<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailSubscription;
use App\Services\EmailNotificationService;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

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
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'email_code' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = strtolower(trim((string) $request->email));
        $cacheKey = $this->emailCodeCacheKey($email);
        $expectedCode = (string) Cache::get($cacheKey, '');
        if ($expectedCode === '' || $expectedCode !== (string) $request->email_code) {
            throw ValidationException::withMessages([
                'email_code' => '邮箱验证码错误或已过期，请重新获取。',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);
        Cache::forget($cacheKey);

        // 注册赠送 VIP（由后台开关控制）
        $autoVipEnabled = (bool) Setting::getValue('register_default_vip_enabled', false);
        if ($autoVipEnabled) {
            $vipDays = (int) Setting::getValue('register_default_vip_days', 7);
            $vipDays = max(1, $vipDays);

            $user->update([
                'role' => 'vip',
                'subscription_ends_at' => now()->addDays($vipDays),
            ]);
        }

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

    /**
     * 发送注册邮箱验证码
     */
    public function sendEmailCode(Request $request)
    {
        $payload = $request->all();
        validator($payload, [
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
        ])->validate();

        $email = strtolower(trim((string) ($payload['email'] ?? '')));
        $throttleKey = 'register_email_code_throttle:' . sha1($email);
        if (Cache::has($throttleKey)) {
            return new \Illuminate\Http\JsonResponse([
                'success' => false,
                'message' => '发送过于频繁，请 60 秒后再试。',
            ], 429);
        }

        $code = (string) random_int(100000, 999999);
        Cache::put($this->emailCodeCacheKey($email), $code, now()->addMinutes(10));
        Cache::put($throttleKey, 1, now()->addSeconds(60));

        Mail::raw("【AI 副业情报局】您的注册验证码是：{$code}，10 分钟内有效。若非本人操作请忽略。", function ($message) use ($email) {
            $message->to($email)->subject('注册验证码');
        });

        return new \Illuminate\Http\JsonResponse([
            'success' => true,
            'message' => '验证码已发送，请查收邮箱。',
        ]);
    }

    protected function emailCodeCacheKey(string $email): string
    {
        return 'register_email_code:' . sha1($email);
    }

}
