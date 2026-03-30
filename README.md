# AI 副业情报局

> 🤖 每天 10 分钟，发现 AI 副业机会

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
copy .env.example .env

# 3. 启动 Docker
docker-compose up -d

# 4. 初始化
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan make:filament-user
# 5. 启用 /storage 映射（图片/头像/上传文件访问依赖）
docker-compose exec php php artisan storage:link
# 6. 访问
# 前台：`http://localhost:8081`
# 后台：`http://localhost:8081/admin`
```

**部署与运维文档**：Linux 部署参考 [`docs/服务器部署文档.md`](docs/服务器部署文档.md)，Windows 部署参考 [`docs/Windows 部署文档.md`](docs/Windows%20部署文档.md)，以及 [`docs/文档整理说明.md`](docs/文档整理说明.md) 中的 docs 维护约定。

---

## ⚙️ 环境变量要点（必须关注）
微信 Native 支付与自动化采集是系统的关键依赖：
- `WECHAT_PAY_ENABLED=true/false`
- `WECHAT_PAY_APP_ID`
- `WECHAT_PAY_MCH_ID`
- `WECHAT_PAY_MCH_SECRET_V3_KEY`（API v3 商户密钥）
- `WECHAT_PAY_MCH_SERIAL_NO`
- `WECHAT_PAY_MCH_PRIVATE_KEY_PATH`（`storage/certs/wechat/apiclient_key.pem`）
- `WECHAT_PAY_PLATFORM_CERT_PATH`（`storage/certs/wechat/wechatpay_platform.pem`）
- `WECHAT_PAY_NOTIFY_URL`（必须与微信后台配置一致）
- `WECHAT_PAY_PLAN_MONTHLY_YUAN / _ORIGINAL_YUAN`、`WECHAT_PAY_PLAN_YEARLY_YUAN / _ORIGINAL_YUAN`、`WECHAT_PAY_PLAN_LIFETIME_YUAN / _ORIGINAL_YUAN`

此外，OpenClaw webhook 相关 token 需要配置正确（用于自动化内容写入）。

---

## 📦 功能特性

### 用户端
- 价值主张驱动：首页信息流聚合，让用户快速理解“能做什么、怎么做”
- 项目库：列表/详情展示（含变现路径与技术栈视角）
- 文章中心：内容浏览、评论/收藏、非 VIP 遮罩策略
- 知识库（规划中）：检索与 AI 问答能力（与 VIP 付费墙联动）
- 会员中心：VIP 权益说明与开通流程（微信 Native 扫码）
- 个人中心：头像/订阅偏好/收藏列表/浏览历史（提升留存）

### 管理端（Filament）
- 内容与运营：文章/项目/分类管理
- 内容与运营：订单管理（对应 `orders` 与 `subscriptions`）
- 内容与运营：系统通知与邮件订阅偏好（运营投放）
- 任务与配置：采集/定时任务（自动化工作流）
- 任务与配置：系统设置（例如注册自动 VIP 开关与天数）

### 自动化
- 自动化采集：按来源去重、结构化落库，保证内容可用性
- 内容生成与结构化：统一格式，面向前台渲染
- 邮件推送：日报/周报/通知与用户订阅偏好对齐
- 付费闭环：微信 Native 回调后自动激活 VIP 权限（订单->订阅->用户）

---

## 🏗️ 技术架构

### 分层视角（从页面到支付/上传）
- 展示层（Web）：Blade 前台页面（项目/文章/知识库/VIP/个人中心）+ 布局/富文本/上传预览
- 应用层（业务编排）：Controller + Service（OpenClaw Webhook 写入、VIP 微信 Native 支付、通知触发、上传落盘、支付状态轮询）
- 数据层：MySQL（用户/订阅/订单/系统通知等）+ Redis（缓存支付二维码、会话与队列）
- 基础设施与文件：Laravel `public` 磁盘（对外 `/storage/...`）、证书目录（微信支付）、SMTP 邮件服务
- 外部集成：OpenClaw Gateway/Webhook、GitHub API、微信支付 API v3、QQ SMTP

```text
用户浏览
  ↓
Blade 页面 ──> Controller/Service ──> MySQL/Redis/Storage
  |                               └─> 外部服务（微信支付/邮件/OpenClaw/GitHub）
```

---

## 🗺️ 产品规划（PRD 路线图摘要）
该路线图来自 `docs/01-项目规划/产品需求文档.md`，结合当前仓库已落地能力做了“实现状态”标注：



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

## 🧾 会员与支付（VIP 核心链路）
当前 VIP 采用 **微信 Native 扫码支付（API v3）**，整体流程是：

1. `GET /vip/pay/{plan}`：进入套餐确认页（`vip-pay-confirm`）
2. 点击“使用微信扫码支付”：前端通过 `POST /payments/wechat/create` 创建订单
3. 跳转到二维码页：`GET /payments/wechat/order/{orderNo}`
4. 前端轮询支付状态：`GET /payments/wechat/order/{orderNo}/status`
5. 微信异步回调落库：`POST /payments/wechat/notify`（验签 + 解密 + 更新 `orders`/`subscriptions`/用户 VIP 权限）

建议关注两类排障：
- 二维码过期：重新发起创建订单
- VIP 未生效：查看回调日志与订单 `status/payment_method` 是否更新

---

## 📁 项目结构

```text
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

## 📤 上传与图片访问策略（解决“成功但看不到”的根源）
系统把上传文件统一落盘到 Laravel 的 `public` 磁盘（`storage/app/public/*`），并通过 `/storage/...` 对外访问。

对应入口：
- 头像：`HomeController@uploadAvatar` -> `storage/app/public/avatars`
- 反馈截图：`ProblemFeedbackController@store` -> `storage/app/public/feedback`
- 富文本图片：`SubmissionController@uploadImage` -> `storage/app/public/submissions/images`

如果你遇到：
- 前端弹“上传成功”但图片不显示

优先排查：
1. 是否执行了 `php artisan storage:link`
2. 文件是否真实存在于 `storage/app/public/` 对应路径
3. 容器/服务器是否具备写入权限（volume/perms）

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

| 等级 | 折后价格 | 打折前 | 权益（展示口径） |
|------|----------|--------|------------------|
| **免费用户** | ¥0 | - | 每日简报 Top5、基础项目推荐 |
| **月度会员** | ¥9.9/月 | ¥29/月 | 解锁全部文章/项目库、每日资讯日报（不含一对一） |
| **年度会员** | ¥88/年 | ¥288/年 | 解锁全部文章/项目库、每日资讯日报、专属资源下载 |
| **终身会员** | ¥388/一次买断 | ¥888 | 含月度/年度全部权益 + 长期内容更新（不含续费烦恼） |

### 收入预测（示例）
（示例仅按月度会员 `¥9.9/月` 线性估算）
- 第 1 个月：10 个付费会员 = ¥99
- 第 3 个月：100 个付费会员 = ¥990
- 第 6 个月：500 个付费会员 = ¥4,950

---

## 📖 项目文档（建议与优先级）
权威文档入口主要在 `docs/01-项目规划` 与 `docs/05-开发文档`：

- [`docs/01-项目规划/产品需求文档.md`](docs/01-项目规划/产品需求文档.md)：PRD（MVP → 成长期），包含完整功能地图、验收标准与路线图
- [`docs/01-项目规划/产品详细需求.md`](docs/01-项目规划/产品详细需求.md)：更细的产品结构与功能拆解（可用于对齐实现）
- [`docs/01-项目规划/商业化方案.md`](docs/01-项目规划/商业化方案.md)：收费策略、增收模块与路线（含 MCP 搜索整合思路）
- [`docs/01-项目规划/产品评审报告.md`](docs/01-项目规划/产品评审报告.md)：审查结论与优化清单（用于迭代优先级）
- [`docs/文档整理说明.md`](docs/文档整理说明.md)：docs 结构、合并说明与维护规范
- [`docs/05-开发文档/迁移与前台功能变更记录.md`](docs/05-开发文档/迁移与前台功能变更记录.md)：迁移批次与前台关键路径变更对照
- [`docs/05-开发文档/微信支付Native接入.md`](docs/05-开发文档/微信支付Native接入.md)：微信 Native（API v3）配置、证书、路由与回调约定
- [`docs/服务器部署文档.md`](docs/服务器部署文档.md)：Linux 服务器线上部署（Docker/验证/排障）

---

## 🤝 贡献

欢迎提交 Issue 和 Pull Request！

---

## 📄 许可证

MIT License

---

## 📞 联系方式

- **Email**: 2801359160@qq.com
- **GitHub**: [@YOUR_USERNAME](https://github.com/huangweihia/ai-side-laravel)

---

**AI 副业情报局** - 抓住 AI 时代的每一个赚钱机会！💰
