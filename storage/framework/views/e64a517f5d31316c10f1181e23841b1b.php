<?php $__env->startSection('title', '注册 - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 480px; margin: 60px auto;">
    <div class="card" style="padding: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 48px; margin-bottom: 16px;">🚀</div>
            <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">创建账号</h1>
            <p style="color: var(--gray-light); font-size: 15px;">加入 AI 副业情报局，开启副业之旅</p>
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

        <form method="POST" action="<?php echo e(route('register')); ?>" id="registerForm">
            <?php echo csrf_field(); ?>
            
            <div class="form-group">
                <label class="form-label" for="name">姓名</label>
                <input class="form-input" id="name" type="text" name="name" value="<?php echo e(old('name')); ?>" required autofocus placeholder="你的名字">
            </div>

            <div class="form-group">
                <label class="form-label" for="email">邮箱地址</label>
                <input class="form-input" id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="your@email.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">密码</label>
                <input class="form-input" id="password" type="password" name="password" required placeholder="至少 8 位">
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">确认密码</label>
                <input class="form-input" id="password_confirmation" type="password" name="password_confirmation" required placeholder="再次输入密码">
            </div>

            <button class="btn btn-primary" type="button" onclick="confirmRegister()" style="width: 100%; padding: 14px; font-size: 15px;">
                免费注册
            </button>
        </form>

        <script>
        function confirmRegister() {
            const form = document.getElementById('registerForm');
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;

            if (!name || !email || !password || !passwordConfirm) {
                showToast('请填写所有必填字段', 'error');
                return;
            }

            if (password !== passwordConfirm) {
                showToast('两次输入的密码不一致', 'error');
                return;
            }

            if (password.length < 8) {
                showToast('密码长度至少 8 位', 'error');
                return;
            }

            showConfirm({
                icon: '🎉',
                title: '确认注册',
                content: '注册后即表示你同意我们的服务条款和隐私政策。<br>注册成功后将自动订阅每日资讯日报。',
                confirmText: '确认注册',
                confirmColor: '#6366f1',
                onConfirm: async () => {
                    showLoading('正在创建账号...');
                    form.submit();
                }
            });
        }
        </script>

        <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1);">
            <p style="color: var(--gray-light); font-size: 14px;">
                已有账号？
                <a href="<?php echo e(route('login')); ?>" style="color: var(--primary-light); text-decoration: none; font-weight: 600;">立即登录</a>
            </p>
        </div>
        
        <p style="text-align: center; color: var(--gray); font-size: 13px; margin-top: 20px;">
            注册即表示你同意我们的
            <a href="#" style="color: var(--gray); text-decoration: underline;">服务条款</a>
            和
            <a href="#" style="color: var(--gray); text-decoration: underline;">隐私政策</a>
        </p>
    </div>
    
    <p style="text-align: center; color: var(--gray); font-size: 14px; margin-top: 24px;">
        <a href="<?php echo e(route('home')); ?>" style="color: var(--gray); text-decoration: none;">
            <span>←</span> 返回首页
        </a>
    </p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/register.blade.php ENDPATH**/ ?>