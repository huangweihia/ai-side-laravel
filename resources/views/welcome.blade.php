@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-badge">
            <span>✨</span>
            <span>每天 10:00 自动推送最新 AI 副业机会</span>
        </div>
        
        <h1 class="hero-title">
            用 AI 赋能副业<br>让赚钱变得更简单
        </h1>
        
        <p class="hero-subtitle">
            每天精选 GitHub 热门 AI 项目 + 变现灵感 + 学习资源，<br>
            帮你发现下一个百万级副业机会
        </p>
        
        <div class="hero-cta">
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    免费注册
                    <span>→</span>
                </a>
                <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">
                    已有账号？登录
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                    进入个人中心
                    <span>→</span>
                </a>
                <a href="#features" class="btn btn-secondary btn-lg">
                    了解更多
                </a>
            @endguest
        </div>
        
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-value">10+</div>
                <div class="hero-stat-label">每日精选项目</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">5+</div>
                <div class="hero-stat-label">变现灵感方向</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">4+</div>
                <div class="hero-stat-label">学习资源推荐</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">100%</div>
                <div class="hero-stat-label">免费推送</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features" id="features">
    <div class="container">
        <div class="section-header">
            <span class="section-label">核心功能</span>
            <h2 class="section-title">为什么选择我们？</h2>
            <p class="section-desc">
                不只是资讯聚合，更是你的 AI 副业成长伙伴
            </p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🔥</div>
                <h3 class="feature-title">热门 AI 项目 Top 10</h3>
                <p class="feature-desc">
                    每日抓取 GitHub Trending，精选 AI/LLM/RAG 相关项目，带 star 数、技术栈、项目标签，帮你发现下一个爆款
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3 class="feature-title">副业/创收灵感</h3>
                <p class="feature-desc">
                    5+ 个经过验证的 AI 变现方向，包含难度评估、收入区间、技能要求、获客平台，直接复制就能开干
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📚</div>
                <h3 class="feature-title">学习资源推荐</h3>
                <p class="feature-desc">
                    精选教程、文档、开源项目，从入门到进阶，帮你快速掌握 AI 开发技能，少走 3 年弯路
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📧</div>
                <h3 class="feature-title">每日邮件推送</h3>
                <p class="feature-desc">
                    每天早上 10 点准时发送，精美的 HTML 邮件，手机电脑都能看，不错过任何机会
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3 class="feature-title">项目管理后台</h3>
                <p class="feature-desc">
                    完整的 Filament 后台，管理项目、文章、分类、邮件日志，数据一目了然
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🤖</div>
                <h3 class="feature-title">全自动化</h3>
                <p class="feature-desc">
                    定时任务自动执行，无需手动操作，躺着就能收到最新 AI 副业资讯
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Sample Email Section -->
<section style="padding: 100px 0; background: var(--dark);">
    <div class="container">
        <div class="section-header">
            <span class="section-label">邮件预览</span>
            <h2 class="section-title">每天早上的精神食粮</h2>
            <p class="section-desc">
                精美的 HTML 邮件，包含 3 大板块，5 分钟掌握最新 AI 副业机会
            </p>
        </div>
        
        <div class="card" style="max-width: 650px; margin: 0 auto; overflow: hidden;">
            <div style="background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%); padding: 35px 30px; text-align: center;">
                <h3 style="font-size: 24px; margin-bottom: 8px; color: white;">🤖 AI & 副业资讯日报</h3>
                <p style="opacity: 0.9; font-size: 14px;">2026-03-23 | 星期一 · 精选 GitHub 热门项目 + 变现灵感</p>
            </div>
            <div style="padding: 25px 30px; background: #16213e;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                    <span style="font-size: 20px;">🔥</span>
                    <span style="font-size: 16px; font-weight: 600;">热门 AI 项目 Top 10</span>
                </div>
                <div style="background: #1a1a2e; border-left: 3px solid #6366f1; padding: 18px; border-radius: 0 8px 8px 0; margin-bottom: 15px;">
                    <div style="font-size: 14px; color: #818cf8; font-weight: bold; margin-bottom: 8px;">#1</div>
                    <div style="font-size: 16px; font-weight: 600; color: white; margin-bottom: 6px;">
                        <a href="#" style="color: #6366f1;">langflow-ai/langflow</a>
                    </div>
                    <div style="font-size: 13px; color: #94a3b8; margin-bottom: 12px;">Build and deploy AI-powered agents and workflows visually</div>
                    <div style="display: flex; gap: 10px; font-size: 12px; flex-wrap: wrap;">
                        <span style="color: #fbbf24;">🐍 Python</span>
                        <span style="color: #fbbf24;">⭐ 145,957</span>
                        <span style="background: #2a2a4a; color: #94a3b8; padding: 3px 10px; border-radius: 12px;">Agent</span>
                        <span style="background: #2a2a4a; color: #94a3b8; padding: 3px 10px; border-radius: 12px;">Low-Code</span>
                    </div>
                </div>
                <div style="background: #1a1a2e; border-left: 3px solid #6366f1; padding: 18px; border-radius: 0 8px 8px 0; margin-bottom: 15px;">
                    <div style="font-size: 14px; color: #818cf8; font-weight: bold; margin-bottom: 8px;">#2</div>
                    <div style="font-size: 16px; font-weight: 600; color: white; margin-bottom: 6px;">
                        <a href="#" style="color: #6366f1;">langgenius/dify</a>
                    </div>
                    <div style="font-size: 13px; color: #94a3b8; margin-bottom: 12px;">Production-ready platform for agentic workflow development</div>
                    <div style="display: flex; gap: 10px; font-size: 12px; flex-wrap: wrap;">
                        <span style="color: #fbbf24;">🐍 TypeScript</span>
                        <span style="color: #fbbf24;">⭐ 133,752</span>
                        <span style="background: #2a2a4a; color: #94a3b8; padding: 3px 10px; border-radius: 12px;">Agent</span>
                        <span style="background: #2a2a4a; color: #94a3b8; padding: 3px 10px; border-radius: 12px;">RAG</span>
                    </div>
                </div>
                <div style="text-align: center; padding: 20px; color: #64748b; font-size: 14px;">
                    ··· 还有 8 个热门项目，查看完整邮件
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    立即注册，免费订阅
                    <span>→</span>
                </a>
            @else
                <p style="color: var(--success); font-size: 16px; font-weight: 600;">
                    ✅ 你已订阅，每天 10:00 准时收到邮件
                </p>
            @endguest
        </div>
    </div>
</section>

<!-- CTA Section -->
<section style="padding: 100px 0;">
    <div class="container">
        <div class="card" style="background: var(--gradient-primary); padding: 60px 40px; text-align: center; border: none;">
            <h2 class="section-title" style="color: white; margin-bottom: 16px;">
                准备好开始你的 AI 副业之旅了吗？
            </h2>
            <p style="color: rgba(255,255,255,0.9); font-size: 16px; max-width: 500px; margin: 0 auto 30px;">
                立即注册，每天 5 分钟，掌握最新 AI 副业机会，让赚钱变得更简单
            </p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-lg" style="background: white; color: var(--primary);">
                    免费注册
                    <span>→</span>
                </a>
                <p style="color: rgba(255,255,255,0.7); font-size: 13px; margin-top: 16px;">
                    无需信用卡 · 30 秒完成注册 · 随时取消
                </p>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-lg" style="background: white; color: var(--primary);">
                    进入个人中心
                    <span>→</span>
                </a>
            @endguest
        </div>
    </div>
</section>
@endsection
