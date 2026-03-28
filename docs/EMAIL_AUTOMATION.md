# 📧 邮件自动化系统配置指南

## 系统架构

```
用户注册 → 邮件订阅表 → 定时任务扫描 → 队列缓冲 → 批量发送
                                    ↓
                            Redis 队列
                                    ↓
                          Laravel Scheduler
```

---

## 📋 前提条件

### 1. Redis 配置

确保 `.env` 文件中已配置：

```env
QUEUE_CONNECTION=redis
REDIS_HOST=host.docker.internal
REDIS_PORT=6379
```

### 2. SMTP 配置

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.qq.com
MAIL_PORT=465
MAIL_USERNAME=2801359160@qq.com
MAIL_PASSWORD=你的授权码
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=2801359160@qq.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🚀 启动邮件自动化

### 方式一：手动启动（推荐测试用）

```bash
cd D:\lewan\openclaw-data\workspace\ai-side-laravel

# 1. 启动队列消费者（监听邮件队列）
docker compose exec php php artisan queue:work --queue=emails --sleep=3 --tries=3

# 2. 新开一个终端，启动定时任务调度器
docker compose exec php php artisan schedule:work
```

### 方式二：使用启动脚本

```bash
cd D:\lewan\openclaw-data\workspace\ai-side-laravel

# 执行启动脚本
./start-mail-worker.sh
```

### 方式三：生产环境（Supervisor）

创建 `/etc/supervisor/conf.d/mail-worker.conf`：

```ini
[program:mail-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work --queue=emails --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/mail-worker.log
stopwaitsecs=3600
```

然后启动：

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start mail-worker:*
```

---

## ⏰ 定时任务配置

### 当前配置

| 时间 | 任务 | 说明 |
|------|------|------|
| 09:00 | `emails:send-scheduled` | 发送订阅邮件（日报/周报） |
| 10:00 | `ai-projects:send-daily` | 发送项目推荐邮件 |
| 10:30 | `jobs:send-daily` | 发送职位推荐邮件 |

### 修改发送时间

编辑 `app/Console/Kernel.php`：

```php
protected function schedule(Schedule $schedule): void
{
    // 每天早上 9 点发送
    $schedule->command('emails:send-scheduled', ['--limit=100'])
             ->dailyAt('09:00')
             ->timezone('Asia/Shanghai');
    
    // 每周一上午 9 点发送周报
    $schedule->command('emails:send-weekly')
             ->weeklyOn(1, '09:00')
             ->timezone('Asia/Shanghai');
}
```

---

## 📊 邮件发送流程

### 1. 用户订阅

```php
// 用户注册或订阅时
EmailSubscription::create([
    'user_id' => $userId,
    'email' => $email,
    'status' => true,
    'confirmed' => true,
]);
```

### 2. 创建邮件模板

后台管理 → 邮件模板 → 新建模板

**Slug 规则：**
- `daily-digest` - 日报模板
- `weekly-digest` - 周报模板
- `welcome` - 欢迎邮件
- `password-reset` - 密码重置

**可用变量：**
- `{user.name}` - 用户昵称
- `{user.email}` - 用户邮箱
- `{date}` - 当前日期
- `{time}` - 当前时间
- `{unsubscribe_url}` - 取消订阅链接

### 3. 定时发送

系统每天 9:00 自动扫描 `email_subscriptions` 表，发送邮件给所有已确认的用户。

---

## 🔍 监控和日志

### 查看发送日志

```bash
# 查看实时日志
docker compose exec php tail -f storage/logs/laravel.log

# 查看最近的发送记录
docker compose exec mysql mysql -u root -p ai_side_laravel -e "SELECT * FROM email_logs ORDER BY created_at DESC LIMIT 20;"
```

### 手动触发发送

```bash
# 立即发送一次（测试用）
docker compose exec php php artisan emails:send-scheduled --limit=10

# 查看队列状态
docker compose exec php php artisan queue:status
```

---

## ⚠️ 常见问题

### 1. 邮件发送失败

**检查项：**
- SMTP 授权码是否正确
- Redis 服务是否运行
- 队列消费者是否启动

```bash
# 测试 SMTP 连接
docker compose exec php php artisan tinker
>>> Mail::raw('test', function($m) { $m->to('2801359160@qq.com')->subject('Test'); });
```

### 2. 队列堆积

**解决方案：**
```bash
# 增加队列消费者数量
# Supervisor 配置中增加 numprocs=4

# 清理失败队列
docker compose exec php php artisan queue:flush
```

### 3. 定时任务不执行

**检查项：**
```bash
# 查看定时任务列表
docker compose exec php php artisan schedule:list

# 测试定时任务
docker compose exec php php artisan schedule:run --verbose
```

---

## 📈 性能优化

### 批量发送优化

```bash
# 增加每次发送数量
php artisan emails:send-scheduled --limit=500

# 添加发送延迟（避免被标记为垃圾邮件）
# 在 SendScheduledEmails.php 中添加
usleep(200000); // 200ms 延迟
```

### 队列优化

```env
# .env 配置
QUEUE_CONNECTION=redis
REDIS_HOST=host.docker.internal
REDIS_PORT=6379
REDIS_PASSWORD=
```

---

## 🎯 最佳实践

1. **发送频率**：每天不超过 1 封营销邮件
2. **发送时间**：选择用户活跃时段（9:00-10:00）
3. **内容质量**：提供有价值的信息，避免纯广告
4. **取消订阅**：必须提供明显的取消订阅链接
5. **发送限制**：每次限制 100-200 封，避免被标记为垃圾邮件

---

**文档维护者：** AI Assistant  
**最后更新：** 2026-03-27  
**下次审查：** 根据实际发送效果调整
