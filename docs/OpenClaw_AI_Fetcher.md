# OpenClaw AI 自动获取功能文档

## 📖 功能说明

本功能使用 **OpenClaw CLI** 调用阿里云百炼 AI，自动获取 AI 相关内容，包括：
- 📝 AI 相关文章
- 💻 GitHub AI 项目
- 💼 AI 职位
- 📚 知识库文档

**核心优势：**
- ✅ 使用 OpenClaw 已验证的 API Key
- ✅ 无需爬虫代码
- ✅ 内容经过 AI 整理，质量更高
- ✅ 合法合规

---

## 🚀 使用方法

### 方式 1：手动执行（测试用）

在项目中执行：

```bash
# 进入项目目录
cd D:\lewan\openclaw-data\workspace\ai-side-laravel

# 执行 AI 获取命令（使用 OpenClaw 真实 AI）
docker compose exec php php artisan ai:fetch-daily

# 或者自定义主题
docker compose exec php php artisan ai:fetch-daily --topic="GPT-5 最新动态"

# 使用模拟数据（开发测试）
docker compose exec php php artisan ai:fetch-daily --mock
```

### 方式 2：自动执行（生产环境）

定时任务已配置在 `app/Console/Kernel.php` 中：
- **执行时间：** 每日凌晨 2:00
- **时区：** Asia/Shanghai

需要启动 Laravel 调度器：

```bash
# 方式 A：手动启动调度器（开发环境）
docker compose exec php php artisan schedule:work

# 方式 B：配置 Docker Cron（生产环境）
# 见下方部署指南
```

---

## 📁 文件说明

### 核心文件

| 文件 | 说明 |
|------|------|
| `app/Services/OpenClawFetcher.php` | OpenClaw AI 获取服务（使用 OpenClaw CLI） |
| `app/Console/Commands/AiFetchDaily.php` | 定时命令 |
| `app/Console/Kernel.php` | 定时任务配置 |
| `app/Services/MockAiFetcher.php` | 模拟数据服务（`--mock` 模式） |

### 数据模型

| 模型 | 说明 |
|------|------|
| `Article` | AI 文章 |
| `Project` | GitHub 项目 |
| `JobListing` | AI 职位 |
| `KnowledgeDocument` | 知识库文档 |
| `KnowledgeBase` | 知识库分类 |

---

## ⚙️ 配置说明

### 环境变量

**无需额外配置！** 使用 OpenClaw 已有的阿里云百炼 API Key。

### OpenClaw 调用方式

`OpenClawFetcher.php` 使用 OpenClaw CLI 调用 AI：

```bash
openclaw agent --session-id ai-fetch-xxx --message "搜索任务" --json
```

这样：
- ✅ 复用 OpenClaw 的 API Key 配置
- ✅ 使用相同的 AI 模型（qwen3.5-plus）
- ✅ 无需单独配置

---

## 🎯 自定义主题

### 修改默认主题

编辑 `app/Console/Commands/AiFetchDaily.php`：

```php
// 文章主题
$articleTopic = 'AI 大模型 最新动态 GPT-5';

// 项目主题
$projectTopic = 'machine-learning artificial-intelligence gpt llm';

// 职位主题
$jobTopic = 'AI 工程师 大模型 AIGC 算法工程师';

// 知识库主题
$knowledgeTopic = 'ChatGPT 使用技巧';
```

### 使用命令行参数

```bash
# 使用自定义主题
php artisan ai:fetch-daily --topic="Stable Diffusion 教程"
```

---

## 📊 日志查看

### 实时日志

```bash
docker compose exec php tail -f storage/logs/laravel.log
```

### 搜索相关日志

```bash
docker compose exec php grep "OpenClawFetcher" storage/logs/laravel.log
```

---

## 🔧 部署指南

### 开发环境（本地）

1. **启动调度器**
   ```bash
   docker compose exec php php artisan schedule:work
   ```

2. **测试命令**
   ```bash
   docker compose exec php php artisan ai:fetch-daily
   ```

### 生产环境（服务器）

#### 方案 A：Docker Cron（推荐）

1. **修改 docker-compose.yml**
   ```yaml
   services:
     php:
       volumes:
         - ./cron/laravel-cron:/etc/cron.d/laravel-cron:ro
   ```

2. **创建 Cron 配置文件**
   ```bash
   # cron/laravel-cron
   * * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1
   ```

3. **重启容器**
   ```bash
   docker compose restart php
   ```

#### 方案 B：系统 Cron

1. **编辑 Crontab**
   ```bash
   crontab -e
   ```

2. **添加任务**
   ```bash
   0 2 * * * cd /path/to/ai-side-laravel && docker compose exec -T php php artisan ai:fetch-daily >> storage/logs/ai-fetch.log 2>&1
   ```

---

## 🐛 故障排查

### 问题 1：OpenClaw CLI 不可用

**现象：** 日志显示 "调用 OpenClaw 失败"

**解决：**
1. 检查 OpenClaw 是否安装
2. 检查 `openclaw` 命令是否在 PATH 中
3. 确保 OpenClaw 配置正常（能正常对话）

### 问题 2：数据未保存

**现象：** 命令执行成功但数据库无数据

**解决：**
1. 检查数据库连接
2. 查看日志中的错误信息
3. 确认模型字段映射正确

### 问题 3：返回内容不是 JSON

**现象：** AI 返回的内容无法解析

**解决：**
- 这是正常现象，代码会自动处理
- 检查 Prompt 是否足够清晰
- 可以尝试调整 Prompt 格式

---

## 📈 性能优化

### 调整获取数量

修改 `AiFetchDaily.php` 中的数量限制：

```php
$articles = $fetcher->fetchArticles($topic, 5);  // 改为 10
$projects = $fetcher->fetchProjects($topic, 10); // 改为 20
```

### 调整执行时间

修改 `Kernel.php` 中的执行时间：

```php
$schedule->command('ai:fetch-daily')
         ->dailyAt('03:00')  // 改为凌晨 3 点
```

---

## 🔐 安全注意事项

1. **OpenClaw 配置**
   - 确保 OpenClaw 的 API Key 配置正确
   - 定期检查 API 使用情况

2. **频率限制**
   - 避免频繁调用（建议每日 1-2 次）
   - 注意 OpenClaw 的速率限制

3. **数据审核**
   - AI 生成的内容建议审核后发布
   - 定期检查数据质量

---

## 📝 更新日志

- **2026-03-28 v2.0**: 改用 OpenClaw CLI 调用真实 AI
- **2026-03-28 v1.0**: 初始版本，使用模拟数据

---

## 💡 常见问题

### Q: 可以只获取某一类数据吗？

A: 可以！修改 `AiFetchDaily.php` 注释掉不需要的部分即可。

### Q: 为什么使用 OpenClaw CLI 而不是直接调用 API？

A: 
- ✅ 复用 OpenClaw 的 API Key 配置
- ✅ 使用相同的 AI 模型
- ✅ 无需额外配置
- ✅ 更稳定可靠

### Q: 如何查看获取的数据？

A: 
- 文章：后台 → 内容管理 → 文章管理
- 项目：前台 → 项目库
- 职位：后台 → 内容管理 → 职位管理
- 知识库：后台 → 内容管理 → 知识库管理

### Q: 模拟数据和真实 AI 有什么区别？

A: 
- **模拟数据** (`--mock`): 硬编码的假数据，用于开发测试
- **真实 AI**: 使用 OpenClaw 调用阿里云百炼，生成真实内容

---

**文档版本：** 2.0  
**最后更新：** 2026-03-28
