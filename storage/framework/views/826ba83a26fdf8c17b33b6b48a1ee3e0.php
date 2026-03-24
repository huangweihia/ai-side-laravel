<div style="padding: 20px;">
    <h3 style="margin-bottom: 16px; font-size: 16px; font-weight: 600;">📧 <?php echo e($template->subject); ?></h3>
    
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 16px;">
        <h4 style="margin: 0 0 12px 0; font-size: 14px; color: #64748b;">可用变量：</h4>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ($template->variables ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span style="background: #e0e7ff; color: #4338ca; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-family: monospace;">
                    <?php echo e('{{' . $var . '); ?>' }}
                </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
        <?php echo $template->content; ?>

    </div>
</div>
<?php /**PATH /var/www/html/resources/views/filament/email-template-preview.blade.php ENDPATH**/ ?>