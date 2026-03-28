# 📊 AI 副业情报局 - 数据库表结构文档

> 本文档维护项目所有数据库表的结构、字段说明和版本变更记录  
> 最后更新：2026-03-28  
> 数据库：`ai_side_laravel`

---

## 📑 目录

- [核心表](#核心表)
- [内容表](#内容表)
- [用户互动表](#用户互动表)
- [用户主页留言与 VIP 紧急通知](#用户主页留言与-vip-紧急通知)
- [系统通知表](#系统通知表)
- [邮件系统表](#邮件系统表)
- [知识库表](#知识库表)
- [职位表](#职位表)
- [系统表](#系统表)
- [版本变更历史](#版本变更历史)

---

## 核心表

### users - 用户表

**描述：** 存储用户基本信息、会员状态和登录记录

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| name | VARCHAR(255) | ✅ | - | 用户昵称 |
| email | VARCHAR(255) | ✅ | - | 邮箱（唯一） |
| email_verified_at | TIMESTAMP | ❌ | NULL | 邮箱验证时间 |
| password | VARCHAR(255) | ✅ | - | 密码（加密） |
| avatar | VARCHAR(255) | ❌ | NULL | 头像 URL |
| role | ENUM | ✅ | 'user' | 角色：user/vip/admin |
| subscription_ends_at | TIMESTAMP | ❌ | NULL | 会员到期时间 |
| last_login_at | TIMESTAMP | ❌ | NULL | 最后登录时间 |
| last_login_ip | VARCHAR(45) | ❌ | NULL | 最后登录 IP |
| remember_token | VARCHAR(100) | ❌ | NULL | 记住我令牌 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `email` - 邮箱索引
- `role` - 角色索引

**变更记录：**
- v1.0.0 (2024-01-01) - 初始创建

---

### subscriptions - 会员订阅表

**描述：** 记录用户的 VIP 会员订阅信息

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| user_id | BIGINT | ✅ | - | 用户 ID（外键） |
| plan | ENUM | ✅ | - | 套餐：monthly/yearly/lifetime |
| amount | DECIMAL(10,2) | ✅ | - | 金额 |
| status | ENUM | ✅ | 'pending' | 状态：pending/active/expired/cancelled |
| started_at | TIMESTAMP | ❌ | NULL | 开始时间 |
| expires_at | TIMESTAMP | ❌ | NULL | 到期时间 |
| payment_id | VARCHAR(100) | ❌ | NULL | 支付订单号 |
| payment_method | VARCHAR(50) | ❌ | NULL | 支付方式 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `user_id` - 用户索引
- `status` - 状态索引

---

### orders - 订单表

**描述：** 记录所有支付订单

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| order_no | VARCHAR(100) | ✅ | - | 订单号（唯一） |
| user_id | BIGINT | ✅ | - | 用户 ID（外键） |
| product_type | ENUM | ✅ | - | 产品类型：subscription/service |
| product_id | BIGINT | ❌ | NULL | 产品 ID |
| amount | DECIMAL(10,2) | ✅ | - | 订单金额 |
| status | ENUM | ✅ | 'pending' | 状态：pending/paid/cancelled/refunded |
| payment_method | VARCHAR(50) | ❌ | NULL | 支付方式 |
| payment_time | TIMESTAMP | ❌ | NULL | 支付时间 |
| paid_amount | DECIMAL(10,2) | ❌ | NULL | 实付金额 |
| remark | TEXT | ❌ | NULL | 备注 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `order_no` - 订单号索引
- `user_id` - 用户索引
- `status` - 状态索引

---

## 内容表

### categories - 分类表

**描述：** 文章和项目的分类管理

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| name | VARCHAR(255) | ✅ | - | 分类名称 |
| slug | VARCHAR(255) | ✅ | - | 别名（唯一） |
| description | TEXT | ❌ | NULL | 分类描述 |
| sort | INT | ✅ | 0 | 排序值 |
| is_premium | BOOLEAN | ✅ | false | 是否付费分类 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

---

### articles - 文章表

**描述：** 存储文章内容、统计和 SEO 信息

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| category_id | BIGINT | ❌ | NULL | 分类 ID（外键） |
| title | VARCHAR(255) | ✅ | - | 标题 |
| slug | VARCHAR(255) | ✅ | - | 别名（唯一） |
| summary | VARCHAR(500) | ❌ | NULL | 摘要 |
| content | LONGTEXT | ❌ | NULL | 正文内容 |
| cover_image | VARCHAR(255) | ❌ | NULL | 封面图 |
| author_id | BIGINT | ❌ | NULL | 作者 ID（外键） |
| view_count | BIGINT | ✅ | 0 | 浏览次数 |
| like_count | BIGINT | ✅ | 0 | 点赞数 |
| is_premium | BOOLEAN | ✅ | false | 是否付费文章 |
| is_published | BOOLEAN | ✅ | false | 是否已发布 |
| published_at | TIMESTAMP | ❌ | NULL | 发布时间 |
| source_url | VARCHAR(255) | ❌ | NULL | 来源链接 |
| meta_keywords | VARCHAR(255) | ❌ | NULL | SEO 关键词 |
| meta_description | VARCHAR(500) | ❌ | NULL | SEO 描述 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `slug` - 别名索引
- `category_id` - 分类索引
- `is_published` - 发布状态索引
- `is_premium` - 付费状态索引

**变更记录：**
- v1.0.0 - 初始创建
- v1.2.0 - 添加 `source_url` 唯一索引

---

### projects - 项目表

**描述：** 存储开源项目和副业项目信息

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| name | VARCHAR(255) | ✅ | - | 项目名称 |
| full_name | VARCHAR(150) | ❌ | NULL | 完整名称（含作者） |
| description | TEXT | ❌ | NULL | 项目描述 |
| url | VARCHAR(255) | ✅ | - | 项目链接（唯一） |
| language | VARCHAR(50) | ❌ | NULL | 编程语言 |
| stars | INT | ✅ | 0 | Star 数量 |
| forks | INT | ✅ | 0 | Fork 数量 |
| score | DECIMAL(5,2) | ✅ | 0 | 推荐分数 |
| tags | JSON | ❌ | NULL | 标签数组 |
| monetization | TEXT | ❌ | NULL | 变现方式 |
| difficulty | ENUM | ✅ | 'medium' | 难度：easy/medium/hard |
| is_featured | BOOLEAN | ✅ | false | 是否推荐 |
| is_vip | BOOLEAN | ✅ | false | 是否 VIP 专属（详情与全文需会员权限） |
| collected_at | TIMESTAMP | ✅ | CURRENT | 收录时间 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `url` - 链接索引
- `stars` - Star 数索引
- `score` - 分数索引
- `is_featured` - 推荐状态索引
- `is_vip` - VIP 筛选索引

**变更记录：**
- v1.0.0 - 初始创建
- v1.1.0 - 添加 `revenue` 相关字段（已废弃）
- v1.4.2 (2026-03-28) - 添加 `is_vip`（迁移 `2026_03_28_000001_add_is_vip_to_projects_table`）

---

## 用户互动表

### favorites - 收藏表

**描述：** 记录用户收藏的项目和文章（多态关联）

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| user_id | BIGINT | ✅ | - | 用户 ID（外键） |
| favoritable_type | VARCHAR(255) | ✅ | - | 收藏类型（模型类名） |
| favoritable_id | BIGINT | ✅ | - | 收藏对象 ID |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `user_id` - 用户索引
- `favoritable_type + favoritable_id` - 多态索引

---

### comments - 评论表

**描述：** 支持文章、项目、知识库等内容的评论和回复（多态关联）

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| user_id | BIGINT | ✅ | - | 用户 ID（外键） |
| commentable_type | VARCHAR(255) | ✅ | - | 评论对象类型 |
| commentable_id | BIGINT | ✅ | - | 评论对象 ID |
| parent_id | BIGINT | ❌ | NULL | 父评论 ID（回复） |
| reply_to_id | BIGINT | ❌ | NULL | 回复的目标评论 ID |
| content | TEXT | ✅ | - | 评论内容 |
| like_count | INT | ✅ | 0 | 点赞数 |
| is_hidden | BOOLEAN | ✅ | false | 是否隐藏 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `user_id` - 用户索引
- `commentable_type + commentable_id` - 多态索引
- `parent_id` - 父评论索引
- `is_hidden` - 隐藏状态索引

**变更记录：**
- v1.0.0 - 初始创建
- v1.3.0 - 添加 `reply_to_id` 支持引用回复
- v1.3.0 - 添加 `comment_likes` 表

---

### comment_likes - 评论点赞表

**描述：** 记录用户对评论的点赞

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| user_id | BIGINT | ✅ | - | 用户 ID（外键） |
| comment_id | BIGINT | ✅ | - | 评论 ID（外键） |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |

**索引：**
- `user_id + comment_id` - 联合唯一索引（防止重复点赞）

**变更记录：**
- v1.3.0 (2026-03-27) - 新增

---

### view_histories - 浏览历史表

**描述：** 记录用户浏览过的内容（多态关联）

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| user_id | BIGINT | ✅ | - | 用户 ID（外键） |
| viewable_type | VARCHAR(255) | ✅ | - | 浏览对象类型 |
| viewable_id | BIGINT | ✅ | - | 浏览对象 ID |
| viewed_at | TIMESTAMP | ✅ | CURRENT | 浏览时间 |

**索引：**
- `user_id` - 用户索引
- `viewable_type + viewable_id` - 多态索引

---

### user_actions - 用户行为表

**描述：** 统一记录用户的各种行为（点赞、收藏等）

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| user_id | BIGINT | ✅ | - | 用户 ID |
| type | VARCHAR(50) | ✅ | - | 行为类型：like/favorite |
| actionable_type | VARCHAR(255) | ✅ | - | 行为对象类型 |
| actionable_id | BIGINT | ✅ | - | 行为对象 ID |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |

**索引：**
- `user_id + type + actionable_type + actionable_id` - 联合唯一索引

---

## 用户主页留言与 VIP 紧急通知

### profile_messages - 用户主页留言表

**描述：** 登录用户在其他用户公开主页上发送的留言（非多态评论）；主页主人仅在自己查看本人主页时于后台列表中可见。

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| recipient_id | BIGINT | ✅ | - | 主页主人用户 ID（外键 `users`，级联删除） |
| sender_id | BIGINT | ✅ | - | 留言者用户 ID（外键 `users`，级联删除） |
| body | TEXT | ✅ | - | 留言正文 |
| read_at | TIMESTAMP | ❌ | NULL | 已读时间（预留） |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `recipient_id + created_at` - 主页收件列表排序
- `sender_id` - 留言者查询

**变更记录：**
- v1.4.3 (2026-03-28) - 新增（迁移 `2026_03_28_100000_create_profile_messages_and_urgent_logs_tables`）

---

### vip_urgent_notification_logs - VIP 紧急通知发送日志表

**描述：** 记录主页主人（VIP）通过「紧急通知」向留言者发送邮件的行为，用于 **每位 VIP 每天仅允许 1 次** 等业务校验（按 `sender_user_id` + `sent_at` 日期判断）。

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| sender_user_id | BIGINT | ✅ | - | 发起邮件的 VIP（主页主人）用户 ID（外键 `users`，级联删除） |
| recipient_user_id | BIGINT | ✅ | - | 收件人（留言者）用户 ID（外键 `users`，级联删除） |
| profile_message_id | BIGINT | ✅ | - | 关联的留言 ID（外键 `profile_messages`，级联删除） |
| sent_at | TIMESTAMP | ✅ | - | 发送时间（业务上用于「当日是否已发送」） |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `sender_user_id + sent_at` - 按发送者与时间查询（日限）

**变更记录：**
- v1.4.3 (2026-03-28) - 新增（迁移 `2026_03_28_100000_create_profile_messages_and_urgent_logs_tables`）

---

## 系统通知表

### system_notifications - 站内系统通知

**描述：** 向用户推送互动与运营消息；`is_from_admin = true` 为后台发送，前台列表 **置顶** 且 **样式区分**。

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| user_id | BIGINT | ✅ | - | 接收用户（外键 `users`） |
| type | VARCHAR(50) | ✅ | - | `article_liked` / `article_favorited` / `admin_notice` 等 |
| title | VARCHAR(255) | ✅ | - | 标题 |
| body | TEXT | ❌ | NULL | 正文 |
| meta | JSON | ❌ | NULL | 扩展（如 `article_id`、`actor_id`） |
| is_from_admin | BOOLEAN | ✅ | false | 是否后台官方通知（置顶排序） |
| read_at | TIMESTAMP | ❌ | NULL | 已读时间 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `user_id + is_from_admin + created_at`
- `user_id + read_at`

**变更记录：**
- v1.5.0 (2026-03-28) - 新增（迁移 `2026_03_28_140000_create_system_notifications_table`）

---

## 邮件系统表

### email_settings - 邮件设置表

**描述：** 存储邮件发送的全局配置

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| mailer_name | VARCHAR(100) | ✅ | - | 邮件器名称 |
| host | VARCHAR(255) | ✅ | - | SMTP 主机 |
| port | INT | ✅ | 587 | SMTP 端口 |
| username | VARCHAR(255) | ✅ | - | SMTP 用户名 |
| password | VARCHAR(255) | ✅ | - | SMTP 密码 |
| encryption | VARCHAR(50) | ❌ | NULL | 加密方式：tls/ssl |
| from_address | VARCHAR(255) | ✅ | - | 发件人邮箱 |
| from_name | VARCHAR(255) | ❌ | NULL | 发件人名称 |
| is_default | BOOLEAN | ✅ | false | 是否默认 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

---

### email_templates - 邮件模板表

**描述：** 存储邮件模板内容；代码中通过 **`key`** 唯一标识调用（如 `EmailNotificationService::sendFromTemplateByKey`）。

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| name | VARCHAR(255) | ✅ | - | 模板名称（展示用） |
| key | VARCHAR(255) | ✅ | - | 模板业务键（**唯一**，与代码常量一致） |
| subject | VARCHAR(255) | ✅ | - | 邮件主题（支持 `{{变量}}` 占位） |
| content | LONGTEXT | ✅ | - | HTML/文本正文（`{{变量}}` 占位替换） |
| variables | JSON | ❌ | NULL | 可用变量名列表（数组，便于后台展示） |
| is_active | BOOLEAN | ✅ | true | 是否启用 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `key` - 唯一

**迁移种子数据（基础数据，与迁移文件同步）：**

| key | 说明 | 迁移文件 |
|-----|------|----------|
| `profile_message_urgent` | 主页留言 · VIP 紧急通知（发给留言者） | 迁移 `2026_03_28_100001_add_profile_message_urgent_email_template.php`；种子 `RestoreAllDataSeeder`、`EmailTemplatePresetSeeder` 同步同一 `key` |
| `vip_expiry_reminder` | VIP 到期提醒（后台对用户手动发送） | 迁移 `2026_03_28_120000_add_vip_expiry_reminder_email_template.php`；种子 `RestoreAllDataSeeder`、`EmailTemplatePresetSeeder` 同步同一 `key` |

**`profile_message_urgent` 占位变量：**

| 变量名 | 含义 |
|--------|------|
| `recipient_name` | 收件人昵称 |
| `profile_owner_name` | 主页主人昵称 |
| `message_excerpt` | 留言摘要 |
| `urgent_note` | 紧急附言或默认说明（由控制器传入） |
| `profile_url` | 对方主页完整 URL |

**`vip_expiry_reminder` 占位变量：**

| 变量名 | 含义 |
|--------|------|
| `recipient_name` | 收件人昵称 |
| `expiry_date` | VIP 到期时间（格式化字符串） |
| `days_remaining` | 剩余天数（字符串，与 `users.subscription_ends_at` 计算一致） |
| `vip_url` | 续费/VIP 页（服务层默认合并） |
| `dashboard_url` | 个人中心（服务层默认合并） |

**变更记录：**
- v1.4.3 (2026-03-28) - 文档勘误：字段为 `key` 非 `slug`；`content` 为 LONGTEXT；补充迁移写入的 `profile_message_urgent` 模板说明
- v1.4.4 (2026-03-28) - 补充迁移种子 `vip_expiry_reminder`（后台 VIP 到期提醒邮件）

---

### email_logs - 邮件发送日志表

**描述：** 记录所有邮件发送历史

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| template_id | BIGINT | ❌ | NULL | 模板 ID（外键） |
| recipient | VARCHAR(255) | ✅ | - | 收件人邮箱 |
| subject | VARCHAR(255) | ✅ | - | 邮件主题 |
| content | TEXT | ❌ | NULL | 邮件内容 |
| status | ENUM | ✅ | 'pending' | 状态：pending/sent/failed |
| error_message | TEXT | ❌ | NULL | 错误信息 |
| sent_at | TIMESTAMP | ❌ | NULL | 发送时间 |
| opened_at | TIMESTAMP | ❌ | NULL | 打开时间 |
| clicked_at | TIMESTAMP | ❌ | NULL | 点击时间 |
| metadata | JSON | ❌ | NULL | 元数据 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `recipient` - 收件人索引
- `status` - 状态索引
- `sent_at` - 发送时间索引

---

## 知识库表

### knowledge_bases - 知识库表

**描述：** 知识库分类和集合

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| name | VARCHAR(255) | ✅ | - | 知识库名称 |
| slug | VARCHAR(255) | ✅ | - | 别名（唯一） |
| description | TEXT | ❌ | NULL | 描述 |
| icon | VARCHAR(50) | ❌ | NULL | 图标 emoji |
| color | VARCHAR(20) | ❌ | NULL | 主题色 |
| is_public | BOOLEAN | ✅ | true | 是否公开 |
| is_vip_only | BOOLEAN | ✅ | false | 是否仅 VIP |
| sort | INT | ✅ | 0 | 排序值 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

---

### knowledge_documents - 知识库文档表

**描述：** 知识库中的具体文档内容

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| knowledge_base_id | BIGINT | ✅ | - | 知识库 ID（外键） |
| title | VARCHAR(255) | ✅ | - | 文档标题 |
| slug | VARCHAR(255) | ✅ | - | 别名（唯一） |
| content | LONGTEXT | ✅ | - | 文档内容 |
| summary | VARCHAR(500) | ❌ | NULL | 摘要 |
| tags | JSON | ❌ | NULL | 标签数组 |
| view_count | BIGINT | ✅ | 0 | 浏览次数 |
| is_published | BOOLEAN | ✅ | false | 是否发布 |
| published_at | TIMESTAMP | ❌ | NULL | 发布时间 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `knowledge_base_id` - 知识库索引
- `slug` - 别名索引
- `is_published` - 发布状态索引

---

## 职位表

### positions - 职位表

**描述：** 存储招聘信息和联系方式

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| user_id | BIGINT | ✅ | - | 发布者 ID（外键） |
| title | VARCHAR(255) | ✅ | - | 职位名称 |
| company_name | VARCHAR(255) | ✅ | - | 公司名称 |
| location | VARCHAR(255) | ❌ | NULL | 工作地点 |
| salary_range | VARCHAR(100) | ❌ | NULL | 薪资范围 |
| requirements | TEXT | ❌ | NULL | 任职要求 |
| description | TEXT | ❌ | NULL | 职位描述 |
| contact_email | VARCHAR(255) | ❌ | NULL | 联系邮箱 |
| contact_phone | VARCHAR(50) | ❌ | NULL | 联系电话 |
| contact_wechat | VARCHAR(100) | ❌ | NULL | 联系微信 |
| is_contact_vip | BOOLEAN | ✅ | false | 联系方式是否 VIP 可见 |
| is_published | BOOLEAN | ✅ | false | 是否已发布 |
| view_count | INT | ✅ | 0 | 浏览次数 |
| apply_count | INT | ✅ | 0 | 申请次数 |
| published_at | TIMESTAMP | ❌ | NULL | 发布时间 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `is_published + created_at` - 发布状态和时间索引
- `location` - 地点索引

**变更记录：**
- v1.4.0 (2026-03-27) - 新增（使用 `positions` 表名避免与队列冲突）

---

## 投稿表

### content_submissions - 内容投稿表

**描述：** 用户投稿的内容，待管理员审核

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| user_id | BIGINT | ✅ | - | 投稿用户 ID（外键） |
| type | ENUM | ✅ | - | 类型：document/project/job/knowledge |
| title | VARCHAR(255) | ✅ | - | 标题 |
| summary | VARCHAR(500) | ❌ | NULL | 摘要 |
| content | LONGTEXT | ✅ | - | 正文内容 |
| is_paid | BOOLEAN | ✅ | false | 是否付费内容 |
| price | DECIMAL(10,2) | ✅ | 0 | 价格 |
| currency | CHAR(3) | ✅ | 'CNY' | 币种 |
| status | ENUM | ✅ | 'pending' | 状态：pending/approved/rejected |
| review_note | TEXT | ❌ | NULL | 审核备注 |
| reviewed_by | BIGINT | ❌ | NULL | 审核人 ID（外键） |
| reviewed_at | TIMESTAMP | ❌ | NULL | 审核时间 |
| published_at | TIMESTAMP | ❌ | NULL | 发布时间 |
| published_model_type | VARCHAR(255) | ❌ | NULL | 发布模型类型 |
| published_model_id | BIGINT | ❌ | NULL | 发布模型 ID |
| payload | JSON | ❌ | NULL | 附加数据 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**索引：**
- `status + type` - 状态和类型索引
- `user_id + status` - 用户和状态索引

**变更记录：**
- v1.3.0 (2026-03-27) - 初始创建
- v1.3.1 (2026-03-27) - 添加 `published_model_type` 和 `published_model_id`

---

## 系统表

### sessions - 会话表

**描述：** Laravel 会话存储

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | VARCHAR(255) | ✅ | - | 会话 ID（主键） |
| user_id | BIGINT | ❌ | NULL | 用户 ID |
| ip_address | VARCHAR(45) | ❌ | NULL | IP 地址 |
| user_agent | TEXT | ❌ | NULL | 用户代理 |
| payload | LONGTEXT | ✅ | - | 会话数据 |
| last_activity | INT | ✅ | - | 最后活动时间戳 |

**索引：**
- `user_id` - 用户索引
- `last_activity` - 活动索引

---

### password_reset_tokens - 密码重置令牌表

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| email | VARCHAR(255) | ✅ | - | 邮箱（主键） |
| token | VARCHAR(255) | ✅ | - | 重置令牌 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |

---

### failed_jobs - 失败任务表

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| uuid | VARCHAR(255) | ✅ | - | 任务 UUID（唯一） |
| connection | TEXT | ✅ | - | 连接名称 |
| queue | TEXT | ✅ | - | 队列名称 |
| payload | LONGTEXT | ✅ | - | 任务数据 |
| exception | LONGTEXT | ✅ | - | 异常信息 |
| failed_at | TIMESTAMP | ✅ | CURRENT | 失败时间 |

---

### job_batches - 任务批处理表

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | VARCHAR(255) | ✅ | - | 批处理 ID（主键） |
| name | VARCHAR(255) | ✅ | - | 批处理名称 |
| total_jobs | INT | ✅ | - | 总任务数 |
| pending_jobs | INT | ✅ | - | 待处理数 |
| failed_jobs | INT | ✅ | - | 失败数 |
| failed_job_ids | LONGTEXT | ✅ | - | 失败任务 ID 列表 |
| options | MEDIUMTEXT | ❌ | NULL | 配置选项 |
| cancelled_at | INT | ❌ | NULL | 取消时间戳 |
| created_at | INT | ✅ | - | 创建时间戳 |
| finished_at | INT | ❌ | NULL | 完成时间戳 |

---

### async_tasks - 异步任务表

| 字段 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| id | BIGINT | ✅ | AUTO | 主键 ID |
| task_type | VARCHAR(100) | ✅ | - | 任务类型 |
| payload | JSON | ✅ | - | 任务数据 |
| status | ENUM | ✅ | 'pending' | 状态：pending/running/completed/failed |
| attempts | INT | ✅ | 0 | 尝试次数 |
| max_attempts | INT | ✅ | 3 | 最大尝试次数 |
| result | JSON | ❌ | NULL | 执行结果 |
| error_message | TEXT | ❌ | NULL | 错误信息 |
| scheduled_at | TIMESTAMP | ❌ | NULL | 计划执行时间 |
| started_at | TIMESTAMP | ❌ | NULL | 开始时间 |
| completed_at | TIMESTAMP | ❌ | NULL | 完成时间 |
| created_at | TIMESTAMP | ❌ | NULL | 创建时间 |
| updated_at | TIMESTAMP | ❌ | NULL | 更新时间 |

**变更记录：**
- v1.2.0 (2026-03-24) - 新增

---

## 版本变更历史

### v1.5.0 (2026-03-28) - 系统通知与投稿互动页

**新增表：**
- ✅ `system_notifications` - 站内系统通知（互动 + 后台运营）

**前台：**
- 文章 VIP 区去掉积分解锁、扩大遮罩、移除文章评论；点赞/收藏为作者写入 `system_notifications`（非本人操作时）。
- `GET /notifications` 系统通知列表；`GET /my-articles/engagement` 投稿已发布文章的点赞/收藏用户明细。
- Filament「系统通知」可向指定用户发送官方通知（`is_from_admin`，列表置顶）。

---

### v1.4.4 (2026-03-28) - VIP 到期提醒邮件（后台）

**基础数据（邮件模板，迁移写入）：**
- ✅ `email_templates.key = vip_expiry_reminder` - 「VIP 到期提醒」（迁移 `2026_03_28_120000_add_vip_expiry_reminder_email_template.php`；`RestoreAllDataSeeder`、`EmailTemplatePresetSeeder` 已同步）

**功能：**
- Filament「用户管理」列表/编辑：当用户 `subscription_ends_at` 在未来且 **≤3 天** 时显示「发送 VIP 到期提醒」按钮，调用 `EmailNotificationService::sendFromTemplateByKey('vip_expiry_reminder', …)`。

---

### v1.4.3 (2026-03-28) - 用户主页留言与 VIP 紧急邮件

**新增表：**
- ✅ `profile_messages` - 用户主页留言
- ✅ `vip_urgent_notification_logs` - VIP 紧急通知发送日志（配合每日 1 次限制）

**基础数据（邮件模板，迁移写入）：**
- ✅ `email_templates.key = profile_message_urgent` - 「主页留言 · VIP 紧急通知」模板（迁移 `2026_03_28_100001_add_profile_message_urgent_email_template.php`；全量恢复种子 `RestoreAllDataSeeder`、预设种子 `EmailTemplatePresetSeeder` 已同步）

**说明：**
- 留言路由：`POST users/{user}/messages`；紧急通知：`POST users/{user}/messages/{message}/urgent`（仅主页主人且 VIP，`ProfileMessageController`）。
- 文档：`email_templates` 表结构以代码/迁移为准（`key` 唯一，无 `slug` 列）。

---

### v1.4.2 (2026-03-28) - 项目 VIP 标记

**修改表：**
- ✅ `projects` - 新增 `is_vip`（BOOLEAN，默认 false）及索引 `is_vip`

**说明：**
- 用于「VIP 专属项目」业务；详情页与列表权限需在 `ProjectController` / 视图中与会员逻辑配合使用。

---

### v1.4.0 (2026-03-27) - 职位系统

**新增表：**
- ✅ `positions` - 职位表（含联系方式 VIP 控制）

**新增功能：**
- 职位列表和详情页
- 联系方式 VIP 权限控制
- 职位评论功能

---

### v1.3.1 (2026-03-27) - 投稿系统增强

**修改表：**
- ✅ `content_submissions` - 添加 `published_model_type` 和 `published_model_id`

**功能：**
- 投稿通过后可关联到具体内容

---

### v1.3.0 (2026-03-27) - 投稿和评论系统

**新增表：**
- ✅ `content_submissions` - 内容投稿表
- ✅ `comment_likes` - 评论点赞表

**修改表：**
- ✅ `comments` - 添加 `reply_to_id` 支持引用回复

**功能：**
- VIP 投稿功能
- 评论点赞功能
- 评论回复引用

---

### v1.2.0 (2026-03-24) - 邮件系统增强

**新增表：**
- ✅ `async_tasks` - 异步任务表

**修改表：**
- ✅ `email_logs` - 添加 `template_id` 关联模板
- ✅ `projects` - 添加分类和变现相关字段

**功能：**
- 异步任务处理
- 邮件模板关联

---

### v1.1.0 (2026-03-23) - 邮件系统

**新增表：**
- ✅ `email_settings` - 邮件设置表
- ✅ `email_templates` - 邮件模板表
- ✅ `email_logs` - 邮件发送日志
- ✅ `email_subscriptions` - 邮件订阅表
- ✅ `smtp_configs` - SMTP 配置表

**功能：**
- 完整的邮件发送系统
- 邮件订阅管理
- 发送日志记录

---

### v1.0.0 (2024-01-01) - 初始版本

**核心表：**
- ✅ `users` - 用户表
- ✅ `subscriptions` - 订阅表
- ✅ `orders` - 订单表
- ✅ `categories` - 分类表
- ✅ `articles` - 文章表
- ✅ `projects` - 项目表

**用户互动：**
- ✅ `favorites` - 收藏表
- ✅ `comments` - 评论表
- ✅ `view_histories` - 浏览历史表
- ✅ `user_actions` - 用户行为表

**系统表：**
- ✅ `sessions` - 会话表
- ✅ `password_reset_tokens` - 密码重置表
- ✅ `failed_jobs` - 失败任务表
- ✅ `job_batches` - 任务批处理表

---

## 📝 维护说明

### 添加新字段的规范

1. **创建新的迁移文件**
   ```bash
   php artisan make:migration add_xxx_to_users_table --table=users
   ```

2. **更新本文档**
   - 在对应表的"变更记录"中添加新版本
   - 在"版本变更历史"末尾添加新版本说明

3. **迁移文件命名规范**
   - 格式：`YYYY_MM_DD_HHMMSS_description.php`
   - 示例：`2026_03_27_000006_add_phone_to_users_table.php`

### 字段命名规范

- 主键：`id`
- 外键：`{表名单数}_id`（如 `user_id`, `article_id`）
- 时间戳：`created_at`, `updated_at`, `{动作}_at`
- 布尔值：`is_{形容词}`, `has_{名词}`, `can_{动词}`
- 计数：`{名词}_count`

### 索引规范

- 外键字段必须添加索引
- 查询条件字段建议添加索引
- 唯一字段添加唯一索引
- 多态关联添加联合索引

---

**文档维护者：** AI Assistant  
**最后审查：** 2026-03-28  
**下次审查：** 每次数据库变更后
