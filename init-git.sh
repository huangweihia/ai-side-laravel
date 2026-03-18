#!/bin/bash
# AI 副业情报局 - Git 初始化与推送脚本

echo "🚀 AI 副业情报局 - Git 初始化"
echo "================================"

# 检查 Git
if ! command -v git &> /dev/null; then
    echo "❌ Git 未安装，请先安装 Git"
    exit 1
fi

cd "$(dirname "$0")"

# 初始化 Git 仓库
echo "📦 初始化 Git 仓库..."
git init

# 创建 .gitignore
cat > .gitignore << 'EOF'
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
EOF

echo "✅ .gitignore 已创建"

# 添加所有文件
echo "📝 添加文件到 Git..."
git add .

# 首次提交
echo "💾 创建首次提交..."
git commit -m "Initial commit: AI 副业情报局 Laravel 项目

- Laravel 10.x + PHP 8.2 + Filament v3
- Docker 容器化部署
- 完整的数据库迁移和模型
- Filament 后台管理（用户/文章/项目）
- GitHub 数据收集服务
- 单元测试和功能测试
- 详细的部署文档

技术栈:
- Backend: Laravel, Filament, MySQL, Redis
- Testing: PHPUnit, Dusk
- Deployment: Docker, Docker Compose
"

# 显示远程仓库设置提示
echo ""
echo "================================"
echo "✅ Git 仓库初始化完成！"
echo "================================"
echo ""
echo "📋 下一步操作："
echo ""
echo "1. 在 GitHub 创建新仓库"
echo "   访问：https://github.com/new"
echo "   仓库名：ai-side-laravel"
echo "   选择 Private（推荐）或 Public"
echo ""
echo "2. 添加远程仓库（替换 YOUR_USERNAME）"
echo "   git remote add origin https://github.com/YOUR_USERNAME/ai-side-laravel.git"
echo ""
echo "3. 推送到 GitHub"
echo "   git branch -M main"
echo "   git push -u origin main"
echo ""
echo "4. 部署项目（参考 DEPLOY.md）"
echo ""
