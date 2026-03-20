#!/bin/bash
# AI 副业情报局 - WSL 环境一键安装脚本
# 复制粘贴到 WSL 终端执行

echo "🚀 开始安装 MySQL 和配置 Laravel..."

# 1. 安装 MySQL
echo "📦 安装 MySQL..."
sudo apt install -y mysql-server

# 2. 配置 MySQL 允许远程连接
echo "🔧 配置 MySQL..."
sudo sed -i 's/bind-address = 127.0.0.1/bind-address = 0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf

# 3. 启动 MySQL
echo "🚀 启动 MySQL 服务..."
sudo service mysql start

# 3. 创建数据库和用户
echo "🗄️ 创建数据库..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS ai_side CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'ai_side'@'localhost' IDENTIFIED BY 'aiside123456';"
sudo mysql -e "GRANT ALL PRIVILEGES ON ai_side.* TO 'ai_side'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# 4. 进入项目目录
cd /mnt/d/lewan/openclaw-data/workspace/ai-side-laravel

# 5. 修改 .env 配置
echo "⚙️ 修改配置..."
sed -i 's/DB_HOST=.*/DB_HOST=localhost/' .env
sed -i 's/CACHE_DRIVER=.*/CACHE_DRIVER=file/' .env
sed -i 's/SESSION_DRIVER=.*/SESSION_DRIVER=file/' .env
sed -i 's/QUEUE_CONNECTION=.*/QUEUE_CONNECTION=sync/' .env
sed -i 's/APP_URL=.*/APP_URL=http:\/\/localhost:8081/' .env

# 6. 清除缓存
echo "🧹 清除缓存..."
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear

# 7. 运行迁移（如果表不存在）
echo "📋 运行数据库迁移..."
php artisan migrate --force

# 8. 启动 Laravel
echo "🚀 启动 Laravel 开发服务器..."
echo ""
echo "✅ 完成！访问地址："
echo "   前台：http://localhost:8081"
echo "   后台：http://localhost:8081/admin/login"
echo ""
echo "管理员账号："
echo "   邮箱：2801359160@qq.com"
echo ""
php artisan serve --host=0.0.0.0 --port=8081
