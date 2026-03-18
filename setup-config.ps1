# AI 副业情报局 - Laravel 配置文件创建脚本
# 在 PowerShell（管理员）执行

$projectDir = "D:\lewan\openclaw-data\workspace\ai-side-laravel"
Set-Location $projectDir

Write-Host "🚀 创建 Laravel 配置文件..." -ForegroundColor Cyan

# 创建 config 目录
New-Item -ItemType Directory -Path "config" -Force | Out-Null

# config/app.php
@'
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
'@ | Out-File -FilePath "config\app.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/app.php" -ForegroundColor Green

# config/database.php
@'
<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'ai_side'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
    ],
    'migrations' => 'migrations',
];
'@ | Out-File -FilePath "config\database.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/database.php" -ForegroundColor Green

# config/cache.php
@'
<?php

return [
    'default' => env('CACHE_DRIVER', 'file'),
    'stores' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],
    ],
    'prefix' => env('CACHE_PREFIX', 'ai_side'),
];
'@ | Out-File -FilePath "config\cache.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/cache.php" -ForegroundColor Green

# config/session.php
@'
<?php

return [
    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => 'sessions',
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env('SESSION_COOKIE', 'ai_side_session'),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN'),
    'secure' => env('SESSION_SECURE_COOKIE'),
    'http_only' => true,
    'same_site' => 'lax',
];
'@ | Out-File -FilePath "config\session.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/session.php" -ForegroundColor Green

# config/view.php
@'
<?php

return [
    'paths' => [resource_path('views')],
    'compiled' => env('VIEW_COMPILED_PATH', realpath(storage_path('framework/views'))),
];
'@ | Out-File -FilePath "config\view.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/view.php" -ForegroundColor Green

# config/mail.php
@'
<?php

return [
    'default' => env('MAIL_MAILER', 'smtp'),
    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.qq.com'),
            'port' => env('MAIL_PORT', 465),
            'encryption' => env('MAIL_ENCRYPTION', 'ssl'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
        ],
    ],
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', '2801359160@qq.com'),
        'name' => env('MAIL_FROM_NAME', 'AI 副业情报局'),
    ],
];
'@ | Out-File -FilePath "config\mail.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/mail.php" -ForegroundColor Green

# config/auth.php
@'
<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
    'password_timeout' => 10800,
];
'@ | Out-File -FilePath "config\auth.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/auth.php" -ForegroundColor Green

# config/hashing.php
@'
<?php

return [
    'driver' => 'bcrypt',
    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
        'verify' => true,
    ],
    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
        'verify' => true,
    ],
];
'@ | Out-File -FilePath "config\hashing.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/hashing.php" -ForegroundColor Green

# config/logging.php
@'
<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),
    'deprecations' => ['channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null')],
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],
    ],
];
'@ | Out-File -FilePath "config\logging.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/logging.php" -ForegroundColor Green

# config/queue.php
@'
<?php

return [
    'default' => env('QUEUE_CONNECTION', 'sync'),
    'connections' => [
        'sync' => [
            'driver' => 'sync',
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'default'),
        ],
    ],
    'batching' => [
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'job_batches',
    ],
    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],
];
'@ | Out-File -FilePath "config\queue.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/queue.php" -ForegroundColor Green

# config/services.php
@'
<?php

return [
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
];
'@ | Out-File -FilePath "config\services.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/services.php" -ForegroundColor Green

# config/cors.php
@'
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
'@ | Out-File -FilePath "config\cors.php" -Encoding utf8 -NoNewline
Write-Host "✅ config/cors.php" -ForegroundColor Green

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host " ✅ 配置文件创建完成！" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "下一步执行：" -ForegroundColor Cyan
Write-Host "docker-compose exec php php artisan config:clear" -ForegroundColor Gray
Write-Host "docker-compose exec php php artisan cache:clear" -ForegroundColor Gray
Write-Host "docker-compose exec php php artisan key:generate" -ForegroundColor Gray
Write-Host "docker-compose exec php php artisan migrate" -ForegroundColor Gray
Write-Host "docker-compose exec php php artisan make:filament-user" -ForegroundColor Gray
Write-Host ""
