# AI 副业情报局 - Windows 电脑部署文档

> **适用环境：** Windows 10/11  
> **最后更新：** 2026-03-28  
> **预计耗时：** 30-60 分钟

---

## 📋 目录

1. [环境准备](#环境准备)
2. [安装 Docker Desktop](#安装-docker-desktop)
3. [安装 Git](#安装-git)
4. [安装 Node.js](#安装-nodejs)
5. [安装 OpenClaw](#安装-openclaw)
6. [部署 Laravel 项目](#部署-laravel-项目)
7. [配置 OpenClaw 定时任务](#配置-openclaw-定时任务)
8. [验证部署](#验证部署)
9. [常见问题](#常见问题)

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
- [ ] Node.js v20+
- [ ] OpenClaw
- [ ] VS Code（推荐编辑器）

---

## 🐳 安装 Docker Desktop

### 步骤 1：下载 Docker Desktop

访问：https://www.docker.com/products/docker-desktop/

点击 **Download for Windows**

### 步骤 2：安装 Docker

1. 双击下载的安装包 `Docker Desktop Installer.exe`
2. 勾选 **Use WSL 2 instead of Hyper-V**（推荐）
3. 点击 **OK** 开始安装
4. 安装完成后重启电脑

### 步骤 3：启动 Docker

1. 打开 Docker Desktop
2. 等待 Docker 启动完成（右下角图标变绿）
3. 打开 PowerShell，执行：
   ```powershell
   docker --version
   docker-compose --version
   ```
   应该显示 Docker 和 Docker Compose 版本号

### 步骤 4：配置 WSL 2（如果使用）

```powershell
# 1. 启用 WSL 2
wsl --install

# 2. 设置 WSL 2 为默认
wsl --set-default-version 2

# 3. 重启电脑
# 4. 重新启动 Docker Desktop
```

---

## 📥 安装 Git

### 步骤 1：下载 Git

访问：https://git-scm.com/download/win

下载并安装 **64 位 Git for Windows**

### 步骤 2：安装配置

安装过程中保持默认设置即可：
- 选择 **Git from the command line and also from 3rd-party software**
- 选择 **Use the OpenSSL library**
- 选择 **Checkout Windows-style, commit Unix-style line endings**

### 步骤 3：验证安装

打开 PowerShell，执行：
```powershell
git --version
```
应该显示 Git 版本号

---

## 🟢 安装 Node.js

### 步骤 1：下载 Node.js

访问：https://nodejs.org/

下载并安装 **LTS 版本**（推荐 v20+）

### 步骤 2：安装配置

双击安装包，保持默认设置，一路 Next 即可。

### 步骤 3：验证安装

打开 PowerShell，执行：
```powershell
node --version
npm --version
```
应该显示 Node.js 和 npm 版本号

---

## 🦞 安装 OpenClaw

### 步骤 1：安装 OpenClaw

以**管理员身份**打开 PowerShell，执行：

```powershell
# 全局安装 OpenClaw
npm install -g openclaw

# 验证安装
openclaw --version
```

### 步骤 2：初始化 OpenClaw

```powershell
# 创建工作目录
mkdir C:\openclaw-workspace
cd C:\openclaw-workspace

# 初始化 OpenClaw
openclaw setup

# 按照提示完成：
# 1. 选择 main 模式
# 2. 配置阿里云百炼 API Key（如果有）
# 3. 完成初始化
```

### 步骤 3：启动 OpenClaw Gateway

```powershell
# 启动 Gateway
openclaw gateway

# 保持后台运行
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
Copy-Item .env.example .env

# 编辑 .env 文件（使用记事本或 VS Code）
notepad .env
```

编辑以下内容：

```bash
# 应用配置
APP_NAME="AI 副业情报局"
APP_ENV=production
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

# 阿里云百炼 API Key
DASHSCOPE_API_KEY=sk-你的 API-Key
```

保存并关闭文件。

### 步骤 3：生成 APP_KEY

```powershell
# 启动 Docker 容器
docker-compose up -d

# 等待 30 秒让容器启动
Start-Sleep -Seconds 30

# 生成 APP_KEY
docker-compose exec php php artisan key:generate
```

### 步骤 4：运行数据库迁移

```powershell
# 进入 PHP 容器
docker-compose exec php bash

# 运行迁移
php artisan migrate

# 创建管理员用户（重要！）
php artisan tinker
>>> $user = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@ai-side.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin'
]);
>>> echo "User ID: " . $user->id;
>>> exit

# 退出容器
exit
```

---

## ⏰ 配置 OpenClaw 定时任务

### 步骤 1：获取 Gateway Token

```powershell
cd C:\openclaw-workspace

# 查看配置文件
Get-Content openclaw.json | Select-String "token"

# 复制 token 值，例如："token": "a58608bbd02545968ef99d8452530584"
```

### 步骤 2：创建定时任务

```powershell
cd C:\openclaw-workspace

openclaw cron add `
  --name="AI 内容自动采集" `
  --description="每小时自动采集 AI 文章、项目、职位和知识库" `
  --cron="0 * * * *" `
  --message="你是一个专业的 AI 内容采集助手。请使用 web_search 搜索最新的 AI 相关内容，整理后推送到 Laravel 项目。

任务要求：
1. 搜索 10 篇最新的 AI 大模型相关文章
2. 搜索 20 个热门的 GitHub AI 项目
3. 搜索 20 个 AI 相关招聘职位
4. 生成 2 篇 AI 技术教程文档

数据格式：
- 文章：{\"type\":\"articles\",\"items\":[{\"title\":\"标题\",\"summary\":\"摘要\",\"content\":\"内容\",\"url\":\"链接\",\"cover_image\":\"封面图 URL\"}]}
- 项目：{\"type\":\"projects\",\"items\":[{\"name\":\"项目名\",\"description\":\"描述\",\"url\":\"GitHub 链接\",\"stars\":数字,\"forks\":数字}]}
- 职位：{\"type\":\"jobs\",\"items\":[{\"title\":\"职位\",\"company_name\":\"公司\",\"salary\":\"薪资\",\"city\":\"城市\",\"description\":\"描述\"}]}
- 知识库：{\"type\":\"knowledge\",\"items\":[{\"title\":\"标题\",\"content\":\"HTML 内容\"}]}

推送方式：
POST http://host.docker.internal:8081/api/openclaw/webhook
Headers: Content-Type: application/json, X-API-Token: openclaw-ai-fetcher-2026

请确保返回有效的 JSON 格式。" `
  --model="bailian/qwen3.5-plus" `
  --thinking="high" `
  --session="isolated" `
  --announce
```

### 步骤 3：验证定时任务

```powershell
# 查看定时任务列表
openclaw cron list

# 应该看到：
# AI 内容自动采集  0 * * * *  enabled

# 立即测试一次
openclaw cron run --name="AI 内容自动采集"
```

---

## ✅ 验证部署

### 1. 检查所有服务

```powershell
# Docker 服务
docker-compose ps

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
```

### 4. 验证数据采集

等待定时任务执行完成后（约 2-5 分钟）：

```powershell
# 进入 PHP 容器
docker-compose exec php bash

# 查看日志
tail -f storage/logs/laravel.log | grep -i "openclaw\|webhook"

# 检查数据库
php artisan tinker
>>> App\Models\Article::count()
>>> App\Models\Project::count()
>>> App\Models\Job::count()
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
```bash
# Windows Docker 使用 host.docker.internal
# 修改 OpenClaw 定时任务中的 URL 为：
http://host.docker.internal:8081/api/openclaw/webhook
```

### Q3: 数据库连接失败

**症状：** Laravel 无法连接 MySQL

**解决：**
```powershell
# 1. 检查 .env 配置
# DB_HOST=mysql  # Docker 中使用服务名

# 2. 检查 MySQL 是否运行
docker-compose ps mysql

# 3. 重启 MySQL 容器
docker-compose restart mysql
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

### Q5: 职位保存失败

**症状：** 日志显示 `user_id` 外键错误

**解决：**
```powershell
# 确保已创建管理员用户
docker-compose exec php php artisan tinker
>>> App\Models\User::where('role', 'admin')->first()
>>> exit

# 如果没有管理员用户，创建一个
docker-compose exec php php artisan tinker
>>> App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@ai-side.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin'
])
>>> exit
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
- [ ] 管理员用户已创建

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

1. **配置开机自启**
   - Docker Desktop 设置为开机自启
   - OpenClaw Gateway 设置为开机自启

2. **配置备份策略**
   - 定期备份数据库
   - 备份 `.env` 文件

3. **定期查看日志**
   - 确保定时任务正常执行
   - 检查错误日志

4. **更新维护**
   - 定期拉取最新代码
   - 运行数据库迁移

---

## 📝 PowerShell 快捷命令

```powershell
# 查看所有 Docker 容器
docker-compose ps

# 重启所有服务
docker-compose restart

# 查看日志
docker-compose logs -f

# 进入 PHP 容器
docker-compose exec php bash

# 查看 OpenClaw 定时任务
openclaw cron list

# 手动执行定时任务
openclaw cron run --name="AI 内容自动采集"
```

---

**文档版本：** 1.0  
**最后更新：** 2026-03-28  
**适用版本：** AI 副业情报局 v1.7+
