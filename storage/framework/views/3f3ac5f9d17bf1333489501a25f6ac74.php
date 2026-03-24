<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div style="display: grid; gap: 32px;">
        
        <!-- 用户增长 -->
        <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
             <?php $__env->slot('heading', null, []); ?> 
                📈 用户增长
             <?php $__env->endSlot(); ?>
            
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
                <div style="background: #1e293b; padding: 20px; border-radius: 12px;">
                    <div style="color: #94a3b8; font-size: 14px; margin-bottom: 8px;">总用户数</div>
                    <div style="font-size: 32px; font-weight: bold; color: #6366f1;"><?php echo e($this->getStats()['users']['total']); ?></div>
                </div>
                <div style="background: #1e293b; padding: 20px; border-radius: 12px;">
                    <div style="color: #94a3b8; font-size: 14px; margin-bottom: 8px;">今日新增</div>
                    <div style="font-size: 32px; font-weight: bold; color: #10b981;">+<?php echo e($this->getStats()['users']['today']); ?></div>
                </div>
                <div style="background: #1e293b; padding: 20px; border-radius: 12px;">
                    <div style="color: #94a3b8; font-size: 14px; margin-bottom: 8px;">本周新增</div>
                    <div style="font-size: 32px; font-weight: bold; color: #3b82f6;">+<?php echo e($this->getStats()['users']['week']); ?></div>
                </div>
                <div style="background: #1e293b; padding: 20px; border-radius: 12px;">
                    <div style="color: #94a3b8; font-size: 14px; margin-bottom: 8px;">本月新增</div>
                    <div style="font-size: 32px; font-weight: bold; color: #8b5cf6;">+<?php echo e($this->getStats()['users']['month']); ?></div>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>

        <!-- VIP 会员 -->
        <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
             <?php $__env->slot('heading', null, []); ?> 
                ⭐ VIP 会员
             <?php $__env->endSlot(); ?>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                <div style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); padding: 24px; border-radius: 12px;">
                    <div style="color: rgba(255,255,255,0.9); font-size: 14px; margin-bottom: 8px;">VIP 用户总数</div>
                    <div style="font-size: 36px; font-weight: bold; color: white;"><?php echo e($this->getStats()['vips']['total']); ?></div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 8px;">
                        占比 <?php echo e(number_format($this->getStats()['vips']['total'] / max($this->getStats()['users']['total'], 1) * 100, 1)); ?>%
                    </div>
                </div>
                <div style="background: #1e293b; padding: 24px; border-radius: 12px; border: 2px dashed #ef4444;">
                    <div style="color: #94a3b8; font-size: 14px; margin-bottom: 8px;">即将到期（7 天内）</div>
                    <div style="font-size: 36px; font-weight: bold; color: #ef4444;"><?php echo e($this->getStats()['vips']['expiring_soon']); ?></div>
                    <div style="color: #94a3b8; font-size: 13px; margin-top: 8px;">需要跟进续费</div>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>

        <!-- 项目库 -->
        <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
             <?php $__env->slot('heading', null, []); ?> 
                📊 项目库
             <?php $__env->endSlot(); ?>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                <div style="background: #1e293b; padding: 20px; border-radius: 12px;">
                    <div style="color: #94a3b8; font-size: 14px; margin-bottom: 8px;">项目总数</div>
                    <div style="font-size: 32px; font-weight: bold; color: #ec4899;"><?php echo e($this->getStats()['projects']['total']); ?></div>
                </div>
                <div style="background: #1e293b; padding: 20px; border-radius: 12px;">
                    <div style="color: #94a3b8; font-size: 14px; margin-bottom: 8px;">本周新增</div>
                    <div style="font-size: 32px; font-weight: bold; color: #14b8a6;">+<?php echo e($this->getStats()['projects']['this_week']); ?></div>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>

        <!-- 邮件发送 -->
        <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
             <?php $__env->slot('heading', null, []); ?> 
                📧 邮件发送
             <?php $__env->endSlot(); ?>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                <div style="background: #1e293b; padding: 20px; border-radius: 12px;">
                    <div style="color: #94a3b8; font-size: 14px; margin-bottom: 8px;">累计发送</div>
                    <div style="font-size: 32px; font-weight: bold; color: #3b82f6;"><?php echo e($this->getStats()['emails']['total_sent']); ?></div>
                </div>
                <div style="background: #1e293b; padding: 20px; border-radius: 12px;">
                    <div style="color: #94a3b8; font-size: 14px; margin-bottom: 8px;">今日发送</div>
                    <div style="font-size: 32px; font-weight: bold; color: #10b981;"><?php echo e($this->getStats()['emails']['today']); ?></div>
                </div>
                <div style="background: #1e293b; padding: 20px; border-radius: 12px; border: 2px solid #ef4444;">
                    <div style="color: #94a3b8; font-size: 14px; margin-bottom: 8px;">发送失败</div>
                    <div style="font-size: 32px; font-weight: bold; color: #ef4444;"><?php echo e($this->getStats()['emails']['failed']); ?></div>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>

        <!-- 快捷操作 -->
        <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
             <?php $__env->slot('heading', null, []); ?> 
                ⚡ 快捷操作
             <?php $__env->endSlot(); ?>
            
            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                <a href="<?php echo e(route('filament.admin.resources.users.index')); ?>" class="btn" style="background: #6366f1; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <span>👥</span> 管理用户
                </a>
                <a href="<?php echo e(route('filament.admin.resources.projects.index')); ?>" class="btn" style="background: #ec4899; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <span>📁</span> 管理项目
                </a>
                <a href="<?php echo e(route('filament.admin.resources.email-logs.index')); ?>" class="btn" style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <span>📧</span> 查看邮件日志
                </a>
                <a href="<?php echo e(url('/admin/ai-projects:send-daily')); ?>" class="btn" style="background: #10b981; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <span>🚀</span> 立即发送日报
                </a>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/filament/pages/analytics.blade.php ENDPATH**/ ?>