

<?php $__env->startSection('title', '知识库 - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>
<section style="padding: 60px 0 24px; background: linear-gradient(135deg, rgba(99,102,241,0.12) 0%, rgba(139,92,246,0.08) 100%); border-bottom: 1px solid rgba(255,255,255,0.08);">
    <div class="container">
        <h1 style="font-size: 36px; font-weight: 800; margin: 0 0 12px; background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            📚 知识库
        </h1>
        <p style="color: var(--gray-light); font-size: 16px; max-width: 640px; margin: 0;">
            浏览公开知识库与文档；更多检索与高级功能持续完善中。
        </p>
    </div>
</section>

<section style="padding: 40px 0 80px;">
    <div class="container">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="alert alert-error" style="margin-bottom: 24px;"><?php echo e(session('error')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('info')): ?>
            <div class="alert alert-success" style="margin-bottom: 24px;"><?php echo e(session('info')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('knowledge.show', $kb)); ?>" class="card" style="display: block; padding: 24px; margin-bottom: 16px; text-decoration: none; color: inherit; transition: transform 0.2s, box-shadow 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 40px rgba(99,102,241,0.15)'"
               onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                    <div style="min-width: 0;">
                        <h2 style="font-size: 20px; font-weight: 700; margin: 0 0 8px; color: var(--white);"><?php echo e($kb->title); ?></h2>
                        <p style="color: var(--gray-light); font-size: 14px; line-height: 1.6; margin: 0;">
                            <?php echo e(\Illuminate\Support\Str::limit($kb->description ?? '暂无简介', 200)); ?>

                        </p>
                    </div>
                    <div style="text-align: right; flex-shrink: 0;">
                        <span style="font-size: 12px; color: var(--gray-light);">文档 <?php echo e($kb->documents_count ?? 0); ?> 篇</span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kb->is_vip_only): ?>
                            <span style="display: inline-block; margin-left: 8px; padding: 2px 8px; border-radius: 8px; background: rgba(251, 191, 36, 0.2); color: #fbbf24; font-size: 11px; font-weight: 700;">VIP</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card" style="padding: 48px; text-align: center; color: var(--gray-light);">
                <div style="font-size: 48px; margin-bottom: 12px;">📭</div>
                <p style="margin: 0;">暂无公开知识库，请稍后再来。</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bases->hasPages()): ?>
            <div style="margin-top: 32px;">
                <?php echo e($bases->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/knowledge/index.blade.php ENDPATH**/ ?>