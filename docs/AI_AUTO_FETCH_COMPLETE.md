# ✅ OpenClaw AI 自动采集 - 配置完成

## 🎯 已完成的配置

### ✅ 1. Laravel API 接口
- **文件：** `app/Http/Controllers/Api/AiContentController.php`
- **路由：** `POST /api/openclaw/webhook`
- **Token：** `openclaw-ai-fetcher-2026`
- **状态：** ✅ 测试通过（成功保存文章）

### ✅ 2. 后台管理工具
- **文件：** `app/Filament/Pages/AiAutoFetcher.php`
- **位置：** 后台 → 内容管理 → AI 自动采集
- **功能：** 手动采集文章/项目/职位/知识库
- **状态：** ✅ 已完成

### ✅ 3. 定时任务配置文件
- **文件：** `ai-content-cron-optimized.json`
- **Schedule：** 每日凌晨 2:00
- **模型：** qwen3.5-plus
- **状态：** ⏳ 待导入

---

## 🚀 立即配置定时任务（2 种方式）

### 方式 1：在 OpenClaw 控制面板手动配置（推荐）

#### 第 1 步：打开控制面板
访问：http://localhost:18888（或你的 OpenClaw 地址）

#### 第 2 步：进入 Cron 页面
点击左侧菜单的 **Cron**

#### 第 3 步：新建任务
点击 **新建任务** 按钮

#### 第 4 步：填写配置

**基本信息：**
```
名称：AI 内容自动采集
描述：每日自动采集 AI 文章、项目、职位和知识库
代理 ID: main
已启用：✅ 勾选
```

**调度配置：**
```
Schedule Type: Cron
Cron 表达式：0 2 * * *
时区：Asia/Shanghai
```

**消息内容：**
```
你是一个专业的 AI 内容采集助手。请使用 web_search 搜索最新的 AI 相关内容，整理后推送到 Laravel 项目。

任务要求：
1. 搜索 5 篇最新的 AI 大模型相关文章
2. 搜索 10 个热门的 GitHub AI 项目
3. 搜索 10 个 AI 相关招聘职位
4. 生成 1 篇 AI 技术教程文档

数据格式：
- 文章：{"type":"articles","items":[{"title":"标题","summary":"摘要","content":"内容","url":"链接"}]}
- 项目：{"type":"projects","items":[{"name":"项目名","description":"描述","url":"链接","stars":数字,"forks":数字}]}
- 职位：{"type":"jobs","items":[{"title":"职位","company_name":"公司","salary":"薪资","city":"城市","description":"描述"}]}
- 知识库：{"type":"knowledge","items":[{"title":"标题","content":"HTML 内容"}]}

推送方式：
POST http://host.docker.internal:8081/api/openclaw/webhook
Headers: Content-Type: application/json, X-API-Token: openclaw-ai-fetcher-2026

请确保返回有效的 JSON 格式，每个类型单独推送。
```

**模型配置：**
```
Model: bailian/qwen3.5-plus
Thinking: medium
Session Target: isolated
```

#### 第 5 步：保存并测试
点击 **保存**，然后点击 **运行** 测试一次

---

### 方式 2：使用脚本导入（需要 Gateway 运行）

```bash
# 1. 启动 Gateway
openclaw gateway start

# 2. 导入定时任务
bash /home/node/.openclaw/workspace/import-cron-job.sh

# 3. 验证
openclaw cron list
```

---

## 📊 配置完成后的效果

### 自动化流程
```
每日凌晨 2:00
    ↓
OpenClaw 定时任务触发
    ↓
使用 web_search 搜索真实数据
    ↓
AI 整理成结构化 JSON
    ↓
POST 到 /api/openclaw/webhook
    ↓
Laravel 保存到数据库
    ↓
✅ 自动完成！
```

### 数据类型
| 类型 | 数量 | 保存位置 |
|------|------|---------|
| 📝 文章 | 5 篇 | 文章管理 |
| 💻 项目 | 10 个 | 项目管理 |
| 💼 职位 | 10 个 | 职位管理 |
| 📚 知识库 | 1 篇 | 知识库管理 |

---

## 🧪 测试方法

### 立即测试一次
在 OpenClaw 控制面板的任务列表中，点击 **运行** 按钮

### 查看结果
```bash
# 1. 查看 Laravel 日志
docker compose exec php tail -f storage/logs/laravel.log | grep -i webhook

# 2. 查看数据库
docker compose exec php php artisan tinker
>>> App\Models\Article::latest()->first()
>>> App\Models\Project::latest()->first()

# 3. 访问后台
访问：http://localhost:8081/admin
查看：内容管理 → 文章管理/项目管理
```

---

## ⚠️ 常见问题

### Q1: Gateway 无法启动
**解决：**
```bash
openclaw gateway restart
```

### Q2: Webhook 调用失败
**检查：**
1. Token 是否正确：`openclaw-ai-fetcher-2026`
2. API 路由是否存在：`POST /api/openclaw/webhook`
3. 数据库是否运行

### Q3: 数据没有保存
**检查 Laravel 日志：**
```bash
docker compose exec php tail -f storage/logs/laravel.log
```

---

## 📋 配置清单

- [x] Laravel API 接口 ✅
- [x] API 路由配置 ✅
- [x] Token 配置 ✅
- [x] 后台管理工具 ✅
- [x] 定时任务配置文件 ✅
- [ ] 导入定时任务 ⏳ **需要手动执行**
- [ ] 测试定时任务 ⏳ **需要手动执行**

---

## 🎯 下一步

1. **在 OpenClaw 控制面板配置定时任务**（5 分钟）
2. **测试一次**（2-5 分钟）
3. **验证数据是否保存**（1 分钟）

---

**Token:** `openclaw-ai-fetcher-2026`  
**API:** `POST http://localhost:8081/api/openclaw/webhook`  
**Schedule:** `0 2 * * *` (每日凌晨 2:00)  
**模型:** `bailian/qwen3.5-plus`

---

**文档版本：** 1.0  
**最后更新：** 2026-03-28  
**状态：** 配置完成，待导入定时任务
