# 🧪 自动化测试指南

## 安装 Pest PHP（推荐）

Pest 是更简洁的 PHP 测试框架，基于 PHPUnit。

### 1. 安装 Pest

```powershell
# 进入项目目录
cd D:\lewan\openclaw-data\workspace\ai-side-laravel

# Docker 中安装
docker compose exec php composer require --dev pestphp/pest pestphp/pest-plugin-laravel --with-all-dependencies

# 初始化 Pest
docker compose exec php php artisan pest:install
```

### 2. 运行测试

```powershell
# 运行所有测试
docker compose exec php ./vendor/bin/pest

# 运行特定测试
docker compose exec php ./vendor/bin/pest tests/Feature/ProjectTest.php

# 运行带过滤的测试
docker compose exec php ./vendor/bin/pest --filter=it_can_view_projects_index

# 生成覆盖率报告
docker compose exec php ./vendor/bin/pest --coverage
```

---

## 现有测试用例

### 项目测试 (ProjectTest.php)
- ✅ 查看项目列表页
- ✅ 按分类筛选项目
- ✅ 搜索项目
- ✅ 空状态显示

### 文章测试 (ArticleTest.php)
- ✅ 查看文章列表页
- ✅ 按分类筛选文章
- ✅ 搜索文章
- ✅ 只显示已发布文章

### 邮件系统测试 (EmailSystemTest.php)
- ✅ 创建邮件订阅
- ✅ 切换订阅偏好
- ✅ 全部退订
- ✅ 注册时自动创建订阅

---

## 测试覆盖率

```bash
# 查看覆盖率
docker compose exec php ./vendor/bin/pest --coverage

# 生成 HTML 报告
docker compose exec php ./vendor/bin/pest --coverage-html ./coverage
```

---

## 持续集成

### GitHub Actions

创建 `.github/workflows/tests.yml`:

```yaml
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
        options: --health-cmd="mysqladmin ping"
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
          
      - name: Install Dependencies
        run: composer install
        
      - name: Run Tests
        run: ./vendor/bin/pest
        env:
          DB_CONNECTION: mysql
          DB_DATABASE: test_db
```

---

## 编写新测试

### 示例：测试用户登录

```php
/** @test */
public function it_can_login_user()
{
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/dashboard');
}
```

---

## 测试最佳实践

1. **使用 RefreshDatabase** - 每次测试后重置数据库
2. **测试命名清晰** - `it_can_do_something()`
3. **一个测试一个功能** - 保持测试原子性
4. **使用 Factory** - 创建测试数据
5. **断言明确** - 使用具体的断言方法

---

## 常见问题

### Q: 测试失败怎么办？
A: 查看错误信息，检查数据库连接，确保迁移已运行

### Q: 如何调试测试？
A: 使用 `dd()` 或 `dump()` 输出变量

### Q: 测试太慢怎么办？
A: 使用 `RefreshDatabase` 而不是迁移，使用内存数据库

---

**下一步：** 运行测试确保所有功能正常

```powershell
docker compose exec php ./vendor/bin/pest
```
