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
    <div style="display: grid; gap: 24px;">
        
        
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
                📊 积分统计
             <?php $__env->endSlot(); ?>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div style="padding: 20px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.3);">
                    <div style="color: #10b981; font-size: 14px; margin-bottom: 8px;">💰 累计发放积分</div>
                    <div style="color: white; font-size: 28px; font-weight: 700;">
                        <?php echo e(number_format($this->stats['total_earned'])); ?>

                    </div>
                </div>
                
                <div style="padding: 20px; background: rgba(239, 68, 68, 0.1); border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.3);">
                    <div style="color: #ef4444; font-size: 14px; margin-bottom: 8px;">🔥 累计消耗积分</div>
                    <div style="color: white; font-size: 28px; font-weight: 700;">
                        <?php echo e(number_format($this->stats['total_spent'])); ?>

                    </div>
                </div>
                
                <div style="padding: 20px; background: rgba(234, 179, 8, 0.1); border-radius: 12px; border: 1px solid rgba(234, 179, 8, 0.3);">
                    <div style="color: #eab308; font-size: 14px; margin-bottom: 8px;">👥 有积分用户数</div>
                    <div style="color: white; font-size: 28px; font-weight: 700;">
                        <?php echo e(number_format($this->stats['users_with_points'])); ?>

                    </div>
                </div>
                
                <div style="padding: 20px; background: rgba(99, 102, 241, 0.1); border-radius: 12px; border: 1px solid rgba(99, 102, 241, 0.3);">
                    <div style="color: #6366f1; font-size: 14px; margin-bottom: 8px;">📈 今日新增流水</div>
                    <div style="color: white; font-size: 28px; font-weight: 700;">
                        <?php echo e(number_format($this->stats['today_transactions'])); ?>

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
                📝 积分流水记录
             <?php $__env->endSlot(); ?>
             <?php $__env->slot('description', null, []); ?> 
                查看所有用户的积分获得和消耗记录
             <?php $__env->endSlot(); ?>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">用户</th>
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">类型</th>
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">积分</th>
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">说明</th>
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 12px; color: white;"><?php echo e($transaction->user?->name ?? '未知'); ?></td>
                                <td style="padding: 12px;">
                                    <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: rgba(99, 102, 241, 0.2); color: #818cf8;">
                                        <?php echo e(match($transaction->type) {
                                            'sign' => '签到',
                                            'share' => '分享',
                                            'like' => '点赞',
                                            'favorite' => '收藏',
                                            'comment' => '评论',
                                            'unlock' => '解锁',
                                            'admin_gift' => '赠送',
                                            'vip' => 'VIP',
                                            default => $transaction->type
                                        }); ?>

                                    </span>
                                </td>
                                <td style="padding: 12px; color: <?php echo e($transaction->amount > 0 ? '#10b981' : '#ef4444'); ?>; font-weight: 600;">
                                    <?php echo e($transaction->amount > 0 ? '+' : ''); ?><?php echo e($transaction->amount); ?>

                                </td>
                                <td style="padding: 12px; color: #94a3b8;"><?php echo e($transaction->description); ?></td>
                                <td style="padding: 12px; color: #94a3b8;"><?php echo e($transaction->created_at->format('Y-m-d H:i')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">
                                    暂无积分流水记录
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div style="margin-top: 20px; display: flex; justify-content: center; gap: 8px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->transactions->onFirstPage()): ?>
                    <span style="padding: 8px 16px; background: rgba(255,255,255,0.05); color: #64748b; border-radius: 6px; font-size: 13px;">上一页</span>
                <?php else: ?>
                    <a href="?page=<?php echo e($this->transactions->currentPage() - 1); ?>" style="padding: 8px 16px; background: rgba(255,255,255,0.05); color: white; border-radius: 6px; font-size: 13px; text-decoration: none;">上一页</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <span style="padding: 8px 16px; background: rgba(99, 102, 241, 0.2); color: #818cf8; border-radius: 6px; font-size: 13px;">
                    第 <?php echo e($this->transactions->currentPage()); ?> / <?php echo e($this->transactions->lastPage()); ?> 页
                </span>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->transactions->hasMorePages()): ?>
                    <a href="?page=<?php echo e($this->transactions->currentPage() + 1); ?>" style="padding: 8px 16px; background: rgba(255,255,255,0.05); color: white; border-radius: 6px; font-size: 13px; text-decoration: none;">下一页</a>
                <?php else: ?>
                    <span style="padding: 8px 16px; background: rgba(255,255,255,0.05); color: #64748b; border-radius: 6px; font-size: 13px;">下一页</span>
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
<?php /**PATH /var/www/html/resources/views/filament/pages/points-manager.blade.php ENDPATH**/ ?>