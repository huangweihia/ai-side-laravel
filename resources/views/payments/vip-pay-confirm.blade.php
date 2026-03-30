@extends('layouts.app')

@section('title', '确认支付 - ' . $planLabel)

@section('content')
<div class="container" style="max-width: 560px; margin: 48px auto; padding: 0 20px;">
    <div class="card" style="padding: 36px;">
        <h1 style="font-size: 22px; font-weight: 800; margin: 0 0 8px; color: var(--white);">确认开通 {{ $planLabel }}</h1>
        <p style="color: var(--gray-light); margin: 0 0 24px;">应付金额：<strong style="color: #fbbf24; font-size: 28px;">¥{{ number_format($amountYuan, 2) }}</strong></p>

        @if(session('error'))
            <div style="margin-bottom: 20px; padding: 12px 16px; border-radius: 12px; background: rgba(239,68,68,0.15); color: #fca5a5;">{{ session('error') }}</div>
        @endif

        @if(! $wechatReady)
            <div style="padding: 16px; border-radius: 16px; background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.25); color: var(--gray-light); font-size: 14px; line-height: 1.7; text-align: center;">
                <div style="font-weight: 800; color: #fbbf24; font-size: 16px; margin-bottom: 8px;">支付暂未接通（测试收款）</div>
                <div style="margin-bottom: 14px;">
                    请使用下方收款码完成转账/付款。请在备注里填写你的订单计划：<strong>{{ $planLabel }}</strong>（也可以不填）。
                </div>

                <div style="display: grid; place-items: center; padding: 16px; border-radius: 16px; background: rgba(255,255,255,0.04); border: 1px dashed rgba(251,191,36,0.35);">
                    <img
                        src="{{ asset('images/vip-demo-qrcode.png') }}"
                        alt="VIP 收款码"
                        style="width: 240px; height: 240px; object-fit: contain; border-radius: 12px;"
                    />
                </div>

                <div style="margin-top: 16px; color: var(--gray-light); font-size: 12px; line-height: 1.6;">
                    说明：当前系统未接入微信支付回调，因此此页面仅用于展示收款码。后续接通支付后可恢复自动开通逻辑。
                </div>
            </div>
            <a href="{{ route('vip') }}" class="btn btn-primary" style="margin-top: 20px; display: inline-block;">返回 VIP 介绍</a>
        @else
            <form method="post" action="{{ route('payments.wechat.create') }}">
                @csrf
                <input type="hidden" name="plan" value="{{ $plan }}">
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; font-weight: 700;">
                    使用微信扫码支付
                </button>
            </form>
            <p style="margin-top: 20px; font-size: 13px; color: var(--gray-light); text-align: center;">
                <a href="{{ route('vip') }}" style="color: var(--primary-light);">返回</a>
            </p>
        @endif
    </div>
</div>
@endsection
