<?php $__env->startSection('title', '登录 - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 480px; margin: 80px auto;">
    <div class="card" style="padding: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 48px; margin-bottom: 16px;">👋</div>
            <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">欢迎回来</h1>
            <p style="color: var(--gray-light); font-size: 15px;">登录你的 AI 副业情报局账号</p>
        </div>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->filled('redirect')): ?>
                <input type="hidden" name="redirect" value="<?php echo e(request('redirect')); ?>">
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="form-group">
                <label class="form-label" for="email">邮箱地址</label>
                <input class="form-input" id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus placeholder="your@email.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">密码</label>
                <input class="form-input" id="password" type="password" name="password" required placeholder="••••••••">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="remember" style="margin-right: 8px; width: 16px; height: 16px;">
                    <span style="color: var(--gray-light); font-size: 14px;">记住我</span>
                </label>
                <a href="#" style="color: var(--primary-light); text-decoration: none; font-size: 14px; font-weight: 500;">忘记密码？</a>
            </div>

            <button class="btn btn-primary" type="submit" style="width: 100%; padding: 14px; font-size: 15px;">
                登录
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1);">
            <p style="color: var(--gray-light); font-size: 14px;">
                还没有账号？
                <a href="<?php echo e(route('register')); ?>" style="color: var(--primary-light); text-decoration: none; font-weight: 600;">立即注册</a>
            </p>
        </div>
    </div>
    
    <p style="text-align: center; color: var(--gray); font-size: 14px; margin-top: 24px;">
        <a href="<?php echo e(route('home')); ?>" style="color: var(--gray); text-decoration: none;">
            <span>←</span> 返回首页
        </a>
    </p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>