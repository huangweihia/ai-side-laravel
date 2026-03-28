<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['article']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['article']); ?>
<?php foreach (array_filter((['article']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->is_vip && (!auth()->check() || !auth()->user()->isVip())): ?>
    <div style="position: relative; margin-top: 30px;">
        
        <div style="filter: blur(8px); opacity: 0.3; pointer-events: none; user-select: none;">
            <?php echo $article->content; ?>

        </div>

        
        <a href="<?php echo e(route('vip')); ?>"
           style="position: absolute; inset: 0; z-index: 9; display: block; background: rgba(15, 23, 42, 0.25); backdrop-filter: blur(1px);"
           aria-label="开通 VIP 查看完整内容"
           title="开通 VIP 查看完整内容"></a>
        
        
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border: 2px solid #fbbf24; border-radius: 16px; padding: 40px; text-align: center; box-shadow: 0 20px 60px rgba(251, 191, 36, 0.3); z-index: 10; min-width: 400px;">
            
            <div style="font-size: 64px; margin-bottom: 20px;">👑</div>
            
            <h3 style="color: white; font-size: 24px; font-weight: 700; margin-bottom: 15px;">
                VIP 专属内容
            </h3>
            
            <p style="color: #94a3b8; font-size: 15px; margin-bottom: 30px; line-height: 1.6;">
                开通 VIP 即可解锁全文，享受更多专属权益
            </p>
            
            
            <div style="text-align: left; margin-bottom: 30px; padding: 20px; background: rgba(251, 191, 36, 0.1); border-radius: 12px;">
                <div style="color: #fbbf24; font-size: 14px; margin-bottom: 10px;">
                    <span style="color: #10b981;">✓</span> 无限阅读 VIP 文章
                </div>
                <div style="color: #fbbf24; font-size: 14px; margin-bottom: 10px;">
                    <span style="color: #10b981;">✓</span> 无限次知识检索
                </div>
                <div style="color: #fbbf24; font-size: 14px; margin-bottom: 10px;">
                    <span style="color: #10b981;">✓</span> 专属邮件推送
                </div>
                <div style="color: #fbbf24; font-size: 14px;">
                    <span style="color: #10b981;">✓</span> 优先客服支持
                </div>
            </div>
            
            
            <div style="display: flex; gap: 12px; justify-content: center;">
                <a href="<?php echo e(route('vip', ['redirect' => request()->fullUrl()])); ?>" 
                   style="padding: 14px 32px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 8px 25px rgba(251, 191, 36, 0.4); transition: all 0.2s;"
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 35px rgba(251, 191, 36, 0.5)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(251, 191, 36, 0.4)'">
                    <span>🎯</span> 立即开通 VIP
                </a>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->points && auth()->user()->points->balance >= 100): ?>
                        <button onclick="event.stopPropagation(); unlockArticle(<?php echo e($article->id); ?>)"
                                style="padding: 14px 32px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 2px solid #10b981; border-radius: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;"
                                onmouseover="this.style.background='rgba(16, 185, 129, 0.3)'"
                                onmouseout="this.style.background='rgba(16, 185, 129, 0.2)'">
                            <span>⭐</span> 使用 100 积分解锁
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" 
                       style="padding: 14px 32px; background: rgba(255, 255, 255, 0.1); color: white; border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;"
                       onmouseover="this.style.background='rgba(255, 255, 255, 0.2)'"
                       onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'">
                        <span>🔐</span> 登录后解锁
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            
            
            <p style="color: #64748b; font-size: 13px; margin-top: 20px;">
                💡 首月仅需 <span style="color: #fbbf24; font-weight: 700; font-size: 16px;">¥9.9</span> | 平均每天 ¥0.33
            </p>
        </div>
    </div>
<?php else: ?>
    
    <div style="margin-top: 30px;">
        <?php echo $article->content; ?>

    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<script>
function unlockArticle(articleId) {
    if (!confirm('确定使用 100 积分解锁这篇文章吗？')) return;
    
    fetch(`/interactions/articles/${articleId}/unlock`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload(); // 刷新页面显示全文
        } else {
            alert(data.message || '解锁失败，请重试');
        }
    })
    .catch(err => {
        console.error(err);
        alert('解锁失败，请重试');
    });
}
</script>
<?php /**PATH /var/www/html/resources/views/components/vip-article-overlay.blade.php ENDPATH**/ ?>