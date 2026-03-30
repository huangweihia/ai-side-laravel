# OpenClaw AI 自动采集定时任务

## 📖 功能说明

使用 OpenClaw 的 AI 能力自动获取内容，并推送到 Laravel 项目保存到数据库。

## ⚙️ 配置步骤

### 1. 在 OpenClaw 中配置定时任务

编辑 OpenClaw 配置，添加定时任务：

```json
{
  "name": "AI 内容自动采集",
  "schedule": {
    "kind": "cron",
    "expr": "0 2 * * *",
    "tz": "Asia/Shanghai"
  },
  "payload": {
    "kind": "agentTurn",
    "message": "请使用 web_search 搜索最新的 AI 相关内容，然后调用 Webhook 推送到 Laravel 项目。\n\n1. 搜索 5 篇最新的 AI 大模型相关文章\n2. 搜索 10 个热门的 GitHub AI 项目\n3. 搜索 10 个 AI 相关招聘职位\n4. 生成 1 篇 AI 技术教程\n\n然后分别调用以下 Webhook：\n- 文章：POST http://localhost:8081/api/openclaw/webhook\n  Body: {\"type\": \"articles\", \"items\": [...]}\n- 项目：POST http://localhost:8081/api/openclaw/webhook\n  Body: {\"type\": \"projects\", \"items\": [...]}\n- 职位：POST http://localhost:8081/api/openclaw/webhook\n  Body: {\"type\": \"jobs\", \"items\": [...]}\n- 知识库：POST http://localhost:8081/api/openclaw/webhook\n  Body: {\"type\": \"knowledge\", \"items\": [...]}\n\nHeader: X-API-Token: ai-fetcher-secret-token"
  },
  "sessionTarget": "isolated",
  "enabled": true
}
```

### 2. 在 Laravel 中配置 Token

编辑 `.env` 文件：

```bash
OPENCLAW_WEBHOOK_TOKEN=ai-fetcher-secret-token
```

### 3. 测试 Webhook

```bash
# 测试文章推送
curl -X POST http://localhost:8081/api/openclaw/webhook \
  -H "Content-Type: application/json" \
  -H "X-API-Token: ai-fetcher-secret-token" \
  -d '{
    "type": "articles",
    "items": [
      {
        "title": "测试文章",
        "summary": "这是一篇测试文章",
        "content": "文章内容...",
        "url": "https://example.com/test"
      }
    ]
  }'
```

## 📊 数据格式

### 文章格式
```json
{
  "type": "articles",
  "items": [
    {
      "title": "文章标题",
      "summary": "摘要",
      "content": "详细内容",
      "url": "原文链接",
      "cover_image": "封面图 URL"
    }
  ]
}
```

### 项目格式
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

### 职位格式（包含原始 URL）
```json
{
  "type": "jobs",
  "items": [
    {
      "title": "职位名称",
      "company_name": "公司名",
      "salary": "薪资",
      "city": "城市",
      "experience": "经验要求",
      "education": "学历要求",
      "description": "职位描述",
      "url": "原始职位链接（用于去重）",
      "tags": ["AI", "Python"]
    }
  ]
}
```

### 知识库格式
```json
{
  "type": "knowledge",
  "items": [
    {
      "title": "文档标题",
      "content": "HTML 格式的文档内容"
    }
  ]
}
```

## 🚀 使用方法

### 手动触发

在 OpenClaw 中执行：
```bash
openclaw agent --message "请搜索最新的 AI 文章并推送到 Webhook"
```

### 自动执行

定时任务会在每日凌晨 2 点自动执行。

## 📝 日志查看

Laravel 日志：
```bash
docker compose exec php tail -f storage/logs/laravel.log | grep "OpenClaw"
```

---

**文档版本：** 1.0  
**最后更新：** 2026-03-28
