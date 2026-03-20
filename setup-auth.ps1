# AI 副业情报局 - 用户系统开发脚本
# 在 Windows PowerShell 中执行

Write-Host " 开始创建用户认证系统..." -ForegroundColor Green

# 1. 创建控制器目录
docker-compose exec php mkdir -p app/Http/Controllers/Auth

# 2. 创建 HomeController
docker-compose exec php php artisan make:controller HomeController

# 3. 创建 Auth 控制器
docker-compose exec php php artisan make:controller Auth/LoginController
docker-compose exec php php artisan make:controller Auth/RegisterController
docker-compose exec php php artisan make:controller ProjectController
docker-compose exec php php artisan make:controller ArticleController

Write-Host "✅ 控制器创建完成！" -ForegroundColor Green

# 4. 创建视图目录
docker-compose exec php mkdir -p resources/views/home
docker-compose exec php mkdir -p resources/views/auth
docker-compose exec php mkdir -p resources/views/projects
docker-compose exec php mkdir -p resources/views/articles
docker-compose exec php mkdir -p resources/views/layouts

Write-Host "✅ 视图目录创建完成！" -ForegroundColor Green

# 5. 创建管理员账号命令
Write-Host "`n📝 创建管理员账号，请执行：" -ForegroundColor Yellow
Write-Host "docker-compose exec php php artisan make:filament-user" -ForegroundColor Cyan

Write-Host "`n🎉 完成！接下来创建视图文件..." -ForegroundColor Green
