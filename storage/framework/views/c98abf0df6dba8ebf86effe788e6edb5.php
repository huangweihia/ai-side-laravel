

<?php $__env->startSection('title', '申请列表 - ' . $job->title); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 900px; margin: 0 auto; padding: 40px 20px;">
    <div style="margin-bottom: 24px;">
        <a href="<?php echo e(route('my.jobs.index')); ?>" style="color: var(--primary-light); text-decoration: none; font-weight: 600;">← 返回我发布的职位</a>
    </div>

    <h1 style="font-size: 22px; font-weight: 800; color: var(--white); margin: 0 0 8px;">📩 申请列表</h1>
    <p style="color: var(--gray-light); font-size: 15px; margin: 0 0 24px;">
        <span style="color: var(--primary-light); font-weight: 600;"><?php echo e($job->title); ?></span>
        <span style="margin: 0 8px;">·</span>
        <?php echo e($job->company_name); ?>

    </p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($applications->count()): ?>
        <div style="display: grid; gap: 14px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card" style="padding: 18px 20px; border: 1px solid rgba(255,255,255,0.08);">
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; gap: 12px; margin-bottom: 10px;">
                        <div>
                            <a href="<?php echo e(route('users.show', $app->applicant)); ?>" style="font-weight: 700; color: var(--white); text-decoration: none;"><?php echo e($app->applicant->name ?? '用户'); ?></a>
                            <span style="font-size: 13px; color: var(--gray-light); margin-left: 10px;"><?php echo e($app->applicant->email ?? ''); ?></span>
                        </div>
                        <span style="font-size: 12px; color: var(--gray);"><?php echo e($app->created_at->format('Y-m-d H:i')); ?></span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($app->message)): ?>
                        <div style="padding: 12px 14px; background: rgba(99,102,241,0.08); border-radius: 10px; font-size: 14px; color: var(--gray-light); line-height: 1.6; white-space: pre-wrap;"><?php echo e($app->message); ?></div>
                    <?php else: ?>
                        <div style="font-size: 13px; color: var(--gray);">（无附言）</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div style="margin-top: 24px;"><?php echo e($applications->links()); ?></div>
    <?php else: ?>
        <div class="card" style="padding: 40px; text-align: center; color: var(--gray-light);">
            暂无申请记录
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/jobs/my-applications.blade.php ENDPATH**/ ?>