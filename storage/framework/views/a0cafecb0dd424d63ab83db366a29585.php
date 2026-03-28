

<?php $__env->startSection('title', $knowledgeBase->title . ' - 知识库'); ?>

<?php $__env->startSection('content'); ?>
<section style="padding: 48px 0 24px; background: linear-gradient(135deg, rgba(99,102,241,0.12) 0%, rgba(139,92,246,0.08) 100%); border-bottom: 1px solid rgba(255,255,255,0.08);">
    <div class="container">
        <nav style="font-size: 14px; color: var(--gray-light); margin-bottom: 16px;">
            <a href="<?php echo e(route('knowledge.index')); ?>" style="color: var(--primary-light); text-decoration: none;">知识库</a>
            <span style="margin: 0 8px;">/</span>
            <span style="color: var(--white);"><?php echo e($knowledgeBase->title); ?></span>
        </nav>
        <h1 style="font-size: 32px; font-weight: 800; margin: 0 0 12px; color: var(--white); line-height: 1.25;">
            <?php echo e($knowledgeBase->title); ?>

        </h1>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($knowledgeBase->description)): ?>
            <p style="color: var(--gray-light); font-size: 16px; line-height: 1.7; margin: 0; max-width: 800px;">
                <?php echo e($knowledgeBase->description); ?>

            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div style="margin-top: 16px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center; font-size: 13px; color: var(--gray-light);">
            <span>维护者：<?php echo e($knowledgeBase->user?->name ?? '—'); ?></span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($knowledgeBase->is_vip_only): ?>
                <span style="padding: 2px 10px; border-radius: 8px; background: rgba(251, 191, 36, 0.2); color: #fbbf24; font-weight: 700;">VIP 专享</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<section style="padding: 40px 0 80px;">
    <div class="container">
        <h2 style="font-size: 20px; font-weight: 700; color: var(--white); margin: 0 0 20px;">
            文档列表
            <span style="font-size: 14px; font-weight: 500; color: var(--gray-light);">（共 <?php echo e($documents->total()); ?> 篇）</span>
        </h2>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('knowledge.documents.show', $doc)); ?>" class="card" style="display: block; padding: 20px 22px; margin-bottom: 14px; text-decoration: none; color: inherit; border: 1px solid rgba(255,255,255,0.08); transition: transform 0.2s, box-shadow 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 36px rgba(99,102,241,0.18)'"
               onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                    <div style="min-width: 0;">
                        <h3 style="font-size: 17px; font-weight: 700; margin: 0 0 8px; color: var(--white);"><?php echo e($doc->title); ?></h3>
                        <p style="color: var(--gray-light); font-size: 13px; line-height: 1.6; margin: 0;">
                            <?php echo e(\Illuminate\Support\Str::limit(strip_tags($doc->content), 160)); ?>

                        </p>
                    </div>
                    <div style="flex-shrink: 0; text-align: right; font-size: 12px; color: var(--gray-light);">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($doc->file_type): ?>
                            <span style="display: inline-block; padding: 2px 8px; border-radius: 6px; background: rgba(99,102,241,0.15); color: var(--primary-light); margin-bottom: 6px;"><?php echo e(strtoupper($doc->file_type)); ?></span><br>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <span>👁 <?php echo e(number_format($doc->view_count ?? 0)); ?></span>
                        <span style="margin-left: 12px;"><?php echo e($doc->updated_at?->diffForHumans() ?? ''); ?></span>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card" style="padding: 48px; text-align: center; color: var(--gray-light);">
                <p style="margin: 0;">该知识库下暂无文档。</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($documents->hasPages()): ?>
            <div style="margin-top: 28px;">
                <?php echo e($documents->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/knowledge/show.blade.php ENDPATH**/ ?>