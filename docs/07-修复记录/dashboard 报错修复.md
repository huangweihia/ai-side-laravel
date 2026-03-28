# 🐛 Dashboard 页面报错修复

## 问题时间
2026-03-26 13:21

## 错误原因
访问 `http://localhost:8081/dashboard` 时报错

### 根本原因
`HomeController::dashboard()` 方法中使用了 `latest()` 方法查询 `ViewHistory` 模型，但 `ViewHistory` 模型关闭了时间戳（`public $timestamps = false;`），导致 `latest()` 方法无法正常工作。

### 错误代码
```php
// ❌ 错误：ViewHistory 没有 created_at/updated_at 字段
$histories = ViewHistory::where('user_id', $user->id)
    ->with('viewable')
    ->latest()  // 这里会报错
    ->limit(10)
    ->get();
```

## 解决方案

### 修复后的代码
```php
// ✅ 正确：使用 viewed_at 字段排序
$histories = ViewHistory::where('user_id', $user->id)
    ->with('viewable')
    ->orderBy('viewed_at', 'desc')
    ->limit(10)
    ->get();
```

### 修改的文件
- `app/Http/Controllers/HomeController.php` - 修改 `dashboard()` 方法

### 修改内容
1. **收藏列表** - `latest()` → `orderBy('created_at', 'desc')`
2. **评论列表** - `latest()` → `orderBy('created_at', 'desc')`
3. **浏览历史** - `latest()` → `orderBy('viewed_at', 'desc')`

## 验证步骤

```powershell
cd D:\lewan\openclaw-data\workspace\ai-side-laravel

# 1. 清理缓存
docker compose exec php php artisan view:clear
docker compose exec php php artisan cache:clear

# 2. 访问个人中心
# http://localhost:8081/dashboard
```

## 预期结果
- ✅ 页面正常加载
- ✅ 显示用户信息
- ✅ 显示收藏列表（最多 10 条）
- ✅ 显示评论列表（最多 10 条）
- ✅ 显示浏览历史（最多 10 条）
- ✅ 显示统计数据

## 教训
使用 `latest()` 方法前，确保模型有时间戳字段（`created_at` / `updated_at`）。如果模型关闭了时间戳，需要手动指定排序字段。

---

**修复时间：** 2026-03-26 13:22  
**修复人：** AI 助手
