# Windows 部署文档 - AI 副业情报局

> 本文档用于在 Windows 机器上完整部署 AI 副业情报局项目，包括 OpenClaw 自动化采集系统。  
> **最后更新：** 2026-03-28  
> **适用系统：** Windows 10/11

---

## 📋 目录

1. [环境准备](#环境准备)
2. [安装 Docker Desktop](#安装-docker-desktop)
3. [安装 OpenClaw](#安装-openclaw)
4. [部署 Laravel 项目](#部署-laravel-项目)
5. [配置数据库](#配置数据库)
6. [配置 OpenClaw 定时任务](#配置-openclaw-定时任务)
7. [验证部署](#验证部署)
8. [常见问题](#常见问题)

---

## 🖥️ 环境准备

### 系统要求

| 组件 | 要求 |
|------|------|
| **操作系统** | Windows 10/11 (64 位) |
| **CPU** | 4 核以上（推荐 8 核） |
| **内存** | 16GB 以上（推荐 32GB） |
| **硬盘** | 100GB 可用空间 |
| **网络** | 可访问 GitHub 和阿里云 |

### 需要安装的软件

- [ ] Docker Desktop
- [ ] Git for Windows
- [ ] OpenClaw
- [ ] 文本编辑器（VS Code 推荐）

---

## 🐳 安装 Docker Desktop

### 步骤 1：下载 Docker Desktop

访问：https://www.docker.com/products/docker-desktop/

点击 **Download for Windows**

### 步骤 2：安装 Docker

1. 双击下载的安装包 `Docker Desktop Installer.exe`
2. 勾选 **Use WSL 2 instead of Hyper-V**
3. 点击 **OK** 开始安装
4. 安装完成后重启电脑

### 步骤 3：启动 Docker

1. 打开 Docker Desktop
2. 等待 Docker 启动完成（右下角图标变绿）
3. 打开 PowerShell，执行：
   ```powershell
   docker --version
   ```
   应该显示 Docker 版本号

---

## 🦞 安装 OpenClaw

### 步骤 1：安装 Node.js

访问：https://nodejs.org/

下载并安装 **LTS 版本**（推荐 v20+）

验证安装：
```powershell
node --version
npm --version
```

### 步骤 2：安装 OpenClaw

在 PowerShell 中执行（管理员权限）：

```powershell
# 安装 OpenClaw
npm install -g openclaw

# 验证安装
openclaw --version
```

### 步骤 3：初始化 OpenClaw

```powershell
# 创建工作目录
mkdir C:\openclaw-workspace
cd C:\openclaw-workspace

# 初始化 OpenClaw
openclaw setup
```

按照提示完成：
1. 选择 **main** 模式
2. 配置阿里云百炼 API Key（如果有）
3. 完成初始化

### 步骤 4：启动 OpenClaw Gateway

```powershell
# 启动 Gateway
openclaw gateway

# 保持后台运行（可选）
# 访问 http://localhost:18788 查看控制面板
```

---

## 📦 部署 Laravel 项目

### 步骤 1：克隆项目

```powershell
# 创建项目目录
mkdir C:\ai-side-laravel
cd C:\ai-side-laravel

# 克隆项目（从 Git 或复制文件）
# 方式 1：从 Git 克隆
git clone <你的项目仓库地址> .

# 方式 2：从其他机器复制
# 复制整个项目文件夹到 C:\ai-side-laravel
```

### 步骤 2：配置环境变量

```powershell
# 复制环境配置文件
cp .env.example .env
```

编辑 `.env` 文件，配置以下内容：

```bash
# 应用配置
APP_NAME="AI 副业情报局"
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=false
APP_URL=http://localhost:8081

# 数据库配置（Docker 中使用服务名）
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=ai_side
DB_USERNAME=ai_side
DB_PASSWORD=你的强密码

# Redis 配置
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# 邮件配置（QQ 邮箱）
MAIL_MAILER=smtp
MAIL_HOST=smtp.qq.com
MAIL_PORT=465
MAIL_USERNAME=你的 QQ 邮箱
MAIL_PASSWORD=你的授权码
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=你的 QQ 邮箱
MAIL_FROM_NAME="${APP_NAME}"

# OpenClaw Webhook Token
OPENCLAW_WEBHOOK_TOKEN=openclaw-ai-fetcher-2026

# 阿里云百炼 API Key（如果有）
DASHSCOPE_API_KEY=sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### 步骤 3：生成 APP_KEY

```powershell
docker compose run --rm php php artisan key:generate
```

### 步骤 4：启动 Docker 容器

```powershell
# 启动所有服务
docker compose up -d

# 查看运行状态
docker compose ps

# 应该看到以下服务：
# - mysql (数据库)
# - redis (缓存)
# - php (PHP 应用)
# - nginx (Web 服务器)
```

### 步骤 5：运行数据库迁移

```powershell
# 进入 PHP 容器
docker compose exec php bash

# 运行迁移
php artisan migrate

# 退出容器
exit
```

### 步骤 6：配置 Nginx

编辑 `docker-compose.yml` 或 Nginx 配置文件，确保：

```nginx
server {
    listen 8081;
    server_name localhost;
    root /var/www/html/public;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### 步骤 7：访问网站

浏览器访问：http://localhost:8081

---

## 🗄️ 配置数据库

### 方式 1：使用 Docker 中的 MySQL（推荐）

```powershell
# 查看 MySQL 容器
docker compose ps mysql

# 进入 MySQL
docker compose exec mysql mysql -u ai_side -p

# 创建数据库（如果迁移未自动创建）
CREATE DATABASE IF NOT EXISTS ai_side CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 方式 2：使用外部 MySQL

1. 安装 MySQL 8.0 或更高版本
2. 创建数据库：
   ```sql
   CREATE DATABASE ai_side CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'ai_side'@'%' IDENTIFIED BY '强密码';
   GRANT ALL PRIVILEGES ON ai_side.* TO 'ai_side'@'%';
   FLUSH PRIVILEGES;
   ```
3. 修改 `.env` 中的数据库配置：
   ```bash
   DB_HOST=127.0.0.1
   DB_PORT=3306
   ```

---

## ⏰ 配置 OpenClaw 定时任务

### 步骤 1：获取 Gateway Token

在 OpenClaw 工作目录执行：

```powershell
# 查看配置文件
cat C:\openclaw-workspace\openclaw.json

# 找到 token 字段，复制 token 值
# 例如："token": "a58608bbd02545968ef99d8452530584"
```

### 步骤 2：创建定时任务配置文件

创建文件 `C:\openclaw-workspace\ai-content-cron.json`：

```json
{
  "name": "AI 内容自动采集",
  "schedule": {
    "kind": "cron",
    "expr": "0 * * * *",
    "tz": "Asia/Shanghai"
  },
  "payload": {
    "kind": "agentTurn",
    "message": "你是一个专业的 AI 内容采集助手。请使用 web_search 搜索最新的 AI 相关内容，整理后推送到 Laravel 项目。\n\n## 任务要求（每小时执行）\n\n### 1. 搜索 AI 文章（10 篇，带封面图）\n搜索关键词：\"AI 大模型 最新动态 GPT-5 AI 工具\"\n整理格式：\n{\"type\":\"articles\",\"items\":[{\"title\":\"标题\",\"summary\":\"200 字摘要\",\"content\":\"1000 字以上内容\",\"url\":\"原文链接\",\"cover_image\":\"封面图片 URL\"}]}\n\n### 2. 搜索 GitHub 项目（20 个）\n搜索关键词：\"GitHub trending machine-learning AI GPT LLM\"\n整理格式：\n{\"type\":\"projects\",\"items\":[{\"name\":\"项目名\",\"description\":\"详细描述\",\"url\":\"GitHub 链接\",\"stars\":数字,\"forks\":数字,\"language\":\"语言\"}]}\n\n### 3. 搜索 AI 职位（20 个）\n搜索关键词：\"AI 工程师 大模型 AIGC 招聘 薪资\"\n整理格式：\n{\"type\":\"jobs\",\"items\":[{\"title\":\"职位\",\"company_name\":\"公司\",\"salary\":\"薪资\",\"city\":\"城市\",\"description\":\"职位描述\",\"url\":\"招聘链接\"}]}\n\n### 4. 生成 AI 教程（2 篇）\n主题：\"ChatGPT 使用技巧\" 和 \"AI 工具实战教程\"\n整理格式：\n{\"type\":\"knowledge\",\"items\":[{\"title\":\"标题\",\"content\":\"HTML 格式内容\"}]}\n\n## 推送方式\n\n对每种类型分别推送：\nPOST http://host.docker.internal:8081/api/openclaw/webhook\nHeaders: Content-Type: application/json, X-API-Token: openclaw-ai-fetcher-2026\n\n## 重要提示\n\n1. 必须返回有效的 JSON 格式\n2. 每个类型单独推送一次\n3. 使用 web_search 获取真实数据\n4. 尽量获取带封面图的文章\n5. 内容要详细丰富（文章 1000 字+）\n\n现在开始执行！",
    "model": "bailian/qwen3.5-plus",
    "thinking": "high"
  },
  "sessionTarget": "isolated",
  "enabled": true,
  "delivery": {
    "mode": "announce"
  }
}
```

### 步骤 3：导入定时任务

```powershell
cd C:\openclaw-workspace

# 导入定时任务
openclaw cron add --name="AI 内容自动采集" --job-file=ai-content-cron.json

# 验证导入
openclaw cron list
```

### 步骤 4：测试定时任务

```powershell
# 立即执行一次测试
openclaw cron run --name="AI 内容自动采集"

# 查看执行日志
openclaw logs | Select-Object -Last 50
```

---

## ✅ 验证部署

### 1. 检查所有服务

```powershell
# Docker 服务
docker compose ps

# 应该看到：
# mysql     Up
# redis     Up
# php       Up
# nginx     Up

# OpenClaw Gateway
openclaw gateway status
```

### 2. 测试网站访问

浏览器访问：
- 前台：http://localhost:8081
- 后台：http://localhost:8081/admin

### 3. 测试 API 接口

```powershell
# 测试 Webhook API
curl -X POST http://localhost:8081/api/openclaw/webhook `
  -H "Content-Type: application/json" `
  -H "X-API-Token: openclaw-ai-fetcher-2026" `
  -d "{\"type\":\"test\",\"items\":[]}"

# 应该返回：{"success":false,"message":"未知类型"}
# 这说明 API 正常工作
```

### 4. 测试 OpenClaw 定时任务

```powershell
# 查看定时任务列表
openclaw cron list

# 应该看到：
# AI 内容自动采集  0 * * * *  enabled

# 查看执行日志
openclaw logs | Select-Object -Last 20
```

### 5. 验证数据采集

等待定时任务执行完成后（约 2-5 分钟）：

```powershell
# 进入 PHP 容器
docker compose exec php bash

# 查看文章数量
php artisan tinker
>>> App\Models\Article::count()

# 查看项目数量
>>> App\Models\Project::count()

# 查看职位数量
>>> App\Models\JobListing::count()

# 退出
exit
```

---

## 🔧 常见问题

### Q1: Docker Desktop 无法启动

**症状：** Docker Desktop 启动失败，显示 WSL 错误

**解决：**
```powershell
# 1. 启用 WSL 2
wsl --install

# 2. 设置 WSL 2 为默认
wsl --set-default-version 2

# 3. 重启电脑
# 4. 重新启动 Docker Desktop
```

### Q2: 容器无法访问宿主机

**症状：** OpenClaw 无法访问 Laravel API

**解决：**
```powershell
# Windows Docker 使用 host.docker.internal
# 修改 OpenClaw 定时任务中的 URL 为：
http://host.docker.internal:8081/api/openclaw/webhook
```

### Q3: 数据库连接失败

**症状：** Laravel 无法连接 MySQL

**解决：**
```bash
# 1. 检查 .env 配置
DB_HOST=mysql  # Docker 中使用服务名

# 2. 检查 MySQL 是否运行
docker compose ps mysql

# 3. 重启 MySQL 容器
docker compose restart mysql
```

### Q4: OpenClaw 定时任务不执行

**症状：** 定时任务未触发

**解决：**
```powershell
# 1. 检查 Gateway 是否运行
openclaw gateway status

# 2. 重启 Gateway
openclaw gateway restart

# 3. 检查定时任务状态
openclaw cron list

# 4. 手动执行一次
openclaw cron run --name="AI 内容自动采集"
```

### Q5: 文章/项目/职位保存失败

**症状：** 日志显示字段不匹配错误

**解决：**
```bash
# 检查数据库表结构
docker compose exec php php artisan tinker
>>> Schema::getColumnListing('job_listings')

# 确保 API 控制器中的字段名与数据库一致
# 参考 DATABASE_SCHEMA.md 文档
```

### Q6: 邮件发送失败

**症状：** 邮件无法发送

**解决：**
```bash
# 1. 检查 QQ 邮箱授权码（不是密码）
# 2. 检查 SMTP 配置
# 3. 查看邮件日志
docker compose exec php tail -f storage/logs/laravel.log | grep -i mail
```

---

## 📊 部署检查清单

### 环境准备
- [ ] Windows 10/11 (64 位)
- [ ] 16GB+ 内存
- [ ] 100GB+ 硬盘空间

### 软件安装
- [ ] Docker Desktop 已安装并运行
- [ ] Git for Windows 已安装
- [ ] Node.js v20+ 已安装
- [ ] OpenClaw 已安装

### 项目部署
- [ ] Laravel 项目已克隆/复制
- [ ] `.env` 文件已配置
- [ ] APP_KEY 已生成
- [ ] Docker 容器已启动
- [ ] 数据库迁移已完成

### OpenClaw 配置
- [ ] Gateway 已启动
- [ ] 定时任务已导入
- [ ] 定时任务已测试
- [ ] Webhook Token 已配置

### 验证
- [ ] 网站可正常访问
- [ ] 后台可正常登录
- [ ] API 接口正常
- [ ] 定时任务正常执行
- [ ] 数据正常保存

---

## 🎯 下一步

部署完成后：

1. **配置域名和 HTTPS**（生产环境）
2. **配置备份策略**（数据库 + 文件）
3. **配置监控告警**（服务状态 + 错误日志）
4. **定期更新内容**（定时任务自动执行）

---

## 📞 技术支持

如遇到问题：
1. 查看日志：`openclaw logs` 和 `docker compose logs`
2. 检查配置：参考本文档和 `DATABASE_SCHEMA.md`
3. 联系管理员

---

**文档版本：** 1.0  
**最后更新：** 2026-03-28  
**适用版本：** AI 副业情报局 v1.7+
