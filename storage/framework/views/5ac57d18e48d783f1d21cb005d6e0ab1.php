

<?php $__env->startSection('title', ($user->name ?? '用户') . ' - 个人主页'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 980px; margin: 0 auto; padding: 32px 20px;">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div style="margin-bottom:16px; padding:12px 16px; background:#ecfdf5; color:#047857; border-radius:12px; font-size:14px;"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div style="margin-bottom:16px; padding:12px 16px; background:#fef2f2; color:#b91c1c; border-radius:12px; font-size:14px;"><?php echo e(session('error')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div style="margin-bottom:16px; padding:12px 16px; background:#fef2f2; color:#b91c1c; border-radius:12px; font-size:14px;"><?php echo e($errors->first()); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div style="background:#fff; border-radius:16px; padding:24px; box-shadow:0 6px 24px rgba(0,0,0,.06); margin-bottom:20px;">
        <div style="display:flex; align-items:center; gap:16px;">
            <img src="<?php echo e($user->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'U')); ?>"
                 alt="<?php echo e($user->name); ?>"
                 style="width:72px; height:72px; border-radius:50%; object-fit:cover;">
            <div>
                <h1 style="margin:0; color:#1e293b;"><?php echo e($user->name ?? '匿名用户'); ?></h1>
                <p style="margin:6px 0 0; color:#64748b;">加入于 <?php echo e(optional($user->created_at)->format('Y-m-d')); ?></p>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; margin-top:18px;">
            <div style="background:#f8fafc; border-radius:12px; padding:14px; text-align:center;">
                <div style="color:#0f172a; font-size:20px; font-weight:800;"><?php echo e($stats['comments'] ?? 0); ?></div>
                <div style="color:#64748b; font-size:13px;">评论数</div>
            </div>
            <div style="background:#f8fafc; border-radius:12px; padding:14px; text-align:center;">
                <div style="color:#0f172a; font-size:20px; font-weight:800;"><?php echo e($stats['favorites'] ?? 0); ?></div>
                <div style="color:#64748b; font-size:13px;">收藏数</div>
            </div>
            <div style="background:#f8fafc; border-radius:12px; padding:14px; text-align:center;">
                <div style="color:#0f172a; font-size:20px; font-weight:800;"><?php echo e($stats['histories'] ?? 0); ?></div>
                <div style="color:#64748b; font-size:13px;">浏览数</div>
            </div>
        </div>
    </div>

    <div style="background:#fff; border-radius:16px; padding:24px; box-shadow:0 6px 24px rgba(0,0,0,.06); margin-bottom:20px;">
        <h2 style="margin-top:0; color:#1e293b;">留言</h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((int) auth()->id() === (int) $user->id): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($profileMessages): ?>
                    <p style="color:#64748b; font-size:14px; margin-top:0;">以下为访客发给你的留言（仅自己可见）。</p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $profileMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div style="padding:16px 0; border-bottom:1px solid #e2e8f0;">
                            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                                <div>
                                    <strong style="color:#1e293b;"><?php echo e($msg->sender?->name ?? '用户'); ?></strong>
                                    <span style="color:#94a3b8; font-size:12px; margin-left:8px;"><?php echo e($msg->created_at->diffForHumans()); ?></span>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isVip() && !$urgentSentToday): ?>
                                    <form action="<?php echo e(route('users.messages.urgent', [$user, $msg])); ?>" method="post" style="display:flex; flex-direction:column; align-items:flex-end; gap:8px; min-width:200px;">
                                        <?php echo csrf_field(); ?>
                                        <textarea name="urgent_note" rows="2" placeholder="紧急附言（可选，留空用默认文案）" style="width:100%; max-width:280px; padding:8px 10px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; resize:vertical;"></textarea>
                                        <button type="submit" style="padding:8px 14px; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">📣 紧急通知（邮件）</button>
                                    </form>
                                <?php elseif(auth()->user()->isVip() && $urgentSentToday): ?>
                                    <span style="font-size:12px; color:#94a3b8;">今日紧急通知已使用</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <p style="margin:10px 0 0; color:#334155; font-size:14px; white-space:pre-wrap;"><?php echo e($msg->body); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p style="color:#64748b;">暂无留言</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($profileMessages->hasPages()): ?>
                        <div style="margin-top:16px;"><?php echo e($profileMessages->links()); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                <form action="<?php echo e(route('users.messages.store', $user)); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <label for="profile-msg-body" style="display:block; color:#64748b; font-size:13px; margin-bottom:8px;">给 TA 留言（登录用户）</label>
                    <textarea id="profile-msg-body" name="body" rows="4" required maxlength="2000" placeholder="写下你想说的话…" style="width:100%; padding:12px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; resize:vertical;"><?php echo e(old('body')); ?></textarea>
                    <button type="submit" style="margin-top:12px; padding:10px 20px; background:#6366f1; color:#fff; border:none; border-radius:10px; font-weight:600; cursor:pointer;">发送留言</button>
                </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php else: ?>
            <p style="color:#64748b; margin:0;">
                <a href="<?php echo e(route('login', ['redirect' => url()->current()])); ?>" style="color:#6366f1; font-weight:600;">登录</a>
                后可向该用户留言。
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div style="background:#fff; border-radius:16px; padding:24px; box-shadow:0 6px 24px rgba(0,0,0,.06);">
        <h2 style="margin-top:0; color:#1e293b;">最近评论</h2>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="padding:14px 0; border-bottom:1px solid #e2e8f0;">
                <div style="color:#334155; font-size:14px;"><?php echo e($comment->content); ?></div>
                <div style="margin-top:6px; color:#94a3b8; font-size:12px;">
                    <?php echo e($comment->created_at->diffForHumans()); ?>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="color:#64748b;">暂无评论</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/users/show.blade.php ENDPATH**/ ?>