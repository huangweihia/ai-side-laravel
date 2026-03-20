# AI 副业情报局 - 全自动化初始化脚本

Write-Host ""
Write-Host "AI 副业情报局 - 全自动化配置" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Green
Write-Host ""

Write-Host "关闭旧 WSL 实例..." -ForegroundColor Cyan
wsl --shutdown
Start-Sleep -Seconds 2

Write-Host "获取 WSL 用户..." -ForegroundColor Cyan
$wslUser = (wsl bash -c "whoami" 2>$null).Trim()
if (-not $wslUser) {
    Write-Host "无法获取 WSL 用户" -ForegroundColor Red
    Read-Host "按回车退出"
    exit 1
}
Write-Host "用户：$wslUser" -ForegroundColor Green

$projectPath = "/home/" + $wslUser + "/ai-side-laravel"
Write-Host "项目路径：$projectPath" -ForegroundColor Cyan

Write-Host "复制项目到 WSL..." -ForegroundColor Cyan
$script = @"
if [ ! -d ${projectPath} ]; then
    mkdir -p ${projectPath}
    rsync -av --exclude vendor /mnt/d/lewan/openclaw-data/workspace/ai-side-laravel/ ${projectPath}/
    chown -R ${wslUser}:${wslUser} ${projectPath}
fi
"@
wsl bash -c $script

Write-Host "启动 MySQL..." -ForegroundColor Cyan
wsl bash -c "sudo service mysql start" 2>$null
Start-Sleep -Seconds 2

Write-Host "安装依赖..." -ForegroundColor Cyan
$script = @"
cd ${projectPath}
if [ ! -d vendor ]; then
    composer install --optimize-autoloader --no-interaction
fi
"@
wsl bash -c $script

Write-Host "修改配置..." -ForegroundColor Cyan
wsl bash -c "cd ${projectPath}; sed -i 's/DB_HOST=.*/DB_HOST=127.0.0.1/' .env"

Write-Host "清除缓存..." -ForegroundColor Cyan
wsl bash -c "cd ${projectPath}; php artisan optimize:clear"

Write-Host "停止旧服务..." -ForegroundColor Cyan
wsl bash -c "pkill -9 -f php; exit 0"

Write-Host "启动 Laravel 服务..." -ForegroundColor Cyan
$script = @"
cd ${projectPath}
nohup php artisan serve --host=0.0.0.0 --port=8081 > /tmp/laravel.log 2>&1 &
"@
wsl bash -c $script
Start-Sleep -Seconds 3

Write-Host ""
Write-Host "测试响应速度..." -ForegroundColor Cyan
$response = wsl bash -c "curl -w '%{time_total}' -o /dev/null -s http://localhost:8081"
if ($response) {
    Write-Host "响应时间：${response}秒" -ForegroundColor Green
}

Write-Host ""
Write-Host "================================" -ForegroundColor Green
Write-Host "配置完成！" -ForegroundColor Green
Write-Host ""
Write-Host "访问地址：http://localhost:8081/admin/login" -ForegroundColor Cyan
Write-Host ""

Read-Host "按回车关闭"
