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
        
        <!-- 收件人管理 -->
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
                📧 邮件接收人管理
             <?php $__env->endSlot(); ?>
             <?php $__env->slot('description', null, []); ?> 
                配置每日资讯日报的接收邮箱
             <?php $__env->endSlot(); ?>
            
            <div style="display: flex; gap: 12px; margin-bottom: 24px;">
                <form wire:submit="addRecipient" style="display: flex; gap: 12px; flex: 1;">
                    <input 
                        type="email" 
                        wire:model="recipient" 
                        placeholder="输入邮箱地址"
                        style="flex: 1; padding: 12px 16px; background: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px;"
                    />
                    <button 
                        type="submit"
                        style="padding: 12px 24px; background: #6366f1; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;"
                    >
                        添加收件人
                    </button>
                </form>
            </div>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($this->recipients) > 0): ?>
                <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                    <button 
                        wire:click="bulkDelete"
                        wire:confirm="确定要删除选中的 <?php echo e(count($this->selectedForBulk)); ?> 个邮箱吗？"
                        style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 6px;"
                    >
                        🗑️ 批量删除 (<span x-text="<?php echo e(count($this->selectedForBulk)); ?>">0</span>)
                    </button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <div style="display: grid; gap: 12px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->recipients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $email): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #1e293b; border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input 
                                type="checkbox" 
                                wire:click="toggleBulkSelect('<?php echo e($email); ?>')"
                                <?php echo e(in_array($email, $this->selectedForBulk) ? 'checked' : ''); ?>

                                style="width: 18px; height: 18px; cursor: pointer;"
                            />
                            <span style="font-size: 20px;">📧</span>
                            <span style="color: white; font-weight: 500;"><?php echo e($email); ?></span>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button 
                                wire:click="removeRecipient('<?php echo e($email); ?>')"
                                style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; font-size: 13px; cursor: pointer;"
                            >
                                删除
                            </button>
                            <button 
                                wire:click="sendTestEmail"
                                wire:loading.attr="disabled"
                                style="padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 13px; cursor: pointer;"
                            >
                                发送测试
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($this->recipients)): ?>
                    <div style="text-align: center; padding: 40px; color: #64748b;">
                        暂无收件人，请添加邮箱地址
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

        <!-- 批量导入/导出 -->
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
                📥 批量导入/导出
             <?php $__env->endSlot(); ?>
             <?php $__env->slot('description', null, []); ?> 
                批量导入邮箱地址或导出当前列表
             <?php $__env->endSlot(); ?>
            
            <div style="display: grid; gap: 16px;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #94a3b8;">
                        批量导入（每行一个邮箱地址）
                    </label>
                    <textarea 
                        wire:model="bulkEmails"
                        placeholder="example1@qq.com&#10;example2@qq.com&#10;example3@qq.com"
                        rows="5"
                        style="width: 100%; padding: 12px 16px; background: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px; font-family: monospace;"
                    ></textarea>
                    <div style="margin-top: 12px; display: flex; gap: 12px;">
                        <button 
                            wire:click="bulkImport"
                            style="padding: 10px 20px; background: #6366f1; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;"
                        >
                            📥 导入邮箱列表
                        </button>
                        <a 
                            href="<?php echo e(url('/admin/email-manager/export')); ?>"
                            style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;"
                        >
                            📤 导出邮箱列表
                        </a>
                    </div>
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

        <!-- 发送时间配置 -->
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
                ⏰ 定时发送配置
             <?php $__env->endSlot(); ?>
             <?php $__env->slot('description', null, []); ?> 
                当前定时任务配置为每天 <?php echo e($this->sendTime); ?> 自动发送
             <?php $__env->endSlot(); ?>
            
            <div style="padding: 20px; background: #1e293b; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <span style="font-size: 24px;">🕐</span>
                    <div>
                        <div style="color: #94a3b8; font-size: 14px;">当前发送时间</div>
                        <div style="color: white; font-size: 20px; font-weight: 600;"><?php echo e($this->sendTime); ?> (北京时间)</div>
                    </div>
                </div>
                <div style="color: #64748b; font-size: 14px; line-height: 1.6;">
                    <p>如需修改发送时间，请在 OpenClaw 定时任务管理中调整：</p>
                    <code style="display: block; margin-top: 8px; padding: 12px; background: #0f172a; border-radius: 6px; color: #10b981;">
                        cron schedule: 0 2 * * * (UTC) = 每天 10:00 (Asia/Shanghai)
                    </code>
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

        <!-- 最近发送记录 -->
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
                📋 最近发送记录
             <?php $__env->endSlot(); ?>
             <?php $__env->slot('description', null, []); ?> 
                查看最近的邮件发送历史
             <?php $__env->endSlot(); ?>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">收件人</th>
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">主题</th>
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">状态</th>
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">发送时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 12px; color: white;"><?php echo e($log->recipient); ?></td>
                                <td style="padding: 12px; color: #94a3b8; max-width: 300px; overflow: hidden; text-overflow: ellipsis;"><?php echo e($log->subject); ?></td>
                                <td style="padding: 12px;">
                                    <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; <?php echo e($log->status === 'sent' ? 'background: #10b981; color: white;' : 'background: #ef4444; color: white;'); ?>">
                                        <?php echo e($log->status === 'sent' ? '✅ 已发送' : '❌ 失败'); ?>

                                    </span>
                                </td>
                                <td style="padding: 12px; color: #94a3b8;"><?php echo e($log->sent_at?->format('Y-m-d H:i') ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px; color: #64748b;">
                                    暂无发送记录
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
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
                <button 
                    wire:click="sendTestEmail"
                    style="padding: 12px 24px; background: #6366f1; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;"
                >
                    <span>🚀</span> 立即发送日报
                </button>
                <a 
                    href="<?php echo e(url('/admin/email-logs')); ?>"
                    style="padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;"
                >
                    <span>📧</span> 查看完整日志
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
<?php /**PATH /var/www/html/resources/views/filament/pages/email-manager.blade.php ENDPATH**/ ?>