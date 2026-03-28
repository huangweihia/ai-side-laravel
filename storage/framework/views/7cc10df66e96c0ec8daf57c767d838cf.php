

<?php $__env->startSection('title', '投稿文章互动 - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 1000px; margin: 0 auto; padding: 40px 20px;">
    <div style="margin-bottom: 24px;">
        <a href="<?php echo e(route('submissions.index')); ?>" style="color: var(--primary-light); text-decoration: none; font-weight: 600;">← 返回投稿</a>
    </div>
    <h1 style="font-size: 26px; font-weight: 800; color: var(--white); margin: 0 0 8px;">📊 投稿文章互动</h1>
    <p style="color: var(--gray-light); margin: 0 0 32px; font-size: 14px;">展示已通过审核并发布为文章的数据：点赞与收藏用户列表（各最多 50 条）。</p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $d = $details[$article->id] ?? ['likes' => collect(), 'favorites' => collect()];
        ?>
        <div style="background: var(--dark-light); border-radius: 16px; padding: 24px; margin-bottom: 20px; border: 1px solid rgba(255,255,255,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; margin-bottom: 16px;">
                <div>
                    <h2 style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: var(--white);"><?php echo e($article->title); ?></h2>
                    <div style="font-size: 13px; color: var(--gray);">
                        ❤️ 点赞 <?php echo e(number_format($article->like_count)); ?> &nbsp;·&nbsp; ⭐ 收藏 <?php echo e(number_format($article->favorite_count)); ?>

                    </div>
                </div>
                <a href="<?php echo e(route('articles.show', $article->id)); ?>" style="padding: 10px 18px; border-radius: 10px; background: rgba(99,102,241,0.2); color: var(--primary-light); text-decoration: none; font-weight: 600; font-size: 14px;">打开文章</a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; color: #fca5a5; margin: 0 0 10px;">点赞用户</h3>
                    <ul style="margin: 0; padding-left: 18px; color: var(--gray-light); font-size: 14px; line-height: 1.8;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_2 = true; $__currentLoopData = $d['likes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ua): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <li>
                                <a href="<?php echo e(route('users.show', $ua->user_id)); ?>" style="color: var(--white); text-decoration: none;"><?php echo e($ua->user->name ?? '用户'); ?></a>
                                <span style="color: var(--gray); font-size: 12px;"><?php echo e($ua->created_at?->format('Y-m-d H:i')); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <li style="list-style: none; padding-left: 0; color: var(--gray);">暂无</li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; color: #fbbf24; margin: 0 0 10px;">收藏用户</h3>
                    <ul style="margin: 0; padding-left: 18px; color: var(--gray-light); font-size: 14px; line-height: 1.8;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_2 = true; $__currentLoopData = $d['favorites']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fav): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <li>
                                <a href="<?php echo e(route('users.show', $fav->user_id)); ?>" style="color: var(--white); text-decoration: none;"><?php echo e($fav->user->name ?? '用户'); ?></a>
                                <span style="color: var(--gray); font-size: 12px;"><?php echo e($fav->created_at?->format('Y-m-d H:i')); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <li style="list-style: none; padding-left: 0; color: var(--gray);">暂无</li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p style="color: var(--gray-light); text-align: center; padding: 48px;">暂无已通过投稿发布的文章。</p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/articles/my-engagement.blade.php ENDPATH**/ ?>