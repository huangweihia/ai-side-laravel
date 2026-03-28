<?php if (isset($component)) { $__componentOriginalb525200bfa976483b4eaa0b7685c6e24 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-widgets::components.widget','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament-widgets::widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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
         <?php $__env->slot('description', null, []); ?> 
            常用功能快速访问
         <?php $__env->endSlot(); ?>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <a href="<?php echo e(route('filament.admin.resources.articles.index')); ?>" 
               style="padding: 20px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 12px; text-decoration: none; color: white; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: transform 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <span style="font-size: 32px;">📝</span>
                <span style="font-weight: 600;">文章管理</span>
                <span style="font-size: 12px; opacity: 0.8;">查看/编辑文章</span>
            </a>
            
            <a href="<?php echo e(route('filament.admin.resources.projects.index')); ?>" 
               style="padding: 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px; text-decoration: none; color: white; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: transform 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <span style="font-size: 32px;">🐙</span>
                <span style="font-weight: 600;">项目管理</span>
                <span style="font-size: 12px; opacity: 0.8;">GitHub 项目</span>
            </a>
            
            <a href="<?php echo e(route('filament.admin.pages.email-manager')); ?>" 
               style="padding: 20px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 12px; text-decoration: none; color: white; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: transform 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <span style="font-size: 32px;">📧</span>
                <span style="font-weight: 600;">邮件发送</span>
                <span style="font-size: 12px; opacity: 0.8;">选择模板发送</span>
            </a>
            
            <a href="<?php echo e(route('filament.admin.pages.task-manager')); ?>" 
               style="padding: 20px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 12px; text-decoration: none; color: white; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: transform 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <span style="font-size: 32px;">📋</span>
                <span style="font-weight: 600;">任务管理</span>
                <span style="font-size: 12px; opacity: 0.8;">查看执行记录</span>
            </a>
            
            <a href="<?php echo e(route('filament.admin.resources.knowledge-bases.index')); ?>" 
               style="padding: 20px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 12px; text-decoration: none; color: white; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: transform 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <span style="font-size: 32px;">📚</span>
                <span style="font-weight: 600;">知识库</span>
                <span style="font-size: 12px; opacity: 0.8;">文档管理</span>
            </a>
            
            <a href="<?php echo e(route('filament.admin.pages.points-manager')); ?>" 
               style="padding: 20px; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); border-radius: 12px; text-decoration: none; color: white; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: transform 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <span style="font-size: 32px;">🪙</span>
                <span style="font-weight: 600;">积分管理</span>
                <span style="font-size: 12px; opacity: 0.8;">积分流水</span>
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $attributes = $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $component = $__componentOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/filament/widgets/quick-actions-widget.blade.php ENDPATH**/ ?>