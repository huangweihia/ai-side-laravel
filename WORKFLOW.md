# 📋 AI 副业情报局 - 开发流程记录

> 记录完整的开发和部署流程，避免重复沟通

---

## 🎯 确定的方案

**开发环境：**
- 本地 Windows 开发
- Docker 容器运行项目
- 代码本地编辑，同步到容器
- GitHub 版本管理

**技术栈：**
- Laravel 10.x + PHP 8.2
- Filament v3（后台管理）
- MySQL 8.0 + Redis
- Docker 容器化部署

---

## 📁 项目位置

### 本地路径
```
D:\lewan\ai-side-laravel\
```

### GitHub 仓库
```
https://github.com/huangweihia/ai-side-laravel.git
```

### Docker 容器内路径
```
/var/www/html/
```

---

## 🚀 部署流程（已完成）

### 1. 推送到 GitHub

```powershell
cd D:\lewan\ai-side-laravel

# 配置 Git
git config user.name "海哥"
git config user.email "2801359160@qq.com"

# 添加远程仓库
git remote add origin https://github.com/huangweihia/ai-side-laravel.git

# 推送
git branch -M main
git push -u origin main
```

### 2. Docker 部署

```powershell
cd D:\lewan\ai-side-laravel

# 复制配置
Copy-Item .env.example .env

# 启动容器
docker-compose up -d

# 等待启动
Start-Sleep -Seconds 30

# 初始化
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan make:filament-user
```

### 3. 访问地址

- 前台：http://localhost:8081
- 后台：http://localhost:8081/admin

---

## 💻 日常开发流程

### 方式一：本地开发 + 同步到容器

**1. 本地编辑代码**
```
用 VS Code 或其他编辑器打开 D:\lewan\ai-side-laravel
直接编辑文件
```

**2. 代码自动同步**
```
Docker 已经挂载了本地目录
本地修改后，容器内自动生效
无需手动同步
```

**3. 需要重启的情况**
```powershell
# 修改了 .env 或配置文件
docker-compose restart php

# 修改了 composer.json
docker-compose exec php composer install
```

---

### 方式二：在容器内开发

**1. 进入容器**
```powershell
docker-compose exec php bash
```

**2. 在容器内编辑**
```bash
# 安装编辑器（如果需要）
apt-get update && apt-get install -y vim

# 编辑文件
vim /var/www/html/app/Models/User.php
```

**3. 退出容器**
```bash
exit
```

---

## 🔧 常用命令

### 容器管理

```powershell
# 启动容器
docker-compose up -d

# 停止容器
docker-compose down

# 重启容器
docker-compose restart

# 查看容器状态
docker-compose ps

# 查看日志
docker-compose logs -f
```

### Laravel 命令

```powershell
# 所有 Laravel 命令都在容器内执行
docker-compose exec php php artisan 命令名

# 示例
docker-compose exec php php artisan make:controller UserController
docker-compose exec php php artisan make:model Article
docker-compose exec php php artisan test
```

### Git 操作

```powershell
# 查看状态
git status

# 添加修改
git add .

# 提交
git commit -m "修改说明"

# 推送
git push

# 拉取
git pull
```

---

## 📦 项目结构

```
ai-side-laravel/
├── app/
│   ├── Models/           # 数据模型
│   ├── Filament/         # 后台资源
│   └── Services/         # 服务层
├── database/
│   └── migrations/       # 数据库迁移
├── tests/                # 测试文件
├── docker/               # Docker 配置
├── docker-compose.yml    # Docker 编排
├── .env                  # 环境变量（不要提交到 Git）
└── ...
```

---

## 📊 数据库配置

```env
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=ai_side
DB_USERNAME=ai_side
DB_PASSWORD=aiside123456
```

**访问方式：**
```powershell
# 进入 MySQL 容器
docker-compose exec mysql bash

# 登录 MySQL
mysql -u ai_side -paiside123456 ai_side
```

---

## 🧪 测试

```powershell
# 运行测试
docker-compose exec php php artisan test

# 运行特定测试
docker-compose exec php php artisan test --filter ProjectTest

# 生成覆盖率报告
docker-compose exec php php artisan test --coverage-html=coverage
```

---

## 📝 开发记录

### 2026-03-18 - 项目初始化

**已完成：**
- ✅ Laravel 10.x 项目创建
- ✅ Filament v3 后台配置
- ✅ 数据库迁移（7 个核心表）
- ✅ 6 个 Eloquent 模型
- ✅ 3 个 Filament 资源（User/Article/Project）
- ✅ GitHub 服务（数据收集）
- ✅ 单元测试 + 功能测试（17 个测试用例）
- ✅ Docker 配置
- ✅ 部署文档

**下一步开发：**
1. 用户认证页面（注册/登录）
2. 首页设计
3. GitHub 收集脚本（定时任务）
4. 邮件推送系统
5. 会员系统

---

## 🆘 常见问题

### 容器启动失败
```powershell
# 重启 Docker Desktop
# 然后执行
docker-compose down
docker-compose up -d
```

### 数据库连接失败
```powershell
# 重启 MySQL
docker-compose restart mysql

# 等待 10 秒
Start-Sleep -Seconds 10

# 重试迁移
docker-compose exec php php artisan migrate
```

### 端口被占用
```powershell
# 修改 docker-compose.yml 中的端口
# 比如 8081 改成 8082
# 然后重启
docker-compose down
docker-compose up -d
```

---

## 📞 重要提示

1. **不要修改 `.env` 文件并提交到 Git**（已添加到 .gitignore）
2. **代码修改后通常立即生效**（无需重启容器）
3. **修改配置文件需要重启 PHP 容器**
4. **定期推送到 GitHub 备份代码**

---

**此文档持续更新...**

最后更新：2026-03-18
