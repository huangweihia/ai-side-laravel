@extends('layouts.app')

@section('title', '个人中心 - AI 副业情报局')

@section('content')
<div class="container" style="max-width: 900px; margin: 60px auto;">
    <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 30px; text-align: center;">
        👤 个人中心
    </h1>
    
    <div style="display: grid; gap: 24px;">
        <!-- 账户信息 -->
        <div class="card" style="padding: 30px;">
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 24px;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; font-weight: 700;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 4px;">{{ Auth::user()->name }}</h2>
                    <p style="color: var(--gray-light);">{{ Auth::user()->email }}</p>
                    @if(Auth::user()->isVip())
                        <span style="display: inline-block; margin-top: 8px; padding: 4px 12px; background: linear-gradient(135deg, #fbbf24, #f59e0b); color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            ⭐ VIP 会员
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- 快捷操作 -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
            <a href="{{ route('projects.index') }}" class="card" style="text-decoration: none; color: inherit; padding: 24px; transition: transform 0.2s;">
                <div style="font-size: 32px; margin-bottom: 12px;">📁</div>
                <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">项目库</h3>
                <p style="color: var(--gray-light); font-size: 14px;">浏览 AI 副业项目</p>
            </a>

            <a href="{{ route('articles.index') }}" class="card" style="text-decoration: none; color: inherit; padding: 24px; transition: transform 0.2s;">
                <div style="font-size: 32px; margin-bottom: 12px;">📰</div>
                <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">文章</h3>
                <p style="color: var(--gray-light); font-size: 14px;">阅读最新文章</p>
            </a>

            <a href="{{ route('subscriptions.preferences') }}" class="card" style="text-decoration: none; color: inherit; padding: 24px; transition: transform 0.2s;">
                <div style="font-size: 32px; margin-bottom: 12px;">📧</div>
                <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">订阅偏好</h3>
                <p style="color: var(--gray-light); font-size: 14px;">管理邮件订阅</p>
            </a>
        </div>

        <!-- 订阅状态 -->
        @php
            $emailSubscription = \App\Models\EmailSubscription::getByEmail(Auth::user()->email);
        @endphp
        @if($emailSubscription)
            <div class="card" style="padding: 30px;">
                <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                    📧 订阅状态
                </h3>
                <div style="display: grid; gap: 12px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: rgba(255,255,255,0.05); border-radius: 8px;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            {{ $emailSubscription->subscribed_to_daily ? '✅' : '❌' }}
                            每日资讯日报
                        </span>
                        <span style="color: var(--gray-light); font-size: 13px;">每天 10:00</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: rgba(255,255,255,0.05); border-radius: 8px;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            {{ $emailSubscription->subscribed_to_weekly ? '✅' : '❌' }}
                            每周精选汇总
                        </span>
                        <span style="color: var(--gray-light); font-size: 13px;">每周一</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: rgba(255,255,255,0.05); border-radius: 8px;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            {{ $emailSubscription->subscribed_to_notifications ? '✅' : '❌' }}
                            系统通知
                        </span>
                        <span style="color: var(--gray-light); font-size: 13px;">重要通知</span>
                    </div>
                </div>
                <a href="{{ route('subscriptions.preferences') }}" style="display: block; text-align: center; margin-top: 20px; padding: 12px; background: rgba(99,102,241,0.1); color: var(--primary-light); border-radius: 8px; text-decoration: none; font-weight: 600;">
                    管理订阅偏好 →
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
