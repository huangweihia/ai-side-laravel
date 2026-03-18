# 🚀 AI 副业情报局 - 完整部署指南

> 从 0 到上线的完整流程（Docker 新手适用）

---

## 📋 目录

1. [准备工作](#1-准备工作)
2. [GitHub 仓库创建](#2-github-仓库创建)
3. [项目推送](#3-项目推送)
4. [本地部署](#4-本地部署)
5. [访问测试](#5-访问测试)
6. [下一步开发](#6-下一步开发)

---

## 1. 准备工作

### 1.1 安装软件

**必装软件：**

| 软件 | 用途 | 下载地址 |
|------|------|----------|
| **Docker Desktop** | 容器运行环境 | https://docker.com/products/docker-desktop |
| **Git** | 代码版本管理 | https://git-scm.com/download/win |

**安装顺序：**
1. 先安装 Docker Desktop
2. 再安装 Git
3. 都安装完后重启电脑

### 1.2 验证安装

打开 PowerShell（管理员），执行：

```powershell
docker --version
docker-compose --version
git --version
```

看到版本号就说明安装成功了！✅

---

## 2. GitHub 仓库创建

### 2.1 登录 GitHub

1. 打开：https://github.com
2. 登录账号：`2801359160@qq.com`
3. 密码：`huangweihai520@`

### 2.2 创建新仓库

1. **点击右上角** `+` → `New repository`

2. **填写信息**：
   - **Repository name**: `ai-side-laravel`
   - **Description**: AI 副业情报局 - Laravel 项目
   - **Public** 或 **Private**（建议 Private，代码不公开）
   - ✅ 勾选 **Add a README file**

3. **点击** `Create repository`

4. **复制仓库地址**，比如：
   ```
   https://github.com/2801359160/ai-side-laravel.git
   ```

---

## 3. 项目推送

### 3.1 复制项目到 Windows

项目现在在 WSL 工作空间，需要复制到 Windows：

**在 PowerShell 执行：**

```powershell
# 从 WSL 复制项目
Copy-Item "\\wsl$\OpenClaw\home\node\.openclaw\workspace\ai-side-laravel" "D:\lewan\" -Recurse
```

**或者手动复制：**
1. 打开文件资源管理器
2. 地址栏输入：`\\wsl$\OpenClaw\home\node\.openclaw\workspace\`
3. 复制 `ai-side-laravel` 文件夹到 `D:\lewan\`

### 3.2 初始化 Git 并推送

**在 PowerShell（管理员）执行：**

```powershell
# 1. 进入项目目录
cd D:\lewan\ai-side-laravel

# 2. 运行 Git 初始化脚本
.\init-git.ps1
```

**然后执行：**

```powershell
# 3. 设置用户名和邮箱（首次使用 Git 需要）
git config --global user.name "海哥"
git config --global user.email "2801359160@qq.com"

# 4. 添加远程仓库（替换为你的 GitHub 用户名）
git remote add origin https://github.com/2801359160/ai-side-laravel.git

# 5. 推送到 GitHub
git branch -M main
git push -u origin main
```

**如果推送失败（需要认证）：**
- 输入 GitHub 账号：`2801359160@qq.com`
- 输入密码：`huangweihai520@`
- 或者使用 GitHub Token（推荐）

---

## 4. 本地部署

### 4.1 一键部署

**在 PowerShell（管理员）执行：**

```powershell
cd D:\lewan\ai-side-laravel

# 运行部署脚本
.\deploy-windows.ps1
```

**等待 1-2 分钟，看到 "🎉 部署完成！"**

### 4.2 手动部署（如果脚本失败）

```powershell
# 1. 进入项目目录
cd D:\lewan\ai-side-laravel

# 2. 复制配置文件
Copy-Item .env.example .env

# 3. 启动容器
docker-compose up -d

# 4. 等待启动
Start-Sleep -Seconds 30

# 5. 初始化
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate

# 6. 创建管理员
docker-compose exec php php artisan make:filament-user
```

**按提示输入管理员信息：**
```
Name: 海哥
Email: 2801359160@qq.com
Password: 设置密码（至少 8 位）
```

---

## 5. 访问测试

### 5.1 打开浏览器

| 地址 | 说明 |
|------|------|
| http://localhost:8081 | 前台首页 |
| http://localhost:8081/admin | 后台管理 |

### 5.2 登录后台

1. 访问：http://localhost:8081/admin
2. 输入邮箱和密码
3. 点击 "Sign in"

### 5.3 验证功能

**在后台测试：**
- ✅ 查看用户列表
- ✅ 创建一篇文章
- ✅ 创建一个项目
- ✅ 查看系统设置

---

## 6. 下一步开发

### 6.1 继续开发功能

**推荐顺序：**

1. **用户认证页面** - 注册/登录
2. **首页设计** - 展示项目和文章
3. **GitHub 收集脚本** - 定时任务
4. **邮件推送** - QQ 邮箱 SMTP
5. **会员系统** - 订单 + 支付

### 6.2 常用开发命令

```powershell
# 启动/停止
docker-compose up -d        # 启动
docker-compose down         # 停止

# 查看日志
docker-compose logs -f      # 所有日志
docker-compose logs -f php  # PHP 日志

# 运行 Laravel 命令
docker-compose exec php php artisan make:controller
docker-compose exec php php artisan make:model
docker-compose exec php php artisan test

# 进入容器
docker-compose exec php bash
```

### 6.3 代码修改

**代码位置**：`D:\lewan\ai-side-laravel`

**修改后：**
- 大部分修改立即生效（无需重启）
- 修改 `.env` 或配置文件需要重启：
  ```powershell
  docker-compose restart php
  ```

### 6.4 提交代码到 GitHub

```powershell
# 1. 查看修改
git status

# 2. 添加修改
git add .

# 3. 提交
git commit -m "完成用户认证功能"

# 4. 推送
git push
```

---

## 📚 学习资源

### Docker 入门
- [Docker 官方文档](https://docs.docker.com/get-started/)
- [Docker 从入门到实践（中文）](https://vuepress.mirror.docker-practice.com/)

### Laravel 入门
- [Laravel 官方文档](https://laravel.com/docs)
- [Laravel 中文文档](https://learnku.com/docs/laravel)

### Filament 入门
- [Filament 官方文档](https://filamentphp.com/docs)

---

## 🆘 遇到问题？

### 常见问题

**1. Docker 启动失败**
```powershell
# 重启 Docker Desktop
# 右键任务栏 Docker 图标 → Quit Docker Desktop
# 重新打开 Docker Desktop
```

**2. 端口被占用**
```powershell
# 修改 docker-compose.yml 中的端口
# 比如 8081 改成 8082
```

**3. 数据库连接失败**
```powershell
# 重启 MySQL 容器
docker-compose restart mysql

# 等待 10 秒后重试
Start-Sleep -Seconds 10
```

### 获取帮助

1. **查看日志**：`docker-compose logs -f`
2. **查看文档**：[DEPLOY.md](DEPLOY.md)
3. **搜索错误**：把错误信息复制到 Google
4. **联系开发者**：2801359160@qq.com

---

## ✅ 部署检查清单

完成后逐项检查：

- [ ] Docker Desktop 已安装并运行
- [ ] Git 已安装
- [ ] GitHub 仓库已创建
- [ ] 项目已推送到 GitHub
- [ ] `.env` 文件已创建
- [ ] `docker-compose up -d` 无报错
- [ ] `docker-compose ps` 显示所有容器为 "Up"
- [ ] `php artisan migrate` 成功
- [ ] 管理员账号已创建
- [ ] 能访问 http://localhost:8081
- [ ] 能访问 http://localhost:8081/admin
- [ ] 能登录后台

**全部打勾就成功了！** 🎉

---

**祝你开发顺利！** 🚀
