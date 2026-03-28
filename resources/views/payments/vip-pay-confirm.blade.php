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
            <div style="padding: 16px; border-radius: 12px; background: rgba(251,191,36,0.12); border: 1px solid rgba(251,191,36,0.35); color: #fcd34d; font-size: 14px; line-height: 1.7;">
                微信支付尚未配置。请在服务器配置 <code style="color:#fef3c7;">.env</code> 中的 <code style="color:#fef3c7;">WECHAT_PAY_*</code> 参数，并将商户私钥放到
                <code style="color:#fef3c7;">storage/certs/wechat/apiclient_key.pem</code>，详见 <code style="color:#fef3c7;">docs/05-开发文档/微信支付Native接入.md</code>。
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
