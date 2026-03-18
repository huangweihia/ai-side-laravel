# AI 副业情报局 - 修复脚本（纯 PowerShell 语法）

$projectDir = "D:\lewan\openclaw-data\workspace\ai-side-laravel"
Set-Location $projectDir

Write-Host "🚀 创建缺失的文件..." -ForegroundColor Cyan

# 创建目录
New-Item -ItemType Directory -Path "app\Providers" -Force | Out-Null
New-Item -ItemType Directory -Path "config" -Force | Out-Null

# 创建 AppServiceProvider.php
$providerContent = @"
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
"@

$providerContent | Out-File -FilePath "app\Providers\AppServiceProvider.php" -Encoding utf8 -NoNewline
Write-Host "✅ app/Providers/AppServiceProvider.php" -ForegroundColor Green

# 创建 config/app.php
$appConfig = @"
<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
    'name' => env('APP_NAME', 'AI 副业情报局'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'Asia/Shanghai',
    'locale' => 'zh_CN',
    'fallback_locale' => 'en',
    'faker_locale' => 'zh_CN',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    'maintenance' => ['driver' => 'file'],
    'providers' => ServiceProvider::defaultProviders()->merge([
        App\Providers\AppServiceProvider::class,
    ])->toArray(),
    'aliases' => Facade::defaultAliases()->merge([])->toArray(),
];
"@

$appConfig | Out-File -FilePath "config\app.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/app.php" -ForegroundColor Green

# 提交到 Git
git add .
git commit -m "添加缺失的配置文件"
git push

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host " ✅ 文件创建完成！" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "现在执行初始化..." -ForegroundColor Cyan

# 执行初始化命令
docker-compose exec php php artisan config:clear
docker-compose exec php php artisan cache:clear
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan make:filament-user

Write-Host ""
Write-Host "🎉 完成！访问：http://localhost:8081/admin" -ForegroundColor Green
Write-Host ""
