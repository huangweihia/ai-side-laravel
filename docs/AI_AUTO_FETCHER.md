# AI 自动采集功能 - 完整实现方案

## 📖 方案概述

使用 **OpenClaw Gateway** 调用 AI，实现真实的内容自动采集和生成。

### 核心特点
- ✅ 复用 OpenClaw 的 API Key 和认证
- ✅ 支持定时任务自动执行
- ✅ 支持后台手动触发
- ✅ 广告位移到内容下方，不影响布局

---

## 🏗️ 架构设计

```
┌─────────────────────────────────────────────────┐
│  Laravel 项目                                    │
│                                                 │
│  ┌─────────────────────────────────────────┐   │
│  │ OpenClawGatewayFetcher                  │   │
│  │  - fetchArticles()                      │   │
│  │  - fetchProjects()                      │   │
│  │  - fetchJobs()                          │   │
│  │  - fetchKnowledge()                     │   │
│  └─────────────┬───────────────────────────┘   │
│                │                                │
│                ↓ HTTP 调用                       │
│  ┌─────────────────────────────────────────┐   │
│  │ OpenClaw Gateway                        │   │
│  │ (http://127.0.0.1:18888)               │   │
│  └─────────────┬───────────────────────────┘   │
└────────────────┼────────────────────────────────┘
                 │
                 ↓ 调用 AI
┌────────────────────────────────────────────────┐
│ 阿里云百炼 (qwen3.5-plus)                     │
│ - AI 生成文章                                  │
│ - AI 整理项目                                  │
│ - AI 生成职位                                  │
│ - AI 生成知识库                                │
└────────────────────────────────────────────────┘
```

---

## 📁 文件结构

### 核心服务
- `app/Services/OpenClawGatewayFetcher.php` - AI 采集服务
- `app/Console/Commands/AiFetchDaily.php` - 定时任务命令
- `app/Filament/Pages/AiFetcher.php` - 后台管理页面

### 视图文件
- `resources/views/filament/pages/ai-fetcher.blade.php` - 后台采集界面
- `resources/views/articles/show.blade.php` - 文章详情页（优化布局）
- `resources/views/projects/show.blade.php` - 项目详情页（优化布局）
- `resources/views/layouts/app.blade.php` - 全局布局（优化广告位）

---

## 🚀 使用方法

### 方式 1：后台手动采集

1. **登录后台**
2. **进入"AI 自动采集"页面**（内容管理 → AI 自动采集）
3. **选择采集类型**：
   - 📝 AI 文章
   - 💻 GitHub 项目
   - 💼 AI 职位
   - 📚 知识库文档
4. **输入主题和数量**
5. **点击"开始采集"**

### 方式 2：定时任务自动采集

定时任务已配置在 `app/Console/Kernel.php` 中：

```php
// 每日凌晨 2 点自动获取 AI 内容
$schedule->command('ai:fetch-daily --gateway')
         ->dailyAt('02:00')
         ->timezone('Asia/Shanghai')
         ->withoutOverlapping();
```

**启动调度器：**
```bash
# 开发环境
docker compose exec php php artisan schedule:work

# 生产环境（Docker Cron）
# 配置 cron/laravel-cron 文件
```

### 方式 3：命令行手动执行

```bash
# 使用 OpenClaw Gateway（真实 AI）
docker compose exec php php artisan ai:fetch-daily --gateway

# 使用模拟数据（测试）
docker compose exec php php artisan ai:fetch-daily --mock

# 自定义主题
docker compose exec php php artisan ai:fetch-daily --gateway --topic="GPT-5"
```

---

## ⚙️ 配置说明

### 环境变量

在 `.env` 文件中配置：

```bash
# OpenClaw Gateway 配置
OPENCLAW_GATEWAY_URL=http://127.0.0.1:18888
OPENCLAW_GATEWAY_TOKEN=a58608bbd02545968ef99d8452530584
```

### OpenClaw Gateway Token

从 `/home/node/.openclaw/openclaw.json` 中获取：
```json
{
  "gateway": {
    "auth": {
      "mode": "token",
      "token": "a58608bbd02545968ef99d8452530584"
    }
  }
}
```

---

## 🎨 布局优化

### 广告位优化

**之前：** 广告位在右侧边栏，挤压主内容

**现在：** 广告位移到内容底部，不影响阅读体验

**修改文件：**
- `layouts/app.blade.php` - 移除侧边栏布局
- `articles/show.blade.php` - 单列布局，广告在底部
- `projects/show.blade.php` - 单列布局，广告在底部

### 相关文章优化

**之前：** 在右侧边栏

**现在：** 在内容底部，响应式网格布局

---

## 📊 数据类型

### 1. AI 文章
- 标题、摘要、内容
- 封面图片（AI 生成 URL）
- 来源链接
- 自动发布

### 2. GitHub 项目
- 项目名称、描述
- GitHub 链接
- Stars、Forks 数量
- 编程语言
- 自动分类

### 3. AI 职位
- 职位名称、公司
- 薪资、城市
- 经验、学历要求
- 职位描述
- 技能标签

### 4. 知识库文档
- HTML 格式
- 分章节结构
- 包含实用示例
- 2000 字左右

---

## 🔧 故障排查

### 问题 1：OpenClaw Gateway 不可用

**检查：**
```bash
# 检查 Gateway 是否运行
openclaw gateway status

# 查看 Gateway 日志
openclaw logs
```

**解决：**
```bash
# 重启 Gateway
openclaw gateway restart
```

### 问题 2：AI 返回内容不是 JSON

**原因：** AI 可能返回对话内容而不是结构化数据

**解决：**
- 代码已自动处理，会提取 JSON 部分
- 如果解析失败，会记录日志并跳过

### 问题 3：数据库连接失败

**检查：**
```bash
# 检查数据库是否运行
docker compose ps

# 查看数据库日志
docker compose logs mysql
```

**解决：**
```bash
# 重启数据库
docker compose restart mysql
```

---

## 📈 性能优化

### 1. 调整采集数量

修改 `AiFetchDaily.php`：
```php
$articles = $fetcher->fetchArticles($topic, 5);  // 改为 10
$projects = $fetcher->fetchProjects($topic, 10); // 改为 20
```

### 2. 调整执行时间

修改 `Kernel.php`：
```php
$schedule->command('ai:fetch-daily --gateway')
         ->dailyAt('03:00')  // 改为凌晨 3 点
```

### 3. 并发控制

使用 `->withoutOverlapping()` 防止任务重叠执行。

---

## 🔐 安全注意事项

1. **Gateway Token 保护**
   - 不要将 `.env` 提交到 Git
   - 定期更换 Token

2. **频率限制**
   - 建议每日执行 1-2 次
   - 避免短时间内频繁调用

3. **内容审核**
   - AI 生成的内容建议审核后发布
   - 定期检查数据质量

---

## 💡 最佳实践

### 1. 定时任务 + 手动补充

- **定时任务**：每日凌晨自动采集热门主题
- **手动采集**：根据需要补充特定主题

### 2. 内容审核流程

```
AI 生成 → 待审核状态 → 人工审核 → 发布
```

可以在对应模型中添加 `is_approved` 字段实现。

### 3. 数据去重

代码已使用 `firstOrCreate` 防止重复，但可以优化：
- 基于标题相似度检测
- 基于 URL 去重
- 基于内容哈希去重

---

## 📝 更新日志

- **2026-03-28 v3.0**: 使用 OpenClaw Gateway 调用真实 AI
- **2026-03-28 v2.0**: 优化布局，广告位移到内容底部
- **2026-03-28 v1.0**: 初始版本，后台手动采集页面

---

## 🎯 下一步计划

1. **内容审核流程**：添加审核状态管理
2. **数据去重优化**：基于相似度检测
3. **多源采集**：支持多个 AI 模型
4. **质量评分**：AI 生成内容的质量评估

---

**文档版本：** 3.0  
**最后更新：** 2026-03-28  
**维护者：** AI 副业情报局团队
