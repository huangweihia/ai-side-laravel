# AI 副业情报局

> 🤖 每天 5 分钟，掌握 AI 赚钱机会

一个基于 Laravel + Filament 的 AI 项目推荐和副业变现信息平台。

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)
![Filament](https://img.shields.io/badge/Filament-3.x-FDAE4D?style=flat)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=flat&logo=docker)

---

## 🚀 快速开始

### 环境要求

- Docker Desktop（Windows/Mac）
- 或 PHP 8.2+ MySQL 8.0+ Redis（本地开发）

### 一键部署

```bash
# 1. 克隆项目
git clone https://github.com/YOUR_USERNAME/ai-side-laravel.git
cd ai-side-laravel

# 2. 复制配置文件
cp .env.example .env

# 3. 启动 Docker
docker-compose up -d

# 4. 初始化
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan make:filament-user

# 5. 访问
# 前台：http://localhost:8081
# 后台：http://localhost:8081/admin
```

**详细部署文档**：[docs/02-部署文档/完整部署手册.md](docs/02-部署文档/完整部署手册.md)

---

## 📦 功能特性

### 用户端
- ✅ 每日 AI 项目推荐（GitHub 热门）
- ✅ 副业变现点子
- ✅ 付费会员系统
- ✅ 邮件订阅推送
- ✅ 项目变现分析

### 管理端（Filament）
- ✅ 用户管理
- ✅ 文章管理（富文本编辑器）
- ✅ 项目管理
- ✅ 订单管理
- ✅ 系统设置

### 自动化
- ✅ GitHub 项目自动收集
- ✅ AI 分析评分
- ✅ 内容自动生成
- ✅ 定时邮件推送

---

## 🏗️ 技术架构

```
┌─────────────────────────────────────────────────────────┐
│                    用户端                                │
│  首页 · 项目库 · 文章中心 · 会员中心 · 个人中心          │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│                    管理端 (Filament)                     │
│  仪表盘 · 内容管理 · 用户管理 · 订单管理 · 数据统计      │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│                    自动化层                              │
│  GitHub 收集 · AI 分析 · 内容生成 · 邮件推送            │
└─────────────────────────────────────────────────────────┘
```

### 技术栈

| 组件 | 技术 | 说明 |
|------|------|------|
| **后端框架** | Laravel 10.x | PHP 8.2+ |
| **后台管理** | Filament v3 | 基于 Livewire |
| **数据库** | MySQL 8.0 | 主数据库 |
| **缓存** | Redis | 会话/缓存/队列 |
| **部署** | Docker | 容器化部署 |
| **测试** | PHPUnit | 单元/功能测试 |

---

## 📁 项目结构

```
ai-side-laravel/
├── app/
│   ├── Models/              # 数据模型
│   ├── Filament/Resources/  # 后台资源
│   └── Services/            # 服务层
├── database/
│   ├── migrations/          # 数据库迁移
│   └── seeders/             # 数据填充
├── tests/
│   ├── Unit/                # 单元测试
│   └── Feature/             # 功能测试
├── docker/
│   └── php/Dockerfile       # PHP 容器配置
├── docker-compose.yml       # Docker 编排
├── .env.example             # 环境变量示例
├── docs/                    # 项目文档（部署见 docs/02-部署文档/完整部署手册.md）
└── README.md                # 本文件
```

---

## 📊 数据库设计

### 核心表

| 表名 | 说明 | 字段数 |
|------|------|--------|
| `users` | 用户表 | 11 |
| `subscriptions` | 会员订阅表 | 10 |
| `orders` | 订单表 | 12 |
| `categories` | 分类表 | 6 |
| `articles` | 文章表 | 15 |
| `projects` | 项目表 | 13 |
| `email_subscribers` | 邮件订阅表 | 5 |
| `settings` | 系统设置表 | 5 |

---

## 🧪 测试

```bash
# 运行所有测试
docker-compose exec php php artisan test

# 运行特定测试
docker-compose exec php php artisan test --filter ProjectTest

# 生成覆盖率报告
docker-compose exec php php artisan test --coverage-html=coverage
```

---

## 💰 商业模式

### 会员计划

| 等级 | 价格 | 权益 |
|------|------|------|
| **免费用户** | ¥0 | 每日简报 Top5、基础项目推荐 |
| **年度会员** | ¥199/年 | 完整日报、周报、资源包、社群 |
| **终身会员** | ¥999 | 所有权益 + 1v1 咨询 + 资源对接 |

### 收入预测

- 第 1 个月：10 个付费会员 = ¥1,990
- 第 3 个月：100 个付费会员 = ¥19,900
- 第 6 个月：500 个付费会员 = ¥99,500/年

---

## 📖 文档

- **[docs/README.md](docs/README.md)** - 文档索引
- **[docs/02-部署文档/完整部署手册.md](docs/02-部署文档/完整部署手册.md)** - 部署与上线（唯一权威）
- **[docs/03-测试文档/](docs/03-测试文档/)** - 测试相关说明

---

## 🤝 贡献

欢迎提交 Issue 和 Pull Request！

---

## 📄 许可证

MIT License

---

## 📞 联系方式

- **Email**: 2801359160@qq.com
- **GitHub**: [@YOUR_USERNAME](https://github.com/YOUR_USERNAME)

---

**AI 副业情报局** - 抓住 AI 时代的每一个赚钱机会！💰
