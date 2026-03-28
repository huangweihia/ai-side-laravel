# 邮件系统修复与增强 - 实施指南

## 📋 本次更新内容

### P0 - 严重问题修复
- ✅ 修复 `EmailSetting::getRecipients()` key 不一致 bug
- ✅ 新增用户订阅管理功能（退订/重新订阅/偏好设置）
- ✅ 新增注册欢迎邮件

### P1 - 重要功能
- ✅ SMTP 配置管理后台（可编辑、测试连接）
- ✅ 邮件模板管理后台（可视化编辑、预览）
- ✅ 批量导入/导出收件人

### P2 - 体验优化
- ✅ 个人中心订阅状态展示
- ✅ 订阅偏好设置页面
- ✅ 退订页面优化

---

## 🚀 部署步骤

### 1. 确保数据库运行

```bash
# 启动 MySQL/MariaDB
service mariadb start
# 或
service mysql start

# 验证连接
mysqladmin ping -h localhost -u ai_side -paiside123456
```

### 2. 运行数据库迁移

```bash
cd /home/node/.openclaw/workspace/ai-side-laravel

# 运行迁移
php artisan migrate --force
```

### 3. 初始化默认数据

```bash
# 运行 Seeder
php artisan db:seed --class=EmailConfigSeeder --force
```

### 4. 清理配置缓存（可选）

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 📁 新增文件清单

### 模型 (Models)
- `app/Models/EmailSubscription.php` - 邮件订阅模型
- `app/Models/SmtpConfig.php` - SMTP 配置模型

### 控制器 (Controllers)
- `app/Http/Controllers/SubscriptionController.php` - 订阅管理控制器

### 资源 (Resources)
- `app/Filament/Resources/SmtpConfigResource.php` - SMTP 配置管理
- `app/Filament/Resources/EmailTemplateResource.php` - 邮件模板管理

### 视图 (Views)
- `resources/views/subscriptions/unsubscribe.blade.php` - 退订页面
- `resources/views/subscriptions/unsubscribed.blade.php` - 退订成功
- `resources/views/subscriptions/resubscribed.blade.php` - 重新订阅
- `resources/views/subscriptions/preferences.blade.php` - 订阅偏好
- `resources/views/emails/welcome.blade.php` - 欢迎邮件模板
- `resources/views/filament/email-template-preview.blade.php` - 模板预览

### 迁移 (Migrations)
- `database/migrations/2026_03_24_000001_create_email_subscriptions_table.php`
- `database/migrations/2026_03_24_000002_create_smtp_configs_table.php`

### Seeder
- `database/seeders/EmailConfigSeeder.php`

---

## 🔧 修改文件清单

### 核心修复
- `app/Models/EmailSetting.php` - 修复 getRecipients() key 不一致

### 路由
- `routes/web.php` - 添加订阅管理路由

### 视图
- `resources/views/dashboard.blade.php` - 添加订阅状态展示
- `resources/views/filament/pages/email-manager.blade.php` - 添加批量操作

### 控制器
- `app/Http/Controllers/Auth/RegisterController.php` - 添加欢迎邮件发送

### 页面
- `app/Filament/Pages/EmailManager.php` - 添加批量导入/导出

---

## 🎯 功能说明

### 1. 用户订阅管理

**前台用户功能：**
- 个人中心查看订阅状态
- 订阅偏好设置页面（`/subscriptions/preferences`）
- 邮件退订链接（每封邮件底部）
- 重新订阅功能

**退订流程：**
```
用户点击退订链接 → 选择退订类型 → 确认退订 → 显示成功页面
```

### 2. SMTP 配置管理

**后台路径：** `/admin/smtp-configs`

**功能：**
- 编辑 SMTP 服务器配置
- 发送测试邮件验证
- 加密显示密码/授权码

### 3. 邮件模板管理

**后台路径：** `/admin/email-templates`

**功能：**
- 创建/编辑邮件模板
- 可视化编辑器
- 模板预览
- 变量支持（`{{date}}`, `{{name}}` 等）

### 4. 批量操作

**后台路径：** `/admin/email-manager`

**功能：**
- 批量导入（文本粘贴，每行一个邮箱）
- 批量导出（下载 TXT 文件）
- 批量删除（复选框多选）

---

## 📊 数据库表结构

### email_subscriptions
| 字段 | 类型 | 说明 |
|------|------|------|
| id | bigint | 主键 |
| user_id | bigint | 用户 ID（可空） |
| email | varchar | 邮箱地址 |
| subscribed_to_daily | boolean | 订阅日报 |
| subscribed_to_weekly | boolean | 订阅周报 |
| subscribed_to_notifications | boolean | 订阅通知 |
| unsubscribe_token | varchar | 退订令牌 |
| unsubscribed_at | timestamp | 退订时间 |

### smtp_configs
| 字段 | 类型 | 说明 |
|------|------|------|
| id | bigint | 主键 |
| key | varchar | 配置键 |
| value | text | 配置值 |
| description | varchar | 描述 |
| is_encrypted | boolean | 是否加密显示 |

---

## ⚠️ 注意事项

1. **数据库必须先启动** 才能运行迁移
2. **欢迎邮件发送失败不影响注册流程**（已捕获异常）
3. **退订令牌唯一**，重新订阅后会生成新令牌
4. **SMTP 密码加密存储**，后台显示为 ••••••

---

## 🧪 测试清单

- [ ] 新用户注册后收到欢迎邮件
- [ ] 个人中心显示订阅状态
- [ ] 订阅偏好设置可保存
- [ ] 退订链接有效
- [ ] 重新订阅有效
- [ ] SMTP 配置可编辑
- [ ] 测试邮件发送成功
- [ ] 批量导入/导出正常
- [ ] 邮件模板可编辑预览

---

## 📞 问题排查

### 数据库连接失败
```bash
# 检查 MySQL 状态
service mariadb status

# 查看错误日志
tail -f /var/log/mysql/error.log
```

### 迁移失败
```bash
# 检查 .env 配置
cat .env | grep DB_

# 手动测试连接
mysql -h localhost -u ai_side -p
```

### 邮件发送失败
```bash
# 检查 SMTP 配置
# 后台 → 系统设置 → SMTP 配置 → 发送测试邮件
```

---

**最后更新：** 2026-03-24  
**版本：** v1.0.0
