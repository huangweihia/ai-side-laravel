@extends('layouts.app')

@section('title', 'VIP 会员 - AI 副业情报局')

@section('content')
@if(session('success'))
    <div class="container" style="max-width: 1000px; margin: 20px auto 0; padding: 0 20px;">
        <div style="padding: 14px 18px; border-radius: 12px; background: rgba(16,185,129,0.15); color: #6ee7b7; font-weight: 600;">{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="container" style="max-width: 1000px; margin: 20px auto 0; padding: 0 20px;">
        <div style="padding: 14px 18px; border-radius: 12px; background: rgba(239,68,68,0.15); color: #fca5a5;">{{ session('error') }}</div>
    </div>
@endif
<!-- Page Header -->
<section style="padding: 80px 0; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); text-align: center;">
    <div class="container">
        <h1 style="font-size: 48px; font-weight: 800; color: white; margin-bottom: 16px;">⭐ VIP 会员计划</h1>
        <p style="font-size: 20px; color: rgba(255,255,255,0.9); max-width: 600px; margin: 0 auto;">
            解锁全部内容，获取专属资源和一对一指导
        </p>
    </div>
</section>

<!-- Pricing Cards -->
<section style="padding: 80px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; max-width: 1100px; margin: 0 auto;">
            
            <!-- 月度会员 -->
            <div class="card" style="padding: 40px; text-align: center; border: 2px solid rgba(255,255,255,0.1);">
                <div style="font-size: 24px; margin-bottom: 10px; color: var(--gray-light);">月度会员</div>
                <div style="font-size: 56px; font-weight: 800; color: var(--primary); margin-bottom: 10px;">
                    ¥{{ number_format((float) config('wechat_pay.plans.monthly.amount_yuan', 9.9), 1) }}<span style="font-size: 16px; color: var(--gray-light); font-weight: 400;">/月</span>
                </div>
                <div style="margin-top: -2px; margin-bottom: 18px; color: rgba(148,163,184,0.95); font-size: 14px;">
                    打折前
                    <span style="text-decoration: line-through; color: rgba(148,163,184,0.95); font-weight: 600;">
                        ¥{{ number_format((float) config('wechat_pay.plans.monthly.original_amount_yuan', 29), 0) }}
                    </span>
                </div>
                <ul style="list-style: none; padding: 0; margin: 30px 0; text-align: left;">
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">✅ 解锁全部项目库</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">✅ 解锁全部文章</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">✅ 每日资讯日报</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">❌ 专属资源下载</li>
                    <li style="padding: 12px 0;">❌ 一对一指导</li>
                </ul>
                @auth
                    <a href="{{ route('vip.pay', 'monthly') }}" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; display: inline-block; text-decoration: none; box-sizing: border-box;">
                        查看收款码开通
                    </a>
                @else
                    <a href="{{ route('login', ['redirect' => route('vip.pay', 'monthly')]) }}" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; display: inline-block; text-decoration: none; box-sizing: border-box;">
                        登录后开通
                    </a>
                @endauth
            </div>

            <!-- 年度会员 -->
            <div class="card" style="position: relative; padding: 40px; text-align: center; background: linear-gradient(135deg, rgba(251,191,36,0.1) 0%, rgba(245,158,11,0.1) 100%); border: 2px solid #fbbf24;">
                <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: #fbbf24; color: white; padding: 4px 20px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                    🔥 最受欢迎
                </div>
                <div style="font-size: 24px; margin-bottom: 10px; color: var(--gray-light);">年度会员</div>
                <div style="font-size: 56px; font-weight: 800; color: var(--primary); margin-bottom: 10px;">
                    ¥{{ number_format((float) config('wechat_pay.plans.yearly.amount_yuan', 88), 0) }}<span style="font-size: 16px; color: var(--gray-light); font-weight: 400;">/年</span>
                </div>
                @php
                    $m = (float) config('wechat_pay.plans.monthly.amount_yuan', 9.9);
                    $y = (float) config('wechat_pay.plans.yearly.amount_yuan', 88);
                    $save = max(0, $m * 12 - $y);
                @endphp
                <div style="margin-top: -2px; margin-bottom: 18px; color: rgba(148,163,184,0.95); font-size: 14px;">
                    打折前
                    <span style="text-decoration: line-through; color: rgba(148,163,184,0.95); font-weight: 600;">
                        ¥{{ number_format((float) config('wechat_pay.plans.yearly.original_amount_yuan', 288), 0) }}
                    </span>
                </div>
                @if($save > 0)
                <div style="color: #10b981; font-size: 14px; margin-bottom: 20px;">相较连续包月约省 ¥{{ number_format($save, 0) }}</div>
                @endif
                <ul style="list-style: none; padding: 0; margin: 30px 0; text-align: left;">
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">✅ 解锁全部项目库</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">✅ 解锁全部文章</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">✅ 每日资讯日报</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">✅ 专属资源下载</li>
                    <li style="padding: 12px 0;">❌ 一对一指导</li>
                </ul>
                @auth
                    <a href="{{ route('vip.pay', 'yearly') }}" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); display: inline-block; text-decoration: none; box-sizing: border-box;">
                        查看收款码开通
                    </a>
                @else
                    <a href="{{ route('login', ['redirect' => route('vip.pay', 'yearly')]) }}" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); display: inline-block; text-decoration: none; box-sizing: border-box;">
                        登录后开通
                    </a>
                @endauth
            </div>

            <!-- 终身会员 -->
            <div class="card" style="padding: 40px; text-align: center; border: 2px solid rgba(139, 92, 246, 0.45);">
                <div style="font-size: 24px; margin-bottom: 10px; color: var(--gray-light);">终身会员</div>
                <div style="font-size: 56px; font-weight: 800; color: #a78bfa; margin-bottom: 10px;">
                    ¥{{ number_format((float) config('wechat_pay.plans.lifetime.amount_yuan', 388), 0) }}<span style="font-size: 16px; color: var(--gray-light); font-weight: 400;"> 一次买断</span>
                </div>
                <div style="margin-top: -2px; margin-bottom: 22px; color: rgba(148,163,184,0.95); font-size: 14px;">
                    打折前
                    <span style="text-decoration: line-through; color: rgba(148,163,184,0.95); font-weight: 600;">
                        ¥{{ number_format((float) config('wechat_pay.plans.lifetime.original_amount_yuan', 888), 0) }}
                    </span>
                </div>
                <ul style="list-style: none; padding: 0; margin: 30px 0; text-align: left;">
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">✅ 含月度/年度全部权益</li>
                    <li style="padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">✅ 长期内容更新</li>
                    <li style="padding: 12px 0;">✅ 无需续费烦恼</li>
                </ul>
                @auth
                    <a href="{{ route('vip.pay', 'lifetime') }}" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); display: inline-block; text-decoration: none; box-sizing: border-box;">
                        查看收款码开通
                    </a>
                @else
                    <a href="{{ route('login', ['redirect' => route('vip.pay', 'lifetime')]) }}" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); display: inline-block; text-decoration: none; box-sizing: border-box;">
                        登录后开通
                    </a>
                @endauth
            </div>

        </div>
    </div>
</section>

<!-- VIP Benefits -->
<section style="padding: 80px 0; background: var(--dark-light);">
    <div class="container">
        <h2 style="text-align: center; font-size: 36px; margin-bottom: 60px;">VIP 会员专属权益</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">
            <div style="text-align: center;">
                <div style="font-size: 48px; margin-bottom: 16px;">📚</div>
                <h3 style="font-size: 20px; margin-bottom: 12px;">全部项目库</h3>
                <p style="color: var(--gray-light); line-height: 1.8;">解锁 100+ 个 AI 副业项目，包含详细教程和变现路径</p>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 48px; margin-bottom: 16px;">📰</div>
                <h3 style="font-size: 20px; margin-bottom: 12px;">深度文章</h3>
                <p style="color: var(--gray-light); line-height: 1.8;">每周更新 3-5 篇深度教程和变现案例分享</p>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 48px; margin-bottom: 16px;">💎</div>
                <h3 style="font-size: 20px; margin-bottom: 12px;">专属资源</h3>
                <p style="color: var(--gray-light); line-height: 1.8;">VIP 专属的 Prompt 库、工具清单、模板下载</p>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 48px; margin-bottom: 16px;">🎯</div>
                <h3 style="font-size: 20px; margin-bottom: 12px;">一对一指导</h3>
                <p style="color: var(--gray-light); line-height: 1.8;">年度会员专享，30 分钟一对一项目指导</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section style="padding: 80px 0;">
    <div class="container" style="max-width: 800px;">
        <h2 style="text-align: center; font-size: 36px; margin-bottom: 60px;">常见问题</h2>
        
        <div style="display: grid; gap: 20px;">
            <div class="card" style="padding: 24px;">
                <h3 style="font-size: 18px; margin-bottom: 12px;">Q: 支持退款吗？</h3>
                <p style="color: var(--gray-light); line-height: 1.8;">A: 支持 7 天无理由退款。如果您在购买 7 天内不满意，可以联系客服申请全额退款。</p>
            </div>
            <div class="card" style="padding: 24px;">
                <h3 style="font-size: 18px; margin-bottom: 12px;">Q: 会员到期后怎么办？</h3>
                <p style="color: var(--gray-light); line-height: 1.8;">A: 会员到期后，您可以选择续费。续费后所有权益立即恢复，之前下载的资源仍然可以访问。</p>
            </div>
            <div class="card" style="padding: 24px;">
                <h3 style="font-size: 18px; margin-bottom: 12px;">Q: 支持哪些支付方式？</h3>
                <p style="color: var(--gray-light); line-height: 1.8;">A: 已接入<strong>微信 Native 扫码支付</strong>（需在服务器配置商户参数）。其他渠道后续陆续开放。</p>
            </div>
        </div>
    </div>
</section>
@endsection
