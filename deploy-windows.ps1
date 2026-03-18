# AI 副业情报局 - Windows 一键部署脚本
# 在 PowerShell（管理员）中运行

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " AI 副业情报局 - 一键部署" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 检查 Docker
Write-Host "[1/5] 检查 Docker..." -ForegroundColor Yellow
if (!(Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Docker 未安装！请先安装 Docker Desktop" -ForegroundColor Red
    exit 1
}
Write-Host "✅ Docker 已安装" -ForegroundColor Green
Write-Host ""

# 进入项目目录
$projectDir = "D:\lewan\ai-side-laravel"
Write-Host "[2/5] 进入项目目录：$projectDir" -ForegroundColor Yellow
Set-Location $projectDir

# 复制环境变量文件
Write-Host "[3/5] 配置环境变量..." -ForegroundColor Yellow
if (!(Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "✅ .env 文件已创建" -ForegroundColor Green
} else {
    Write-Host "✅ .env 文件已存在" -ForegroundColor Green
}
Write-Host ""

# 启动 Docker 容器
Write-Host "[4/5] 启动 Docker 容器..." -ForegroundColor Yellow
docker-compose up -d

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ 容器启动失败！" -ForegroundColor Red
    docker-compose logs
    exit 1
}
Write-Host "✅ 容器启动成功" -ForegroundColor Green
Write-Host ""

# 等待服务启动
Write-Host "[5/5] 等待服务启动（30 秒）..." -ForegroundColor Yellow
Start-Sleep -Seconds 30

# 初始化应用
Write-Host "初始化数据库..." -ForegroundColor Yellow
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host " 🎉 部署完成！" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "📱 访问地址:" -ForegroundColor Cyan
Write-Host "   前台：http://localhost:8081" -ForegroundColor White
Write-Host "   后台：http://localhost:8081/admin" -ForegroundColor White
Write-Host ""
Write-Host "🔧 创建管理员账号:" -ForegroundColor Cyan
Write-Host "   docker-compose exec php php artisan make:filament-user" -ForegroundColor White
Write-Host ""
Write-Host "📊 查看日志:" -ForegroundColor Cyan
Write-Host "   docker-compose logs -f" -ForegroundColor White
Write-Host ""
Write-Host "🛑 停止服务:" -ForegroundColor Cyan
Write-Host "   docker-compose down" -ForegroundColor White
Write-Host ""
