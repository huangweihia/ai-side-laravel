#!/bin/bash

# 邮件系统更新 - 快速部署脚本
# 用法：./deploy-email-update.sh

set -e

echo "🚀 开始部署邮件系统更新..."
echo ""

# 进入项目目录
cd /home/node/.openclaw/workspace/ai-side-laravel

# 1. 检查数据库连接
echo "📊 检查数据库连接..."
if mysqladmin ping -h localhost -u ai_side -paiside123456 &>/dev/null; then
    echo "✅ 数据库连接正常"
else
    echo "❌ 数据库连接失败，尝试启动 MariaDB..."
    service mariadb start || {
        echo "⚠️  无法启动数据库，请手动启动后重新运行此脚本"
        exit 1
    }
    sleep 3
fi

# 2. 运行迁移
echo ""
echo "📦 运行数据库迁移..."
php artisan migrate --force || {
    echo "⚠️  迁移失败，请检查数据库配置"
    exit 1
}
echo "✅ 迁移完成"

# 3. 运行 Seeder
echo ""
echo "🌱 初始化默认数据..."
php artisan db:seed --class=EmailConfigSeeder --force || {
    echo "⚠️  Seeder 执行失败"
    exit 1
}
echo "✅ 数据初始化完成"

# 4. 清理缓存
echo ""
echo "🧹 清理缓存..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo "✅ 缓存已清理"

# 5. 完成
echo ""
echo "=========================================="
echo "✅ 部署完成！"
echo "=========================================="
echo ""
echo "📋 新增功能："
echo "   - 用户订阅管理（退订/偏好设置）"
echo "   - SMTP 配置管理后台"
echo "   - 邮件模板管理后台"
echo "   - 批量导入/导出收件人"
echo ""
echo "🔗 访问路径："
echo "   - 后台首页：/admin"
echo "   - SMTP 配置：/admin/smtp-configs"
echo "   - 邮件模板：/admin/email-templates"
echo "   - 邮件管理：/admin/email-manager"
echo "   - 订阅偏好：/subscriptions/preferences"
echo ""
echo "📧 测试建议："
echo "   1. 注册新用户，检查是否收到欢迎邮件"
echo "   2. 后台发送测试邮件验证 SMTP 配置"
echo "   3. 点击邮件退订链接测试退订流程"
echo ""
