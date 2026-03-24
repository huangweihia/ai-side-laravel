<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AI 副业情报局 - 用 AI 赋能副业，让赚钱变得更简单">
    <title>@yield('title', config('app.name', 'AI 副业情报局'))</title>
    
    <script src="{{ asset('js/ui-components.js') }}" defer></script>
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #ec4899;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --dark-light: #1e293b;
            --gray: #64748b;
            --gray-light: #94a3b8;
            --light: #f1f5f9;
            --white: #ffffff;
            --gradient-primary: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
            --gradient-dark: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'PingFang SC', 'Hiragino Sans GB', 'Microsoft YaHei', sans-serif;
            background: var(--dark);
            color: var(--white);
            line-height: 1.6;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; transition: all 0.2s ease; }
        ul { list-style: none; }
        img { max-width: 100%; display: block; }

        /* Container */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-brand-icon {
            font-size: 28px;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-link {
            padding: 10px 18px;
            border-radius: var(--radius);
            color: var(--gray-light);
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .navbar-link:hover {
            color: var(--white);
            background: rgba(255, 255, 255, 0.1);
        }

        .navbar-link.active {
            color: var(--white);
            background: var(--primary);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            outline: none;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-sm { padding: 8px 16px; font-size: 13px; }
        .btn-lg { padding: 16px 32px; font-size: 16px; }

        /* Main Content */
        .main { padding-top: 80px; min-height: 100vh; }

        /* Hero Section */
        .hero {
            position: relative;
            padding: 120px 0 100px;
            text-align: center;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: 50%;
            transform: translateX(-50%);
            width: 1000px;
            height: 1000px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 50px;
            font-size: 13px;
            color: var(--primary-light);
            margin-bottom: 24px;
        }

        .hero-title {
            font-size: clamp(36px, 5vw, 64px);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: clamp(16px, 2vw, 20px);
            color: var(--gray-light);
            max-width: 600px;
            margin: 0 auto 40px;
            line-height: 1.8;
        }

        .hero-cta {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 60px;
            flex-wrap: wrap;
        }

        .hero-stat {
            text-align: center;
        }

        .hero-stat-value {
            font-size: 36px;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-stat-label {
            font-size: 14px;
            color: var(--gray-light);
            margin-top: 4px;
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background: var(--dark-light);
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-label {
            display: inline-block;
            padding: 6px 14px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            color: var(--primary-light);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 700;
            margin-bottom: 16px;
        }

        .section-desc {
            font-size: 16px;
            color: var(--gray-light);
            max-width: 500px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }

        .feature-card {
            background: var(--dark);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-lg);
            padding: 32px;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-primary);
            border-radius: var(--radius);
            font-size: 26px;
            margin-bottom: 20px;
        }

        .feature-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .feature-desc {
            color: var(--gray-light);
            line-height: 1.8;
            font-size: 15px;
        }

        /* Cards */
        .card {
            background: var(--dark);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: var(--shadow-xl);
        }

        .card-image {
            height: 200px;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
        }

        .card-body { padding: 24px; }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .card-desc {
            color: var(--gray-light);
            font-size: 14px;
            line-height: 1.8;
            margin-bottom: 16px;
        }

        .card-meta {
            display: flex;
            gap: 16px;
            font-size: 13px;
            color: var(--gray);
        }

        .card-tag {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 50px;
            font-size: 12px;
            color: var(--primary-light);
            font-weight: 500;
        }

        /* Footer */
        .footer {
            background: var(--dark-light);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 60px 0 30px;
            margin-top: 100px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand {
            font-size: 20px;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
        }

        .footer-desc {
            color: var(--gray-light);
            font-size: 14px;
            line-height: 1.8;
            max-width: 300px;
        }

        .footer-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--white);
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-link {
            color: var(--gray-light);
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .footer-link:hover { color: var(--primary-light); }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--gray);
            font-size: 13px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-menu { display: none; }
            .hero { padding: 80px 0 60px; }
            .hero-stats { gap: 20px; }
            .features-grid { grid-template-columns: 1fr; }
            .footer-content { grid-template-columns: 1fr; gap: 30px; }
        }

        /* Forms */
        .form-group { margin-bottom: 20px; }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--gray-light);
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            background: var(--dark-light);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            color: var(--white);
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-input::placeholder { color: var(--gray); }

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-content">
            <a href="{{ route('home') }}" class="navbar-brand">
                <span class="navbar-brand-icon">🤖</span>
                <span>AI 副业情报局</span>
            </a>
            
            <div class="navbar-menu">
                <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">首页</a>
                <a href="{{ route('projects.index') }}" class="navbar-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">项目</a>
                <a href="{{ route('articles.index') }}" class="navbar-link {{ request()->routeIs('articles.*') ? 'active' : '' }}">文章</a>
                <a href="/admin" class="navbar-link">后台</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">个人中心</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">登出</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="navbar-link">登录</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-primary">免费注册</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="main">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div>
                    <div class="footer-brand">🤖 AI 副业情报局</div>
                    <p class="footer-desc">用 AI 赋能副业，让赚钱变得更简单。每天推送最新 AI 项目、变现灵感和学习资源。</p>
                </div>
                <div>
                    <div class="footer-title">产品</div>
                    <div class="footer-links">
                        <a href="{{ route('projects.index') }}" class="footer-link">项目库</a>
                        <a href="{{ route('articles.index') }}" class="footer-link">文章</a>
                        <a href="{{ route('vip') }}" class="footer-link">VIP 会员</a>
                    </div>
                </div>
                <div>
                    <div class="footer-title">资源</div>
                    <div class="footer-links">
                        <a href="{{ route('learning') }}" class="footer-link">学习指南</a>
                        <a href="{{ route('tools') }}" class="footer-link">工具推荐</a>
                        <a href="#" class="footer-link">社群</a>
                    </div>
                </div>
                <div>
                    <div class="footer-title">关于</div>
                    <div class="footer-links">
                        <a href="{{ route('about') }}" class="footer-link">关于我们</a>
                        <a href="{{ route('contact') }}" class="footer-link">联系方式</a>
                        <a href="{{ route('privacy') }}" class="footer-link">隐私政策</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} AI 副业情报局。All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
