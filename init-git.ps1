# AI 副业情报局 - Git 初始化与推送脚本 (Windows PowerShell)
# 在 PowerShell（管理员）中运行

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " AI 副业情报局 - Git 初始化" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 检查 Git
Write-Host "[1/4] 检查 Git..." -ForegroundColor Yellow
if (!(Get-Command git -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Git 未安装！" -ForegroundColor Red
    Write-Host "请访问 https://git-scm.com/download/win 下载安装" -ForegroundColor Yellow
    exit 1
}
Write-Host "✅ Git 已安装" -ForegroundColor Green
Write-Host ""

# 进入项目目录
$projectDir = "D:\lewan\ai-side-laravel"
Write-Host "[2/4] 进入项目目录：$projectDir" -ForegroundColor Yellow
Set-Location $projectDir

# 初始化 Git 仓库
Write-Host "[3/4] 初始化 Git 仓库..." -ForegroundColor Yellow
git init

# 创建 .gitignore
Write-Host "📝 创建 .gitignore..." -ForegroundColor Yellow
$gitignore = @"
# Laravel
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

# IDE
.idea/
.vscode/
*.swp
*.swo
*~

# OS
.DS_Store
Thumbs.db

# Docker
docker-compose.override.yml
"@

$gitignore | Out-File -FilePath ".gitignore" -Encoding utf8
Write-Host "✅ .gitignore 已创建" -ForegroundColor Green
Write-Host ""

# 添加所有文件
Write-Host "[4/4] 添加文件到 Git..." -ForegroundColor Yellow
git add .

# 首次提交
Write-Host "💾 创建首次提交..." -ForegroundColor Yellow
git commit -m "Initial commit: AI 副业情报局 Laravel 项目

- Laravel 10.x + PHP 8.2 + Filament v3
- Docker 容器化部署
- 完整的数据库迁移和模型
- Filament 后台管理（用户/文章/项目）
- GitHub 数据收集服务
- 单元测试和功能测试
- 详细的部署文档"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host " ✅ Git 仓库初始化完成！" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "📋 下一步操作：" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. 在 GitHub 创建新仓库" -ForegroundColor White
Write-Host "   访问：https://github.com/new" -ForegroundColor Gray
Write-Host "   仓库名：ai-side-laravel" -ForegroundColor Gray
Write-Host "   选择 Private（推荐）或 Public" -ForegroundColor Gray
Write-Host ""
Write-Host "2. 添加远程仓库（替换 YOUR_USERNAME 为你的 GitHub 用户名）" -ForegroundColor White
Write-Host "   git remote add origin https://github.com/YOUR_USERNAME/ai-side-laravel.git" -ForegroundColor Gray
Write-Host ""
Write-Host "3. 推送到 GitHub" -ForegroundColor White
Write-Host "   git branch -M main" -ForegroundColor Gray
Write-Host "   git push -u origin main" -ForegroundColor Gray
Write-Host ""
Write-Host "4. 部署项目（参考 DEPLOY.md）" -ForegroundColor White
Write-Host ""
