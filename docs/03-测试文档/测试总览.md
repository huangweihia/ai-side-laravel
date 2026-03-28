# AI 副业情报局 - 自动化测试文档

## 一、测试环境配置

### 1.1 测试环境要求
```
PHP: 8.2+
Laravel: 10.x
Database: MySQL 8.0 (测试库)
PHPUnit: 10.x
```

### 1.2 测试数据库配置
```env
# .env.testing
APP_ENV=testing
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai_side_test
DB_USERNAME=ai_side
DB_PASSWORD=aiside123456

REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### 1.3 安装测试依赖
```bash
# 安装 PHPUnit
composer require --dev phpunit/phpunit

# 安装 Laravel Dusk (浏览器自动化测试)
composer require --dev laravel/dusk

# 安装 Pest (现代化测试框架，可选)
composer require --dev pestphp/pest pestphp/pest-plugin-laravel
```

---

## 二、测试分类

### 2.1 单元测试 (Unit Tests)
测试单个类或方法的功能

**位置**: `tests/Unit/`

**示例**:
```php
// tests/Unit/Models/ProjectTest.php
<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_project()
    {
        $project = Project::create([
            'name' => 'Test Project',
            'full_name' => 'test/test-project',
            'url' => 'https://github.com/test/test-project',
            'stars' => 1000,
            'score' => 8.5,
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'stars' => 1000,
        ]);
    }

    /** @test */
    public function it_calculates_score_correctly()
    {
        $project = new Project();
        $score = $project->calculateScore(50000, 5, 'high');
        
        $this->assertGreaterThan(5, $score);
        $this->assertLessThan(10, $score);
    }
}
```

### 2.2 功能测试 (Feature Tests)
测试完整的业务流程

**位置**: `tests/Feature/`

**示例**:
```php
// tests/Feature/Auth/RegistrationTest.php
<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function user_cannot_register_with_invalid_email()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
```

### 2.3 浏览器测试 (Browser Tests / Dusk)
模拟真实用户操作

**位置**: `tests/Browser/`

**示例**:
```php
// tests/Browser/UserRegistrationTest.php
<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as DuskTestCase;

class UserRegistrationTest extends DuskTestCase
{
    /** @test */
    public function user_can_register_through_browser()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'Dusk User')
                    ->type('email', 'dusk@example.com')
                    ->type('password', 'password123')
                    ->type('password_confirmation', 'password123')
                    ->press('注册')
                    ->waitForLocation('/dashboard')
                    ->assertPathIs('/dashboard')
                    ->assertSee('Dusk User');
        });
    }

    /** @test */
    public function guest_cannot_access_dashboard()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard')
                    ->assertPathIs('/login');
        });
    }
}
```

---

## 三、核心功能测试用例

### 3.1 用户系统测试

| 测试项 | 测试内容 | 预期结果 | 优先级 |
|--------|----------|----------|--------|
| 用户注册 | 有效数据注册 | 注册成功，跳转首页 | P0 |
| 用户注册 | 邮箱已存在 | 提示邮箱已被使用 | P0 |
| 用户注册 | 密码不一致 | 提示密码不匹配 | P0 |
| 用户登录 | 正确账号密码 | 登录成功 | P0 |
| 用户登录 | 错误密码 | 提示密码错误 | P0 |
| 用户登录 | 账号不存在 | 提示账号不存在 | P0 |
| 找回密码 | 有效邮箱 | 发送重置邮件 | P1 |
| 找回密码 | 无效邮箱 | 提示邮箱不存在 | P1 |

### 3.2 会员系统测试

| 测试项 | 测试内容 | 预期结果 | 优先级 |
|--------|----------|----------|--------|
| 会员购买 | 选择套餐 | 生成订单 | P0 |
| 会员购买 | 支付成功 | 自动开通会员 | P0 |
| 会员购买 | 支付失败 | 订单状态为待支付 | P0 |
| 会员权益 | 访问付费内容 | 会员可访问 | P0 |
| 会员权益 | 访问付费内容 | 非会员不可访问 | P0 |
| 会员到期 | 到期提醒 | 发送提醒邮件 | P1 |
| 会员续费 | 续费操作 | 延长有效期 | P1 |

### 3.3 内容系统测试

| 测试项 | 测试内容 | 预期结果 | 优先级 |
|--------|----------|----------|--------|
| 文章列表 | 获取已发布文章 | 返回文章列表 | P0 |
| 文章列表 | 付费内容筛选 | 非会员看不到详情 | P0 |
| 文章详情 | 访问免费文章 | 显示完整内容 | P0 |
| 文章详情 | 访问付费文章 | 会员显示完整内容 | P0 |
| 文章详情 | 访问付费文章 | 非会员显示摘要 | P0 |
| 项目列表 | 获取热门项目 | 按 star 排序 | P0 |
| 项目收集 | GitHub API 调用 | 成功获取数据 | P0 |
| 项目收集 | API 限流处理 | 自动重试/切换账号 | P1 |

### 3.4 邮件系统测试

| 测试项 | 测试内容 | 预期结果 | 优先级 |
|--------|----------|----------|--------|
| 订阅邮件 | 用户订阅 | 发送确认邮件 | P0 |
| 订阅邮件 | 取消订阅 | 发送确认邮件 | P0 |
| 日报推送 | 定时任务触发 | 发送邮件给订阅用户 | P0 |
| 日报推送 | 邮件内容 | 包含当日项目 | P0 |
| 日报推送 | 发送失败 | 记录失败日志 | P1 |

---

## 四、API 接口测试

### 4.1 认证接口

```bash
# 用户注册
POST /api/auth/register
{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
Expected: 201 Created, { token: "...", user: {...} }

# 用户登录
POST /api/auth/login
{
    "email": "test@example.com",
    "password": "password123"
}
Expected: 200 OK, { token: "...", user: {...} }

# 获取当前用户
GET /api/auth/me
Header: Authorization: Bearer {token}
Expected: 200 OK, { user: {...} }
```

### 4.2 内容接口

```bash
# 获取项目列表
GET /api/projects?limit=10&sort=stars
Expected: 200 OK, { data: [...], meta: {...} }

# 获取项目详情
GET /api/projects/{id}
Expected: 200 OK, { project: {...} }

# 获取文章列表
GET /api/articles?category=ai-projects&page=1
Expected: 200 OK, { data: [...], meta: {...} }

# 获取文章详情
GET /api/articles/{slug}
Expected: 200 OK, { article: {...} }
```

### 4.3 会员接口

```bash
# 获取会员状态
GET /api/member/status
Header: Authorization: Bearer {token}
Expected: 200 OK, { is_member: true, expires_at: "..." }

# 创建订单
POST /api/member/order
{
    "plan": "yearly"
}
Header: Authorization: Bearer {token}
Expected: 201 Created, { order_no: "...", amount: 199 }

# 支付回调
POST /api/payment/callback
{
    "order_no": "...",
    "status": "paid"
}
Expected: 200 OK, { success: true }
```

---

## 五、性能测试

### 5.1 负载测试
```bash
# 使用 Apache Bench
ab -n 1000 -c 10 http://localhost:8080/api/projects

# 使用 wrk
wrk -t12 -c400 -d30s http://localhost:8080/api/projects
```

### 5.2 性能指标
| 接口 | 目标响应时间 | 目标 QPS |
|------|-------------|---------|
| 首页 | < 500ms | 100+ |
| 项目列表 API | < 200ms | 500+ |
| 文章详情 API | < 300ms | 300+ |
| 用户登录 | < 500ms | 100+ |

---

## 六、测试执行

### 6.1 运行所有测试
```bash
# 运行 PHPUnit 测试
php artisan test

# 运行特定测试
php artisan test --filter UserRegistrationTest

# 运行 Dusk 测试
php artisan dusk

# 生成测试覆盖率报告
php artisan test --coverage-html=coverage
```

### 6.2 CI/CD 集成
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
          MYSQL_DATABASE: ai_side_test
        ports:
          - 3306:3306
      
      redis:
        image: redis:alpine
        ports:
          - 6379:6379
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      
      - name: Install Dependencies
        run: composer install --no-interaction
      
      - name: Run Tests
        run: php artisan test
        env:
          DB_CONNECTION: mysql
          DB_HOST: 127.0.0.1
          DB_DATABASE: ai_side_test
```

---

## 七、测试报告模板

### 7.1 测试执行报告
```markdown
# 测试执行报告

**日期**: 2026-03-18
**版本**: v1.0.0
**测试人员**: AI Assistant

## 测试概况
- 总测试用例：150
- 通过：145
- 失败：5
- 跳过：0
- 通过率：96.7%

## 失败用例
1. UserRegistrationTest::user_cannot_register_with_invalid_email
   - 原因：邮箱验证规则变更
   - 状态：待修复

## 性能测试结果
- 首页平均响应：320ms (目标：<500ms) ✅
- 项目列表 API：150ms (目标：<200ms) ✅
- 文章详情 API：280ms (目标：<300ms) ✅

## 建议
1. 修复失败的邮箱验证测试
2. 优化数据库查询索引
3. 增加缓存层提升性能
```

---

## 八、测试检查清单

### 上线前必测
- [ ] 用户注册/登录流程
- [ ] 会员购买流程
- [ ] 支付回调处理
- [ ] 邮件发送功能
- [ ] 后台管理功能
- [ ] 数据收集脚本
- [ ] 定时任务执行

### 回归测试
- [ ] 所有 P0 级别测试用例
- [ ] 核心业务流程
- [ ] API 接口兼容性
- [ ] 数据库迁移

---

**测试文档持续更新中...**
