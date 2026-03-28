

<?php $__env->startSection('title', $announcement->title . ' - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>
<section style="padding: 100px 0 60px;">
    <div class="container" style="max-width: 800px;">
        <h1 style="font-size: 28px; font-weight: 800; margin-bottom: 16px;"><?php echo e($announcement->title); ?></h1>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($announcement->published_at): ?>
            <p style="color: var(--gray-light); font-size: 14px; margin-bottom: 28px;">
                <?php echo e($announcement->published_at->format('Y-m-d H:i')); ?>

            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="card" style="padding: 28px; line-height: 1.75;">
            <?php echo $announcement->body ?: '<p>暂无正文</p>'; ?>

        </div>
        <p style="margin-top: 24px;">
            <a href="<?php echo e(route('home')); ?>" class="navbar-link">← 返回首页</a>
        </p>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/announcements/show.blade.php ENDPATH**/ ?>