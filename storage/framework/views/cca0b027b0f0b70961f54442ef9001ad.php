<?php $__env->startSection('title', '退订邮件 - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 600px; margin: 60px auto;">
    <div class="card" style="padding: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 64px; margin-bottom: 16px;">📧</div>
            <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 16px;">管理邮件订阅</h1>
            <p style="color: var(--gray-light); font-size: 15px;">
                订阅邮箱：<strong><?php echo e($subscription->email); ?></strong>
            </p>
        </div>

        <form method="POST" action="<?php echo e(route('unsubscribe.confirm', $subscription->unsubscribe_token)); ?>">
            <?php echo csrf_field(); ?>
            
            <div style="margin-bottom: 30px;">
                <p style="text-align: center; color: var(--gray-light); margin-bottom: 20px;">
                    请选择要退订的邮件类型：
                </p>
                
                <div style="display: grid; gap: 12px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subscription->subscribed_to_daily): ?>
                        <label style="display: flex; align-items: center; gap: 12px; padding: 16px; background: rgba(255,255,255,0.05); border-radius: 8px; cursor: pointer;">
                            <input type="radio" name="type" value="daily" style="width: 20px; height: 20px;">
                            <div>
                                <div style="font-weight: 600;">📅 每日资讯日报</div>
                                <div style="font-size: 13px; color: var(--gray-light);">每天早上 10:00 发送的 AI 副业资讯</div>
                            </div>
                        </label>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subscription->subscribed_to_weekly): ?>
                        <label style="display: flex; align-items: center; gap: 12px; padding: 16px; background: rgba(255,255,255,0.05); border-radius: 8px; cursor: pointer;">
                            <input type="radio" name="type" value="weekly" style="width: 20px; height: 20px;">
                            <div>
                                <div style="font-weight: 600;">📊 每周精选汇总</div>
                                <div style="font-size: 13px; color: var(--gray-light);">每周一发送的上周内容汇总</div>
                            </div>
                        </label>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subscription->subscribed_to_notifications): ?>
                        <label style="display: flex; align-items: center; gap: 12px; padding: 16px; background: rgba(255,255,255,0.05); border-radius: 8px; cursor: pointer;">
                            <input type="radio" name="type" value="notifications" style="width: 20px; height: 20px;">
                            <div>
                                <div style="font-weight: 600;">🔔 系统通知</div>
                                <div style="font-size: 13px; color: var(--gray-light);">账户相关的重要通知</div>
                            </div>
                        </label>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <label style="display: flex; align-items: center; gap: 12px; padding: 16px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; cursor: pointer;">
                        <input type="radio" name="type" value="all" checked style="width: 20px; height: 20px;">
                        <div>
                            <div style="font-weight: 600; color: #ef4444;">❌ 全部退订</div>
                            <div style="font-size: 13px; color: var(--gray-light);">不再接收任何邮件</div>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; background: #ef4444;">
                确认退订
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1);">
            <p style="color: var(--gray-light); font-size: 14px;">
                改变主意了？
                <a href="<?php echo e(route('resubscribe', $subscription->unsubscribe_token)); ?>" style="color: var(--primary-light); text-decoration: none; font-weight: 600;">重新订阅</a>
            </p>
            <p style="color: var(--gray-light); font-size: 13px; margin-top: 16px;">
                登录后可以在
                <a href="<?php echo e(route('subscriptions.preferences')); ?>" style="color: var(--primary-light); text-decoration: none;">订阅偏好设置</a>
                中管理订阅
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/subscriptions/unsubscribe.blade.php ENDPATH**/ ?>