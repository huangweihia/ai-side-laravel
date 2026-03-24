@extends('layouts.app')

@section('title', '项目库 - AI 副业情报局')

@section('content')
<!-- Page Header -->
<section style="padding: 60px 0; background: var(--dark-light); border-bottom: 1px solid rgba(255,255,255,0.1);">
    <div class="container">
        <div style="text-align: center; max-width: 700px; margin: 0 auto;">
            <span class="section-label">项目库</span>
            <h1 class="section-title" style="margin-bottom: 16px;">AI 副业项目</h1>
            <p class="section-desc">
                精选 GitHub 热门 AI 项目，包含项目介绍、技术栈、变现方向，帮你找到下一个百万级副业机会
            </p>
        </div>
    </div>
</section>

<!-- Projects Grid -->
<section style="padding: 60px 0;">
    <div class="container">
        <!-- Filter Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <span class="card-tag" style="background: var(--primary); color: white;">全部</span>
                <span class="card-tag">LLM</span>
                <span class="card-tag">RAG</span>
                <span class="card-tag">Agent</span>
                <span class="card-tag">Image</span>
            </div>
            <div style="display: flex; gap: 12px; align-items: center;">
                <span style="color: var(--gray-light); font-size: 14px;">共 {{ $projects->total() }} 个项目</span>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="features-grid" style="grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 24px;">
            @forelse($projects as $project)
                <a href="{{ route('projects.show', $project->id) }}" class="card" style="display: block; text-decoration: none; height: 100%;">
                    <!-- Card Header with Gradient -->
                    <div style="height: 120px; background: linear-gradient(135deg, {{ $project->id % 2 == 0 ? '#6366f1 0%, #8b5cf6 100%' : '#ec4899 0%, #f59e0b 100%' }}); position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 20px; left: 20px; right: 20px; display: flex; justify-content: space-between; align-items: flex-start;">
                            <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 6px 12px; border-radius: 20px; font-size: 12px; color: white; font-weight: 600;">
                                {{ $project->status === 'in_progress' ? '🔥 进行中' : ($project->status === 'completed' ? '✅ 已完成' : '📋 规划中') }}
                            </span>
                            @if($project->revenue)
                                <span style="background: rgba(16,185,129,0.9); padding: 6px 12px; border-radius: 20px; font-size: 12px; color: white; font-weight: 600;">
                                    💰 {{ $project->revenue }}
                                </span>
                            @endif
                        </div>
                        <div style="position: absolute; bottom: 20px; left: 20px; right: 20px;">
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                @php
                                    $tags = ['LLM', 'RAG', 'Agent', 'Python', 'TypeScript'];
                                    $randomTags = array_rand($tags, 2);
                                @endphp
                                @foreach($randomTags as $tagIndex)
                                    <span style="background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 12px; font-size: 11px; color: white;">
                                        {{ $tags[$tagIndex] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body" style="padding: 24px;">
                        <h3 class="card-title" style="font-size: 18px; margin-bottom: 10px; color: white;">
                            {{ Str::limit($project->name, 50) }}
                        </h3>
                        <p class="card-desc" style="margin-bottom: 16px; height: 60px; overflow: hidden;">
                            {{ Str::limit($project->description ?: '暂无描述', 120) }}
                        </p>

                        <!-- Card Footer -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.1);">
                            <div style="display: flex; align-items: center; gap: 12px; font-size: 13px; color: var(--gray-light);">
                                <span style="display: flex; align-items: center; gap: 4px;">
                                    ⭐ {{ rand(1000, 100000) }}
                                </span>
                                <span style="display: flex; align-items: center; gap: 4px;">
                                    🍴 {{ rand(100, 10000) }}
                                </span>
                            </div>
                            <span style="color: var(--primary-light); font-size: 14px; font-weight: 600;">
                                查看详情 →
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="card" style="padding: 60px; text-align: center; grid-column: 1 / -1;">
                    <div style="font-size: 64px; margin-bottom: 20px;">📭</div>
                    <h3 style="font-size: 24px; color: white; margin-bottom: 12px;">暂无项目</h3>
                    <p style="color: var(--gray-light);">还没有收录项目，敬请期待</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 60px; gap: 8px;">
                {{ $projects->links('pagination::simple-bootstrap-4') }}
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section style="padding: 60px 0; background: var(--dark-light);">
    <div class="container">
        <div class="card" style="background: var(--gradient-primary); padding: 60px 40px; text-align: center; border: none; max-width: 800px; margin: 0 auto;">
            <h2 class="section-title" style="color: white; margin-bottom: 16px;">
                发现更多优质项目
            </h2>
            <p style="color: rgba(255,255,255,0.9); font-size: 16px; margin-bottom: 30px;">
                每天订阅我们的邮件，第一时间获取最新 AI 副业机会
            </p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-lg" style="background: white; color: var(--primary);">
                    免费订阅
                    <span>→</span>
                </a>
            @else
                <p style="color: rgba(255,255,255,0.9); font-size: 14px;">
                    ✅ 你已订阅，每天 10:00 准时收到邮件
                </p>
            @endguest
        </div>
    </div>
</section>
@endsection
