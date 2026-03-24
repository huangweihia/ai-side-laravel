<?php $__env->startSection('title', '订阅偏好 - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 700px; margin: 60px auto;">
    <div class="card" style="padding: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 48px; margin-bottom: 16px;">📧</div>
            <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 8px;">邮件订阅偏好</h1>
            <p style="color: var(--gray-light); font-size: 15px;">
                管理你希望接收的邮件类型
            </p>
        </div>

        <form method="POST" action="<?php echo e(route('subscriptions.update')); ?>">
            <?php echo csrf_field(); ?>
            
            <div style="display: grid; gap: 16px; margin-bottom: 30px;">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background: rgba(255,255,255,0.05); border-radius: 12px;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                            <span style="font-size: 24px;">📅</span>
                            <div>
                                <div style="font-weight: 600; font-size: 16px;">每日资讯日报</div>
                                <div style="font-size: 13px; color: var(--gray-light);">每天早上 10:00 发送，包含 AI 项目、副业灵感、学习资源</div>
                            </div>
                        </div>
                    </div>
                    <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                        <input type="checkbox" name="subscribed_to_daily" value="1" <?php echo e($subscription->subscribed_to_daily ? 'checked' : ''); ?> style="opacity: 0; width: 0; height: 0;">
                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #334155; transition: .4s; border-radius: 34px;">
                            <span style="position: absolute; content: ''; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                        </span>
                    </label>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background: rgba(255,255,255,0.05); border-radius: 12px;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                            <span style="font-size: 24px;">📊</span>
                            <div>
                                <div style="font-weight: 600; font-size: 16px;">每周精选汇总</div>
                                <div style="font-size: 13px; color: var(--gray-light);">每周一发送，汇总上周最优质内容</div>
                            </div>
                        </div>
                    </div>
                    <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                        <input type="checkbox" name="subscribed_to_weekly" value="1" <?php echo e($subscription->subscribed_to_weekly ? 'checked' : ''); ?> style="opacity: 0; width: 0; height: 0;">
                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #334155; transition: .4s; border-radius: 34px;">
                            <span style="position: absolute; content: ''; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                        </span>
                    </label>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background: rgba(255,255,255,0.05); border-radius: 12px;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                            <span style="font-size: 24px;">🔔</span>
                            <div>
                                <div style="font-weight: 600; font-size: 16px;">系统通知</div>
                                <div style="font-size: 13px; color: var(--gray-light);">账户安全、订阅到期等重要通知</div>
                            </div>
                        </div>
                    </div>
                    <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                        <input type="checkbox" name="subscribed_to_notifications" value="1" <?php echo e($subscription->subscribed_to_notifications ? 'checked' : ''); ?> style="opacity: 0; width: 0; height: 0;">
                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #334155; transition: .4s; border-radius: 34px;">
                            <span style="position: absolute; content: ''; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                        </span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px;">
                💾 保存偏好设置
            </button>
        </form>

        <div style="text-align: center; margin-top: 30px; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1);">
            <p style="color: var(--gray-light); font-size: 14px; margin-bottom: 16px;">
                订阅邮箱：<strong><?php echo e($subscription->email); ?></strong>
            </p>
            <a href="<?php echo e(route('unsubscribe.show', $subscription->unsubscribe_token)); ?>" style="color: #ef4444; text-decoration: none; font-size: 14px;">
                ❌ 退订所有邮件
            </a>
        </div>
    </div>
</div>

<style>
input:checked + span {
    background-color: #6366f1 !important;
}
input:checked + span span {
    transform: translateX(26px);
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/subscriptions/preferences.blade.php ENDPATH**/ ?>