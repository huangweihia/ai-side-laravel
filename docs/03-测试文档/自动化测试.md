# 🧪 自动化测试指南

## 📋 测试准备

### 1. 数据库要求
- ✅ MySQL/MariaDB 正常运行
- ✅ 迁移已执行 (`php artisan migrate`)
- ✅ 基础配置已导入 (`EmailConfigSeeder`)

### 2. 测试数据生成

```powershell
# Docker Desktop 环境
cd D:\lewan\openclaw-data\workspace\ai-side-laravel

# 生成测试数据
docker compose exec php php artisan db:seed --class=TestDataSeeder --force
```

**生成内容：**
- 5 个分类
- 9 个用户（1 管理员 + 3 VIP + 5 普通）
- 10 个 AI 副业项目
- 8 篇文章
- 7 条邮件日志
- 5 个职位

---

## 🤖 自动化测试方案

### 方案一：PHPUnit 测试（推荐）

**安装配置：**
```bash
docker compose exec php composer require --dev phpunit/phpunit
```

**测试文件位置：**
```
tests/Feature/
├── EmailSystemTest.php      # 邮件系统测试
├── SubscriptionTest.php     # 订阅功能测试
├── ProjectTest.php          # 项目管理测试
└── ArticleTest.php          # 文章管理测试
```

**示例测试用例：**

```php
// tests/Feature/EmailSystemTest.php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\EmailSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailSystemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_email_subscription()
    {
        $subscription = EmailSubscription::create([
            'email' => 'test@example.com',
            'subscribed_to_daily' => true,
        ]);

        $this->assertDatabaseHas('email_subscriptions', [
            'email' => 'test@example.com',
            'subscribed_to_daily' => true,
        ]);
    }

    /** @test */
    public function it_can_unsubscribe()
    {
        $subscription = EmailSubscription::create([
            'email' => 'test@example.com',
            'subscribed_to_daily' => true,
        ]);

        $subscription->unsubscribeAll();

        $this->assertFalse($subscription->fresh()->isSubscribedToDaily());
    }

    /** @test */
    public function it_can_send_test_email()
    {
        // 测试邮件发送功能
        $response = $this->post('/admin/email-manager/send-test', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('email_logs', [
            'recipient' => 'test@example.com',
            'status' => 'sent',
        ]);
    }
}
```

**运行测试：**
```bash
docker compose exec php php artisan test
docker compose exec php php artisan test --filter EmailSystemTest
```

---

### 方案二：Pest PHP（更简洁）

**安装：**
```bash
docker compose exec php composer require --dev pestphp/pest
docker compose exec php php artisan pest:install
```

**测试示例：**
```php
// tests/Feature/EmailTest.php
it('can create email subscription', function () {
    $subscription = EmailSubscription::create([
        'email' => 'test@example.com',
    ]);

    expect($subscription)->email->toBe('test@example.com');
});

it('can toggle subscription preference', function () {
    $subscription = EmailSubscription::create([
        'email' => 'test@example.com',
        'subscribed_to_daily' => true,
    ]);

    $subscription->update(['subscribed_to_daily' => false]);

    expect($subscription->fresh()->subscribed_to_daily)->toBeFalse();
});
```

**运行：**
```bash
docker compose exec php ./vendor/bin/pest
```

---

### 方案三：Laravel Dusk（浏览器自动化）

**安装：**
```bash
docker compose exec php composer require --dev laravel/dusk
docker compose exec php php artisan dusk:install
```

**测试示例：**
```php
// tests/Browser/EmailManagerTest.php
namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EmailManagerTest extends DuskTestCase
{
    public function testAddRecipient()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/admin/email-manager')
                    ->type('recipient', 'newuser@example.com')
                    ->press('添加收件人')
                    ->waitForText('添加成功')
                    ->assertSee('newuser@example.com');
        });
    }

    public function testToggleSubscription()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/admin/email-manager')
                    ->click('@toggle-daily')
                    ->waitForText('已更新')
                    ->assertSee('✅ 已更新');
        });
    }
}
```

**运行：**
```bash
docker compose exec php php artisan dusk
```

---

## 📊 测试覆盖率报告

```bash
# 生成覆盖率报告
docker compose exec php php artisan test --coverage

# 生成 HTML 报告
docker compose exec php php artisan test --coverage-html ./coverage
```

---

## 🎯 核心测试用例清单

### 邮件系统
- [ ] 添加收件人
- [ ] 删除收件人
- [ ] 批量导入
- [ ] 批量删除
- [ ] 发送测试邮件
- [ ] 切换订阅类型
- [ ] 退订功能
- [ ] 重新订阅

### 订阅管理
- [ ] 创建订阅记录
- [ ] 更新订阅偏好
- [ ] 退订所有
- [ ] 退订链接生成
- [ ] 退订令牌验证

### 邮件模板
- [ ] 创建模板
- [ ] 编辑模板
- [ ] 预览模板
- [ ] 删除模板
- [ ] 模板变量替换

### SMTP 配置
- [ ] 保存配置
- [ ] 测试连接
- [ ] 加密显示密码

---

## 🚀 CI/CD 集成

### GitHub Actions 示例

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: test_db
        options: --health-cmd="mysqladmin ping" --health-interval=10s
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
          
      - name: Install Dependencies
        run: composer install
        
      - name: Run Tests
        run: php artisan test
        env:
          DB_CONNECTION: mysql
          DB_DATABASE: test_db
```

---

## 📝 手动测试清单

### 后台功能
- [ ] 访问 `/admin` 能正常登录
- [ ] 邮件管理页面加载正常
- [ ] 添加收件人成功
- [ ] 删除收件人成功
- [ ] 订阅切换正常
- [ ] 发送测试邮件成功
- [ ] 邮件模板预览正常
- [ ] SMTP 配置可保存

### 前台功能
- [ ] 用户注册成功
- [ ] 欢迎邮件发送
- [ ] 订阅偏好页面可访问
- [ ] 退订链接有效
- [ ] 重新订阅有效

---

## 🐛 常见问题排查

### 测试失败
```bash
# 清除缓存
docker compose exec php php artisan cache:clear
docker compose exec php php artisan config:clear

# 重新迁移
docker compose exec php php artisan migrate:fresh --seed
```

### 数据库连接失败
```bash
# 检查 .env 配置
docker compose exec php cat .env | grep DB_

# 测试连接
docker compose exec php php artisan db:show
```

---

**推荐起步：** 先运行 TestDataSeeder 生成数据，手动测试主要功能，然后逐步添加自动化测试用例。
