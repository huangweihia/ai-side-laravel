# AI 副业情报局 - Docker 容器内修复脚本
# 在 PowerShell 执行

$projectDir = "D:\lewan\openclaw-data\workspace\ai-side-laravel"
Set-Location $projectDir

Write-Host "🚀 在 Docker 容器内创建缺失的文件..." -ForegroundColor Cyan

# 在容器内执行所有修复命令
docker-compose exec php bash -c '
cd /var/www/html

echo "✅ 创建 app/Providers 目录"
mkdir -p app/Providers app/Console app/Exceptions

echo "✅ 创建 AppServiceProvider.php"
cat > app/Providers/AppServiceProvider.php << '\''ENDOFFILE'\''
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
ENDOFFILE

echo "✅ 创建 config/app.php"
cat > config/app.php << '\''ENDOFFILE'\''
<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
    '\''name'\'' => env('\''APP_NAME'\'', '\''AI 副业情报局'\''),
    '\''env'\'' => env('\''APP_ENV'\'', '\''production'\''),
    '\''debug'\'' => (bool) env('\''APP_DEBUG'\'', false),
    '\''url'\'' => env('\''APP_URL'\'', '\''http://localhost'\''),
    '\''timezone'\'' => '\''Asia/Shanghai'\'',
    '\''locale'\'' => '\''zh_CN'\'',
    '\''fallback_locale'\'' => '\''en'\'',
    '\''faker_locale'\'' => '\''zh_CN'\'',
    '\''key'\'' => env('\''APP_KEY'\''),
    '\''cipher'\'' => '\''AES-256-CBC'\'',
    '\''maintenance'\'' => ['\''driver'\'' => '\''file'\''],
    '\''providers'\'' => ServiceProvider::defaultProviders()->merge([
        App\Providers\AppServiceProvider::class,
    ])->toArray(),
    '\''aliases'\'' => Facade::defaultAliases()->merge([])->toArray(),
];
ENDOFFILE

echo "✅ 创建 config/database.php"
cat > config/database.php << '\''ENDOFFILE'\''
<?php

return [
    '\''default'\'' => env('\''DB_CONNECTION'\'', '\''mysql'\''),
    '\''connections'\'' => [
        '\''mysql'\'' => [
            '\''driver'\'' => '\''mysql'\'',
            '\''host'\'' => env('\''DB_HOST'\'', '\''127.0.0.1'\''),
            '\''port'\'' => env('\''DB_PORT'\'', '\''3306'\''),
            '\''database'\'' => env('\''DB_DATABASE'\'', '\''ai_side'\''),
            '\''username'\'' => env('\''DB_USERNAME'\'', '\''root'\''),
            '\''password'\'' => env('\''DB_PASSWORD'\'', '\'''\'''),
            '\''charset'\'' => '\''utf8mb4'\'',
            '\''collation'\'' => '\''utf8mb4_unicode_ci'\'',
            '\''prefix'\'' => '\'''\''',
            '\''strict'\'' => true,
            '\''engine'\'' => null,
        ],
    ],
    '\''migrations'\'' => '\''migrations'\'',
];
ENDOFFILE

echo "✅ 创建 config/cache.php"
cat > config/cache.php << '\''ENDOFFILE'\''
<?php

return [
    '\''default'\'' => env('\''CACHE_DRIVER'\'', '\''file'\''),
    '\''stores'\'' => [
        '\''redis'\'' => [
            '\''driver'\'' => '\''redis'\'',
            '\''connection'\'' => '\''default'\'',
        ],
        '\''file'\'' => [
            '\''driver'\'' => '\''file'\'',
            '\''path'\'' => storage_path('\''framework/cache/data'\''),
        ],
    ],
    '\''prefix'\'' => env('\''CACHE_PREFIX'\'', '\''ai_side'\''),
];
ENDOFFILE

echo "✅ 创建 config/session.php"
cat > config/session.php << '\''ENDOFFILE'\''
<?php

return [
    '\''driver'\'' => env('\''SESSION_DRIVER'\'', '\''file'\''),
    '\''lifetime'\'' => env('\''SESSION_LIFETIME'\'', 120),
    '\''expire_on_close'\'' => false,
    '\''encrypt'\'' => false,
    '\''files'\'' => storage_path('\''framework/sessions'\''),
    '\''connection'\'' => env('\''SESSION_CONNECTION'\''),
    '\''table'\'' => '\''sessions'\'',
    '\''store'\'' => env('\''SESSION_STORE'\''),
    '\''lottery'\'' => [2, 100],
    '\''cookie'\'' => env('\''SESSION_COOKIE'\'', '\''ai_side_session'\''),
    '\''path'\'' => '\''/'\'',
    '\''domain'\'' => env('\''SESSION_DOMAIN'\''),
    '\''secure'\'' => env('\''SESSION_SECURE_COOKIE'\''),
    '\''http_only'\'' => true,
    '\''same_site'\'' => '\''lax'\'',
];
ENDOFFILE

echo "✅ 创建 config/view.php"
cat > config/view.php << '\''ENDOFFILE'\''
<?php

return [
    '\''paths'\'' => [resource_path('\''views'\'')],
    '\''compiled'\'' => env('\''VIEW_COMPILED_PATH'\'', realpath(storage_path('\''framework/views'\''))),
];
ENDOFFILE

echo "✅ 创建 config/mail.php"
cat > config/mail.php << '\''ENDOFFILE'\''
<?php

return [
    '\''default'\'' => env('\''MAIL_MAILER'\'', '\''smtp'\''),
    '\''mailers'\'' => [
        '\''smtp'\'' => [
            '\''transport'\'' => '\''smtp'\'',
            '\''host'\'' => env('\''MAIL_HOST'\'', '\''smtp.qq.com'\''),
            '\''port'\'' => env('\''MAIL_PORT'\'', 465),
            '\''encryption'\'' => env('\''MAIL_ENCRYPTION'\'', '\''ssl'\''),
            '\''username'\'' => env('\''MAIL_USERNAME'\''),
            '\''password'\'' => env('\''MAIL_PASSWORD'\''),
        ],
    ],
    '\''from'\'' => [
        '\''address'\'' => env('\''MAIL_FROM_ADDRESS'\'', '\''2801359160@qq.com'\''),
        '\''name'\'' => env('\''MAIL_FROM_NAME'\'', '\''AI 副业情报局'\''),
    ],
];
ENDOFFILE

echo "✅ 创建 config/auth.php"
cat > config/auth.php << '\''ENDOFFILE'\''
<?php

return [
    '\''defaults'\'' => [
        '\''guard'\'' => '\''web'\'',
        '\''passwords'\'' => '\''users'\'',
    ],
    '\''guards'\'' => [
        '\''web'\'' => [
            '\''driver'\'' => '\''session'\'',
            '\''provider'\'' => '\''users'\'',
        ],
    ],
    '\''providers'\'' => [
        '\''users'\'' => [
            '\''driver'\'' => '\''eloquent'\'',
            '\''model'\'' => App\Models\User::class,
        ],
    ],
    '\''passwords'\'' => [
        '\''users'\'' => [
            '\''provider'\'' => '\''users'\'',
            '\''table'\'' => '\''password_reset_tokens'\'',
            '\''expire'\'' => 60,
            '\''throttle'\'' => 60,
        ],
    ],
    '\''password_timeout'\'' => 10800,
];
ENDOFFILE

echo "✅ 创建 config/hashing.php"
cat > config/hashing.php << '\''ENDOFFILE'\''
<?php

return [
    '\''driver'\'' => '\''bcrypt'\'',
    '\''bcrypt'\'' => [
        '\''rounds'\'' => env('\''BCRYPT_ROUNDS'\'', 12),
        '\''verify'\'' => true,
    ],
    '\''argon'\'' => [
        '\''memory'\'' => 65536,
        '\''threads'\'' => 1,
        '\''time'\'' => 4,
        '\''verify'\'' => true,
    ],
];
ENDOFFILE

echo "✅ 创建 config/logging.php"
cat > config/logging.php << '\''ENDOFFILE'\''
<?php

return [
    '\''default'\'' => env('\''LOG_CHANNEL'\'', '\''stack'\''),
    '\''deprecations'\'' => ['\''channel'\'' => env('\''LOG_DEPRECATIONS_CHANNEL'\'', '\''null'\'')],
    '\''channels'\'' => [
        '\''stack'\'' => [
            '\''driver'\'' => '\''stack'\'',
            '\''channels'\'' => ['\''single'\''],
            '\''ignore_exceptions'\'' => false,
        ],
        '\''single'\'' => [
            '\''driver'\'' => '\''single'\'',
            '\''path'\'' => storage_path('\''logs/laravel.log'\''),
            '\''level'\'' => env('\''LOG_LEVEL'\'', '\''debug'\''),
        ],
        '\''daily'\'' => [
            '\''driver'\'' => '\''daily'\'',
            '\''path'\'' => storage_path('\''logs/laravel.log'\''),
            '\''level'\'' => env('\''LOG_LEVEL'\'', '\''debug'\''),
            '\''days'\'' => 14,
        ],
    ],
];
ENDOFFILE

echo "✅ 创建 config/queue.php"
cat > config/queue.php << '\''ENDOFFILE'\''
<?php

return [
    '\''default'\'' => env('\''QUEUE_CONNECTION'\'', '\''sync'\''),
    '\''connections'\'' => [
        '\''sync'\'' => [
            '\''driver'\'' => '\''sync'\'',
        ],
        '\''redis'\'' => [
            '\''driver'\'' => '\''redis'\'',
            '\''connection'\'' => '\''default'\'',
            '\''queue'\'' => env('\''REDIS_QUEUE'\'', '\''default'\''),
        ],
    ],
    '\''batching'\'' => [
        '\''database'\'' => env('\''DB_CONNECTION'\'', '\''mysql'\''),
        '\''table'\'' => '\''job_batches'\'',
    ],
    '\''failed'\'' => [
        '\''driver'\'' => env('\''QUEUE_FAILED_DRIVER'\'', '\''database-uuids'\''),
        '\''database'\'' => env('\''DB_CONNECTION'\'', '\''mysql'\''),
        '\''table'\'' => '\''failed_jobs'\'',
    ],
];
ENDOFFILE

echo "✅ 创建 config/services.php"
cat > config/services.php << '\''ENDOFFILE'\''
<?php

return [
    '\''mailgun'\'' => [
        '\''domain'\'' => env('\''MAILGUN_DOMAIN'\''),
        '\''secret'\'' => env('\''MAILGUN_SECRET'\''),
        '\''endpoint'\'' => env('\''MAILGUN_ENDPOINT'\'', '\''api.mailgun.net'\''),
    ],
    '\''postmark'\'' => [
        '\''token'\'' => env('\''POSTMARK_TOKEN'\''),
    ],
    '\''ses'\'' => [
        '\''key'\'' => env('\''AWS_ACCESS_KEY_ID'\''),
        '\''secret'\'' => env('\''AWS_SECRET_ACCESS_KEY'\''),
        '\''region'\'' => env('\''AWS_DEFAULT_REGION'\'', '\''us-east-1'\''),
    ],
];
ENDOFFILE

echo "✅ 创建 config/cors.php"
cat > config/cors.php << '\''ENDOFFILE'\''
<?php

return [
    '\''paths'\'' => ['\''api/*'\'', '\''sanctum/csrf-cookie'\''],
    '\''allowed_methods'\'' => ['\''*'\'''],
    '\''allowed_origins'\'' => ['\''*'\'''],
    '\''allowed_origins_patterns'\'' => [],
    '\''allowed_headers'\'' => ['\''*'\'''],
    '\''exposed_headers'\'' => [],
    '\''max_age'\'' => 0,
    '\''supports_credentials'\'' => false,
];
ENDOFFILE

echo ""
echo "🎉 所有配置文件创建完成！"
'

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host " ✅ 容器内文件创建完成！" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "现在执行初始化命令：" -ForegroundColor Cyan
Write-Host ""

# 执行初始化命令
docker-compose exec php php artisan config:clear
docker-compose exec php php artisan cache:clear
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan make:filament-user

Write-Host ""
Write-Host "🎉 初始化完成！" -ForegroundColor Green
Write-Host "访问：http://localhost:8081/admin" -ForegroundColor Cyan
Write-Host ""
