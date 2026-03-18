#!/usr/bin/env php
<?php
/**
 * Laravel 核心文件创建脚本
 */

$root = __DIR__;

// 创建目录结构
$dirs = [
    'bootstrap/cache',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'routes',
    'public',
    'app/Http',
    'app/Providers',
    'config',
];

foreach ($dirs as $dir) {
    if (!is_dir("$root/$dir")) {
        mkdir("$root/$dir", 0755, true);
        echo "✅ Created directory: $dir\n";
    }
}

// 创建 artisan 文件
file_put_contents("$root/artisan", <<<'PHP'
#!/usr/bin/env php
<?php

define('LARAVEL_START', microtime(true()));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

$kernel->terminate($input, $status);

exit($status);
PHP
);
chmod("$root/artisan", 0755);
echo "✅ Created artisan\n";

// 创建 bootstrap/app.php
file_put_contents("$root/bootstrap/app.php", <<<'PHP'
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
PHP
);
echo "✅ Created bootstrap/app.php\n";

// 创建路由文件
file_put_contents("$root/routes/web.php", <<<'PHP'
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
PHP
);
echo "✅ Created routes/web.php\n";

file_put_contents("$root/routes/api.php", <<<'PHP'
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
PHP
);
echo "✅ Created routes/api.php\n";

file_put_contents("$root/routes/console.php", <<<'PHP'
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
PHP
);
echo "✅ Created routes/console.php\n";

// 创建 public/index.php
file_put_contents("$root/public/index.php", <<<'PHP'
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true()));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
PHP
);
echo "✅ Created public/index.php\n";

// 创建 .gitignore
file_put_contents("$root/.gitignore", <<<'GITIGNORE'
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
.idea/
.vscode/
*.swp
*.swo
*~
.DS_Store
Thumbs.db
docker-compose.override.yml
GITIGNORE
);
echo "✅ Created .gitignore\n";

echo "\n🎉 Laravel core files created successfully!\n";
echo "Now run: composer install\n";
