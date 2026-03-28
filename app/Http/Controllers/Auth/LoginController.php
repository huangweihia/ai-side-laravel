<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * 显示登录表单
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 处理登录
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $redirectTo = $request->input('redirect');
            if (is_string($redirectTo) && $redirectTo !== '') {
                $target = parse_url($redirectTo);
                $base = parse_url((string) config('app.url'));
                if (($target['host'] ?? null) === ($base['host'] ?? null)
                    && (($target['scheme'] ?? 'https') === ($base['scheme'] ?? 'https') || in_array($target['scheme'] ?? '', ['http', 'https'], true))) {
                    return redirect()->to($redirectTo);
                }
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => '邮箱或密码错误',
        ])->onlyInput('email');
    }

    /**
     * 登出
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
