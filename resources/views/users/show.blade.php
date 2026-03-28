@extends('layouts.app')

@section('title', ($user->name ?? '用户') . ' - 个人主页')

@section('content')
<div class="container" style="max-width: 980px; margin: 0 auto; padding: 32px 20px;">
    <div style="background:#fff; border-radius:16px; padding:24px; box-shadow:0 6px 24px rgba(0,0,0,.06); margin-bottom:20px;">
        <div style="display:flex; align-items:center; gap:16px;">
            <img src="{{ $user->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'U') }}"
                 alt="{{ $user->name }}"
                 style="width:72px; height:72px; border-radius:50%; object-fit:cover;">
            <div>
                <h1 style="margin:0; color:#1e293b;">{{ $user->name ?? '匿名用户' }}</h1>
                <p style="margin:6px 0 0; color:#64748b;">加入于 {{ optional($user->created_at)->format('Y-m-d') }}</p>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; margin-top:18px;">
            <div style="background:#f8fafc; border-radius:12px; padding:14px; text-align:center;">
                <div style="color:#0f172a; font-size:20px; font-weight:800;">{{ $stats['comments'] ?? 0 }}</div>
                <div style="color:#64748b; font-size:13px;">评论数</div>
            </div>
            <div style="background:#f8fafc; border-radius:12px; padding:14px; text-align:center;">
                <div style="color:#0f172a; font-size:20px; font-weight:800;">{{ $stats['favorites'] ?? 0 }}</div>
                <div style="color:#64748b; font-size:13px;">收藏数</div>
            </div>
            <div style="background:#f8fafc; border-radius:12px; padding:14px; text-align:center;">
                <div style="color:#0f172a; font-size:20px; font-weight:800;">{{ $stats['histories'] ?? 0 }}</div>
                <div style="color:#64748b; font-size:13px;">浏览数</div>
            </div>
        </div>
    </div>

    <div style="background:#fff; border-radius:16px; padding:24px; box-shadow:0 6px 24px rgba(0,0,0,.06);">
        <h2 style="margin-top:0; color:#1e293b;">最近评论</h2>

        @forelse($comments as $comment)
            <div style="padding:14px 0; border-bottom:1px solid #e2e8f0;">
                <div style="color:#334155; font-size:14px;">{{ $comment->content }}</div>
                <div style="margin-top:6px; color:#94a3b8; font-size:12px;">
                    {{ $comment->created_at->diffForHumans() }}
                </div>
            </div>
        @empty
            <p style="color:#64748b;">暂无评论</p>
        @endforelse
    </div>
</div>
@endsection
