<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

        // 发送欢迎邮件
        $this->sendWelcomeEmail($user, $subscription);

        Auth::login($user);

        return redirect('/dashboard');
    }

    /**
     * 发送欢迎邮件
     */
    private function sendWelcomeEmail(User $user, EmailSubscription $subscription)
    {
        try {
            $content = view('emails.welcome', compact('user', 'subscription'))->render();
            
            Mail::raw($content, function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('🎉 欢迎加入 AI 副业情报局！');
            });
        } catch (\Exception $e) {
            // 欢迎邮件发送失败不影响注册流程
            \Log::warning('欢迎邮件发送失败：' . $e->getMessage());
        }
    }
}
