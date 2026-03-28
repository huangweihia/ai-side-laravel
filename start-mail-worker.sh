#!/bin/bash

echo "🚀 启动邮件自动化系统..."

# 启动队列消费者（后台运行）
echo "📬 启动队列消费者..."
php artisan queue:work --queue=emails --sleep=3 --tries=3 --max-time=3600 &
QUEUE_PID=$!

# 启动定时任务调度器（后台运行）
echo "⏰ 启动定时任务调度器..."
php artisan schedule:work &
SCHEDULER_PID=$!

echo "✅ 邮件自动化系统已启动"
echo "   - 队列消费者 PID: $QUEUE_PID"
echo "   - 调度器 PID: $SCHEDULER_PID"
echo ""
echo "📝 日志查看："
echo "   tail -f storage/logs/laravel.log"
echo ""
echo "🛑 停止服务："
echo "   kill $QUEUE_PID $SCHEDULER_PID"

# 等待进程
wait
