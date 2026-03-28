# OpenClaw 定时任务配置 - AI 自动采集

## 🎯 目标

配置 OpenClaw 定时任务，**每日凌晨 2 点自动执行**：
1. 使用 AI 搜索最新的 AI 相关内容
2. 整理成结构化数据
3. 推送到 Laravel 项目保存到数据库

---

## ⚙️ 配置步骤

### 步骤 1：创建 OpenClaw 定时任务

在 OpenClaw 中执行以下命令：

```bash
openclaw cron add --name="AI 内容自动采集" \
  --schedule="0 2 * * *" \
  --timezone="Asia/Shanghai" \
  --message="请使用 web_search 搜索最新的 AI 相关内容，然后推送到 Laravel 项目。

任务要求：
1. 搜索 5 篇最新的 AI 大模型相关文章
2. 搜索 10 个热门的 GitHub AI 项目  
3. 搜索 10 个 AI 相关招聘职位
4. 生成 1 篇 AI 技术教程文档

数据格式要求：
- 文章：{\"title\":\"标题\",\"summary\":\"摘要\",\"content\":\"内容\",\"url\":\"链接\"}
- 项目：{\"name\":\"项目名\",\"description\":\"描述\",\"url\":\"GitHub 链接\",\"stars\":数字,\"forks\":数字}
- 职位：{\"title\":\"职位\",\"company_name\":\"公司\",\"salary\":\"薪资\",\"city\":\"城市\",\"description\":\"描述\"}
- 知识库：{\"title\":\"标题\",\"content\":\"HTML 内容\"}

推送方式：
POST http://localhost:8081/api/openclaw/webhook
Header: X-API-Token: openclaw-ai-fetcher-2026
Content-Type: application/json

Body 格式：
{
  \"type\": \"articles\" 或 \"projects\" 或 \"jobs\" 或 \"knowledge\",
  \"items\": [数据数组]
}

请确保返回的数据是有效的 JSON 格式。" \
  --model="bailian/qwen3.5-plus" \
  --thinking="medium" \
  --session-target="isolated"
```

---

### 步骤 2：验证定时任务

```bash
# 查看已配置的定时任务
openclaw cron list

# 立即执行一次测试
openclaw cron run --name="AI 内容自动采集"
```

---

### 步骤 3：查看执行日志

```bash
# 查看 OpenClaw 日志
openclaw logs

# 查看 Laravel 日志
docker compose exec php tail -f storage/logs/laravel.log | grep -i "openclaw\|webhook"
```

---

## 📊 数据格式示例

### 文章推送
```json
{
  "type": "articles",
  "items": [
    {
      "title": "GPT-5 正式发布：性能提升 10 倍",
      "summary": "OpenAI 今日发布 GPT-5，性能全面提升",
      "content": "OpenAI 今日正式发布 GPT-5，相比 GPT-4 性能提升 10 倍...",
      "url": "https://example.com/gpt5"
    }
  ]
}
```

### 项目推送
```json
{
  "type": "projects",
  "items": [
    {
      "name": "langchain",
      "description": "Building applications with LLMs through composability",
      "url": "https://github.com/langchain-ai/langchain",
      "stars": 75000,
      "forks": 8500,
      "language": "Python"
    }
  ]
}
```

### 职位推送
```json
{
  "type": "jobs",
  "items": [
    {
      "title": "AI 算法工程师",
      "company_name": "某某科技",
      "salary": "30-60K·16 薪",
      "city": "北京",
      "experience": "3-5 年",
      "education": "本科",
      "description": "负责大模型研发和优化...",
      "url": "https://example.com/job"
    }
  ]
}
```

### 知识库推送
```json
{
  "type": "knowledge",
  "items": [
    {
      "title": "ChatGPT 使用技巧",
      "content": "<h1>ChatGPT 使用技巧</h1><p>详细内容...</p>"
    }
  ]
}
```

---

## ✅ 验证自动化

### 1. 检查定时任务状态

```bash
openclaw cron status
```

应该显示：
```
✅ AI 内容自动采集
   Schedule: 0 2 * * * (Asia/Shanghai)
   Status: enabled
   Next run: 2026-03-29 02:00:00
```

### 2. 手动测试一次

```bash
openclaw cron run --name="AI 内容自动采集"
```

### 3. 检查数据库

```bash
docker compose exec php php artisan tinker
>>> App\Models\Article::latest()->first()
>>> App\Models\Project::latest()->first()
```

---

## 🔧 故障排查

### 问题 1：定时任务未执行

**检查：**
```bash
openclaw cron list
```

**解决：**
```bash
openclaw cron update --name="AI 内容自动采集" --enabled=true
```

### 问题 2：Webhook 调用失败

**检查 Laravel 日志：**
```bash
docker compose exec php tail -f storage/logs/laravel.log
```

**可能原因：**
- Token 不匹配
- API 路由不存在
- 数据库未运行

### 问题 3：AI 返回数据格式错误

**解决：**
- 在 OpenClaw message 中强调 JSON 格式要求
- 添加数据验证逻辑
- 使用更强大的模型（qwen3.5-plus）

---

## 📝 总结

### 配置完成后：

✅ **完全自动化**
- 每日凌晨 2 点自动执行
- 无需人工干预

✅ **真实 AI 获取**
- 使用 OpenClaw 的 web_search
- 搜索最新的真实数据

✅ **自动保存**
- 推送到 Laravel API
- 保存到数据库

---

**配置时间：** 约 10 分钟  
**执行频率：** 每日 1 次（凌晨 2:00）  
**数据来源：** OpenClaw AI (web_search)  
**Token:** `openclaw-ai-fetcher-2026`
