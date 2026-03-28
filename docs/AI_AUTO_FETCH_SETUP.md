# OpenClaw AI 自动采集 - 快速开始

## 🚀 已完成的配置

### ✅ Token 已定义
```
openclaw-ai-fetcher-2026
```

### ✅ Laravel API 已创建
- 路由：`POST /api/openclaw/webhook`
- 控制器：`app/Http/Controllers/Api/AiContentController.php`
- Token：已添加到 `.env`

### ✅ OpenClaw 定时任务配置
- 文件：`openclaw-cron-job.json`
- 时间：每日凌晨 2:00
- 模型：qwen3.5-plus

---

## 📋 部署步骤

### 1. 清除 Laravel 配置缓存

```bash
docker compose exec php php artisan config:clear
```

### 2. 导入 OpenClaw 定时任务

在 OpenClaw 中执行：

```bash
# 方式 1：使用 cron 命令导入
openclaw cron add --job-file=openclaw-cron-job.json

# 方式 2：手动复制配置
# 访问 OpenClaw 控制面板，手动创建定时任务
```

### 3. 测试 API

```bash
# 测试文章推送
curl -X POST http://localhost:8081/api/openclaw/webhook \
  -H "Content-Type: application/json" \
  -H "X-API-Token: openclaw-ai-fetcher-2026" \
  -d '{
    "type": "articles",
    "items": [
      {
        "title": "GPT-5 发布",
        "summary": "OpenAI 发布 GPT-5",
        "content": "详细内容...",
        "url": "https://example.com/gpt5"
      }
    ]
  }'

# 预期响应
{"success":true,"message":"成功保存 1 篇文章","saved":1}
```

### 4. 测试 OpenClaw 定时任务

```bash
# 立即执行一次测试
openclaw cron run --job-name="AI 内容自动采集"
```

---

## 📊 数据格式

### 文章
```json
{
  "type": "articles",
  "items": [
    {
      "title": "文章标题",
      "summary": "摘要（200 字）",
      "content": "详细内容（800 字+）",
      "url": "原文链接",
      "cover_image": "封面图 URL（可选）"
    }
  ]
}
```

### 项目
```json
{
  "type": "projects",
  "items": [
    {
      "name": "项目名",
      "description": "描述",
      "url": "GitHub 链接",
      "stars": 1000,
      "forks": 100,
      "language": "Python"
    }
  ]
}
```

### 职位
```json
{
  "type": "jobs",
  "items": [
    {
      "title": "AI 工程师",
      "company_name": "某某科技",
      "salary": "30-60K",
      "city": "北京",
      "experience": "3-5 年",
      "education": "本科",
      "description": "职位描述...",
      "url": "链接",
      "tags": ["AI", "Python", "大模型"]
    }
  ]
}
```

### 知识库
```json
{
  "type": "knowledge",
  "items": [
    {
      "title": "ChatGPT 使用技巧",
      "content": "<h1>HTML 格式的文档内容</h1>..."
    }
  ]
}
```

---

## 🔍 日志查看

### Laravel 日志
```bash
docker compose exec php tail -f storage/logs/laravel.log | grep -i "openclaw\|webhook"
```

### 数据库验证
```bash
docker compose exec php php artisan tinker
>>> App\Models\Article::latest()->first()
>>> App\Models\Project::latest()->first()
```

---

## ⚠️ 注意事项

1. **Token 安全**
   - 不要将 `.env` 提交到 Git
   - Token 已添加到 `.gitignore`

2. **网络可达**
   - 确保 OpenClaw 能访问 Laravel API（localhost:8081）
   - 如果在不同机器，需要配置公网地址

3. **频率控制**
   - 建议每日执行 1-2 次
   - 避免频繁调用导致 API 限制

---

## 🎯 下一步

1. ✅ 清除 Laravel 缓存
2. ⏳ 导入 OpenClaw 定时任务
3. ⏳ 测试 API
4. ⏳ 验证数据是否保存到数据库

---

**文档版本：** 1.0  
**最后更新：** 2026-03-28  
**Token:** `openclaw-ai-fetcher-2026`
