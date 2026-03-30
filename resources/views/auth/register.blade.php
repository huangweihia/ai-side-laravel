@extends('layouts.app')

@section('title', '注册 - AI 副业情报局')

@section('content')
<div class="container" style="max-width: 480px; margin: 60px auto;">
    <div class="card" style="padding: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 48px; margin-bottom: 16px;">🚀</div>
            <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">创建账号</h1>
            <p style="color: var(--gray-light); font-size: 15px;">加入 AI 副业情报局，开启副业之旅</p>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="name">姓名</label>
                <input class="form-input" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="你的名字">
            </div>

            <div class="form-group">
                <label class="form-label" for="email">邮箱地址</label>
                <input class="form-input" id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="your@email.com">
            </div>
            <div class="form-group">
                <label class="form-label" for="email_code">邮箱验证码</label>
                <div style="display:flex; gap:10px;">
                    <input class="form-input" id="email_code" type="text" name="email_code" value="{{ old('email_code') }}" required placeholder="6 位验证码" maxlength="6" style="flex:1;">
                    <button class="btn btn-secondary" type="button" id="sendEmailCodeBtn" onclick="sendEmailCode()" style="white-space:nowrap;">发送验证码</button>
                </div>
                <p style="margin-top: 8px; font-size: 12px; color: var(--gray-light);">需使用真实邮箱接收验证码，10 分钟内有效。</p>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">密码</label>
                <input class="form-input" id="password" type="password" name="password" required placeholder="至少 8 位">
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">确认密码</label>
                <input class="form-input" id="password_confirmation" type="password" name="password_confirmation" required placeholder="再次输入密码">
            </div>

            <button class="btn btn-primary" type="button" onclick="confirmRegister()" style="width: 100%; padding: 14px; font-size: 15px;">
                免费注册
            </button>
        </form>

        <script>
        function confirmRegister() {
            const form = document.getElementById('registerForm');
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const emailCode = document.getElementById('email_code').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;

            if (!name || !email || !emailCode || !password || !passwordConfirm) {
                showToast('请填写所有必填字段', 'error');
                return;
            }
            if (!/^\d{6}$/.test(emailCode)) {
                showToast('请输入 6 位邮箱验证码', 'error');
                return;
            }

            if (password !== passwordConfirm) {
                showToast('两次输入的密码不一致', 'error');
                return;
            }

            if (password.length < 8) {
                showToast('密码长度至少 8 位', 'error');
                return;
            }

            showConfirm({
                icon: '🎉',
                title: '确认注册',
                content: '注册后即表示你同意我们的服务条款和隐私政策。<br>注册成功后将自动订阅每日资讯日报。',
                confirmText: '确认注册',
                confirmColor: '#6366f1',
                onConfirm: async () => {
                    showLoading('正在创建账号...');
                    form.submit();
                }
            });
        }

        async function sendEmailCode() {
            const email = document.getElementById('email').value.trim();
            const btn = document.getElementById('sendEmailCodeBtn');
            if (!email) {
                showToast('请先输入邮箱地址', 'error');
                return;
            }

            btn.disabled = true;
            btn.textContent = '发送中...';

            try {
                const res = await fetch("{{ route('register.send-code') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email }),
                });

                let data = null;
                try { data = await res.json(); } catch (e) {}

                if (!res.ok || !data || !data.success) {
                    const emailTaken = data?.errors?.email?.[0] || data?.message || '';
                    const shown = emailTaken || '发送失败，请稍后重试';

                    // 针对“邮箱已被占用/已注册”的更友好提示
                    if (shown.includes('already been taken') || shown.includes('已被注册') || shown.includes('已占用')) {
                        showToast('该邮箱已注册，请直接登录或更换邮箱', 'error');
                    } else {
                        showToast(shown, 'error');
                    }

                    btn.disabled = false;
                    btn.textContent = '发送验证码';
                    return;
                }
                showToast('验证码已发送，请查收邮箱', 'success');
                let left = 60;
                btn.textContent = `${left}s 后重发`;
                const timer = setInterval(() => {
                    left -= 1;
                    if (left <= 0) {
                        clearInterval(timer);
                        btn.disabled = false;
                        btn.textContent = '发送验证码';
                        return;
                    }
                    btn.textContent = `${left}s 后重发`;
                }, 1000);
            } catch (e) {
                showToast('发送失败，请检查网络后重试', 'error');
                btn.disabled = false;
                btn.textContent = '发送验证码';
            }
        }
        </script>

        <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1);">
            <p style="color: var(--gray-light); font-size: 14px;">
                已有账号？
                <a href="{{ route('login') }}" style="color: var(--primary-light); text-decoration: none; font-weight: 600;">立即登录</a>
            </p>
        </div>
        
        <p style="text-align: center; color: var(--gray); font-size: 13px; margin-top: 20px;">
            注册即表示你同意我们的
            <a href="#" style="color: var(--gray); text-decoration: underline;">服务条款</a>
            和
            <a href="#" style="color: var(--gray); text-decoration: underline;">隐私政策</a>
        </p>
    </div>
    
    <p style="text-align: center; color: var(--gray); font-size: 14px; margin-top: 24px;">
        <a href="{{ route('home') }}" style="color: var(--gray); text-decoration: none;">
            <span>←</span> 返回首页
        </a>
    </p>
</div>
@endsection
