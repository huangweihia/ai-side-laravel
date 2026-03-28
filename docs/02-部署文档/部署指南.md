# AI 副业情报局 - 部署文档

> 📘 专为 Docker/Laravel/PHP 新手设计的完整部署指南

---

## 📋 目录

1. [环境准备](#1-环境准备)
2. [获取项目代码](#2-获取项目代码)
3. [Docker 基础](#3-docker-基础)
4. [部署项目](#4-部署项目)
5. [访问网站](#5-访问网站)
6. [常见问题](#6-常见问题)
7. [日常开发](#7-日常开发)

---

## 1. 环境准备

### 1.1 安装 Docker Desktop

**什么是 Docker？**
> Docker 就像一个"容器"，可以把整个运行环境（PHP、MySQL、Redis 等）打包在一起，不用担心电脑环境不兼容。

**Windows 安装步骤：**

1. **访问官网**：https://www.docker.com/products/docker-desktop/
2. **下载安装包**：点击 "Download for Windows"
3. **安装**：
   - 双击下载的安装包
   - 一路点击 "Next"
   - 选择 "Use WSL 2 instead of Hyper-V"（推荐）
   - 点击 "Install"
   - 安装完成后重启电脑

4. **启动 Docker Desktop**：
   - 桌面会出现 Docker Desktop 图标
   - 双击打开
   - 等待底部状态栏变绿（显示 "Engine running"）

![Docker Desktop](https://docs.docker.com/desktop/images/dd-win-wsl2.png)

**验证安装：**
```powershell
# 打开 PowerShell（按 Win+X，选择"Windows PowerShell"）
docker --version
docker-compose --version
```

看到版本号就说明安装成功了！✅

---

### 1.2 安装 Git（可选，用于下载代码）

**什么是 Git？**
> Git 是一个代码管理工具，可以从 GitHub 下载项目代码。

**安装步骤：**

1. **访问官网**：https://git-scm.com/download/win
2. **下载安装包**：点击下载
3. **安装**：一路 "Next" 即可
4. **验证**：
```powershell
git --version
```

---

## 2. 获取项目代码

### 方法一：使用 Git（推荐）

```powershell
# 1. 创建项目文件夹
mkdir D:\lewan
cd D:\lewan

# 2. 从 GitHub 克隆项目（替换为你的仓库地址）
git clone https://github.com/YOUR_USERNAME/ai-side-laravel.git

# 3. 进入项目目录
cd ai-side-laravel
```

### 方法二：手动下载

1. **打开 GitHub 仓库页面**
2. **点击绿色按钮 "Code"**
3. **选择 "Download ZIP"**
4. **解压到 `D:\lewan\ai-side-laravel`**

---

## 3. Docker 基础

### 3.1 常用 Docker 命令

```powershell
# 查看正在运行的容器
docker ps

# 查看所有容器（包括停止的）
docker ps -a

# 停止容器
docker stop 容器名

# 启动容器
docker start 容器名

# 重启容器
docker restart 容器名

# 查看容器日志
docker logs 容器名

# 进入容器内部
docker exec -it 容器名 bash

# 删除容器
docker rm 容器名

# 删除镜像
docker rmi 镜像名
```

### 3.2 Docker Compose

**什么是 Docker Compose？**
> Docker Compose 可以同时管理多个容器（比如 PHP + MySQL + Redis），一键启动/停止。

```powershell
# 启动所有容器（后台运行）
docker-compose up -d

# 停止所有容器
docker-compose down

# 查看容器状态
docker-compose ps

# 查看日志
docker-compose logs -f

# 重启某个服务
docker-compose restart php

# 重建容器
docker-compose up -d --build
```

---

## 4. 部署项目

### 4.1 一键部署（推荐）

**在 PowerShell（管理员）中执行：**

```powershell
# 1. 进入项目目录
cd D:\lewan\ai-side-laravel

# 2. 运行部署脚本
.\deploy-windows.ps1
```

脚本会自动完成：
- ✅ 检查 Docker 是否安装
- ✅ 复制配置文件
- ✅ 启动所有容器
- ✅ 初始化数据库
- ✅ 显示访问地址

**等待约 1-2 分钟，看到 "🎉 部署完成！" 就成功了！**

---

### 4.2 手动部署（如果脚本失败）

#### 步骤 1：复制配置文件

```powershell
cd D:\lewan\ai-side-laravel

# 复制 .env.example 为 .env
Copy-Item .env.example .env
```

#### 步骤 2：启动 Docker 容器

```powershell
# 启动所有容器（后台运行）
docker-compose up -d

# 查看启动状态
docker-compose ps
```

**预期输出：**
```
NAME                STATUS              PORTS
ai_side_php         Up                  0.0.0.0:8080->80/tcp
ai_side_mysql       Up                  0.0.0.0:3306->3306/tcp
ai_side_redis       Up                  0.0.0.0:6379->6379/tcp
ai_side_nginx       Up                  0.0.0.0:8081->80/tcp
ai_side_worker      Up
ai_side_scheduler   Up
```

#### 步骤 3：等待服务启动

```powershell
# 等待 30 秒（让 MySQL 完全启动）
Start-Sleep -Seconds 30
```

#### 步骤 4：初始化应用

```powershell
# 生成应用密钥
docker-compose exec php php artisan key:generate

# 运行数据库迁移
docker-compose exec php php artisan migrate

# 创建管理员账号
docker-compose exec php php artisan make:filament-user
```

**创建管理员时会提示输入：**
```
Name: 海哥
Email: 你的邮箱（比如 2801359160@qq.com）
Password: 设置密码（至少 8 位）
Password confirmation: 再次输入密码
```

#### 步骤 5：验证部署

```powershell
# 查看容器日志
docker-compose logs -f

# 按 Ctrl+C 退出日志查看
```

---

## 5. 访问网站

### 5.1 访问地址

部署成功后，打开浏览器访问：

| 用途 | 地址 | 说明 |
|------|------|------|
| **前台首页** | http://localhost:8081 | 用户访问的网站 |
| **后台管理** | http://localhost:8081/admin | 管理员后台 |
| **API 接口** | http://localhost:8081/api | API 接口 |

### 5.2 登录后台

1. **打开**：http://localhost:8081/admin
2. **输入邮箱和密码**（刚才创建管理员时设置的）
3. **点击 "Sign in"**

**后台功能：**
- ✅ 用户管理
- ✅ 文章管理
- ✅ 项目管理
- ✅ 订单管理
- ✅ 系统设置

---

## 6. 常见问题

### 6.1 容器启动失败

**问题**：`docker-compose up -d` 报错

**解决方法：**
```powershell
# 1. 检查 Docker 是否运行
docker ps

# 2. 重启 Docker Desktop
# 右键任务栏 Docker 图标 → Quit Docker Desktop
# 重新打开 Docker Desktop

# 3. 清理旧容器
docker-compose down

# 4. 重新启动
docker-compose up -d
```

---

### 6.2 数据库连接失败

**问题**：`php artisan migrate` 报错 "Connection refused"

**解决方法：**
```powershell
# 1. 检查 MySQL 容器是否运行
docker-compose ps mysql

# 2. 查看 MySQL 日志
docker-compose logs mysql

# 3. 重启 MySQL 容器
docker-compose restart mysql

# 4. 等待 10 秒后重试
Start-Sleep -Seconds 10
docker-compose exec php php artisan migrate
```

---

### 6.3 端口被占用

**问题**：`Bind for 0.0.0.0:8081 failed: port is already allocated`

**原因**：8081 端口已被其他程序使用

**解决方法：**

**方法 A**：修改端口
```yaml
# 编辑 docker-compose.yml
# 找到 nginx 部分，修改端口：
ports:
  - "8082:80"  # 改成 8082 或其他端口
```

**方法 B**：关闭占用端口的程序
```powershell
# 查看占用 8081 端口的程序
netstat -ano | findstr :8081

# 记下 PID，然后：
taskkill /PID 12345 /F
```

---

### 6.4 网站访问不了

**问题**：浏览器打开 http://localhost:8081 显示 "无法访问此网站"

**检查步骤：**

```powershell
# 1. 检查容器是否运行
docker-compose ps

# 2. 检查 nginx 日志
docker-compose logs nginx

# 3. 检查 php 日志
docker-compose logs php

# 4. 测试容器内服务
docker-compose exec php php -v
docker-compose exec mysql mysql --version
```

---

### 6.5 权限问题

**问题**：`Permission denied` 错误

**解决方法：**
```powershell
# 进入 PHP 容器
docker-compose exec php bash

# 在容器内执行
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# 退出容器
exit
```

---

## 7. 日常开发

### 7.1 启动/停止项目

```powershell
# 启动所有容器
docker-compose up -d

# 停止所有容器
docker-compose down

# 重启所有容器
docker-compose restart

# 重启单个服务
docker-compose restart php
```

### 7.2 查看日志

```powershell
# 查看所有日志
docker-compose logs -f

# 查看特定服务日志
docker-compose logs -f php
docker-compose logs -f mysql
docker-compose logs -f nginx

# 按 Ctrl+C 退出日志查看
```

### 7.3 进入容器

```powershell
# 进入 PHP 容器
docker-compose exec php bash

# 进入 MySQL 容器
docker-compose exec mysql bash

# 退出容器
exit
```

### 7.4 运行 Laravel 命令

```powershell
# 所有 Laravel 命令都要在容器内执行
docker-compose exec php php artisan 命令名

# 示例：
docker-compose exec php php artisan migrate          # 运行迁移
docker-compose exec php php artisan db:seed          # 运行种子
docker-compose exec php php artisan make:controller  # 创建控制器
docker-compose exec php php artisan make:model       # 创建模型
docker-compose exec php php artisan test             # 运行测试
```

### 7.5 修改代码

**代码位置**：`D:\lewan\ai-side-laravel`

**修改后无需重启容器**，Laravel 会自动检测代码变化。

**但如果修改了以下文件，需要重启：**
- `.env` 文件
- `docker-compose.yml`
- `config/` 目录下的文件

```powershell
# 修改配置后重启
docker-compose restart php
```

### 7.6 数据库管理

```powershell
# 进入 MySQL 命令行
docker-compose exec mysql mysql -u ai_side -paiside123456 ai_side

# 执行 SQL 查询
mysql> SELECT * FROM users;
mysql> SHOW TABLES;

# 退出
mysql> exit;

# 或者导入 SQL 文件
docker-compose exec -T mysql mysql -u ai_side -paiside123456 ai_side < backup.sql
```

### 7.7 备份数据库

```powershell
# 导出数据库
docker-compose exec mysql mysqldump -u ai_side -paiside123456 ai_side > backup.sql

# 导入数据库
docker-compose exec -T mysql mysql -u ai_side -paiside123456 ai_side < backup.sql
```

---

## 📚 学习资源

### Docker 学习
- [Docker 官方文档](https://docs.docker.com/)
- [Docker 从入门到实践](https://vuepress.mirror.docker-practice.com/)

### Laravel 学习
- [Laravel 官方文档](https://laravel.com/docs)
- [Laravel 中文文档](https://learnku.com/docs/laravel)

### Filament 学习
- [Filament 官方文档](https://filamentphp.com/docs)

---

## 🆘 获取帮助

### 遇到问题？

1. **查看日志**：`docker-compose logs -f`
2. **搜索错误信息**：把错误信息复制到 Google/百度
3. **查看 GitHub Issues**：https://github.com/your-username/ai-side-laravel/issues
4. **联系开发者**：发邮件到 2801359160@qq.com

---

## ✅ 部署检查清单

部署完成后，逐项检查：

- [ ] Docker Desktop 已安装并运行
- [ ] 项目代码已下载到 `D:\lewan\ai-side-laravel`
- [ ] `.env` 文件已创建
- [ ] 运行 `docker-compose up -d` 无报错
- [ ] 运行 `docker-compose ps` 显示所有容器状态为 "Up"
- [ ] 运行 `php artisan migrate` 成功
- [ ] 创建了管理员账号
- [ ] 能访问 http://localhost:8081
- [ ] 能访问 http://localhost:8081/admin
- [ ] 能用管理员账号登录后台

**全部打勾就说明部署成功了！** 🎉

---

**祝你开发顺利！** 🚀
