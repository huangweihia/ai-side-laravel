<?php $__env->startSection('title', '我的收藏 - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">

    
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 36px; font-weight: 800; color: var(--white); margin-bottom: 12px;">
            ⭐ 我的收藏
        </h1>
        <p style="color: var(--gray-light); font-size: 16px;">
            管理你收藏的项目和文章
        </p>
    </div>

    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <a href="<?php echo e(route('favorites.index', ['type' => 'all'])); ?>" 
           style="
               padding: 24px;
               background: <?php echo e($type === 'all' ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'var(--dark-light)'); ?>;
               border-radius: 16px;
               text-decoration: none;
               color: <?php echo e($type === 'all' ? 'white' : 'var(--white)'); ?>;
               box-shadow: 0 4px 20px rgba(0,0,0,0.2);
               transition: all 0.3s;
               text-align: center;
               border: 1px solid rgba(255,255,255,0.1);
           "
           onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.3)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.2)'"
        >
            <div style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">
                <?php echo e($stats['all']); ?>

            </div>
            <div style="font-size: 14px; opacity: 0.8;">全部收藏</div>
        </a>
        
        <a href="<?php echo e(route('favorites.index', ['type' => 'projects'])); ?>" 
           style="
               padding: 24px;
               background: <?php echo e($type === 'projects' ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'var(--dark-light)'); ?>;
               border-radius: 16px;
               text-decoration: none;
               color: <?php echo e($type === 'projects' ? 'white' : 'var(--white)'); ?>;
               box-shadow: 0 4px 20px rgba(0,0,0,0.2);
               transition: all 0.3s;
               text-align: center;
               border: 1px solid rgba(255,255,255,0.1);
           "
           onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.3)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.2)'"
        >
            <div style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">
                <?php echo e($stats['projects']); ?>

            </div>
            <div style="font-size: 14px; opacity: 0.8;">收藏项目</div>
        </a>
        
        <a href="<?php echo e(route('favorites.index', ['type' => 'articles'])); ?>" 
           style="
               padding: 24px;
               background: <?php echo e($type === 'articles' ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'var(--dark-light)'); ?>;
               border-radius: 16px;
               text-decoration: none;
               color: <?php echo e($type === 'articles' ? 'white' : 'var(--white)'); ?>;
               box-shadow: 0 4px 20px rgba(0,0,0,0.2);
               transition: all 0.3s;
               text-align: center;
               border: 1px solid rgba(255,255,255,0.1);
           "
           onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.3)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.2)'"
        >
            <div style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">
                <?php echo e($stats['articles']); ?>

            </div>
            <div style="font-size: 14px; opacity: 0.8;">收藏文章</div>
        </a>
    </div>

    
    <div style="display: flex; gap: 12px; margin-bottom: 32px; flex-wrap: wrap;">
        <a href="<?php echo e(route('favorites.index', ['type' => 'all'])); ?>" 
           style="
               padding: 10px 20px;
               background: <?php echo e($type === 'all' ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'var(--dark-light)'); ?>;
               color: <?php echo e($type === 'all' ? 'white' : 'var(--white)'); ?>;
               border: <?php echo e($type === 'all' ? 'none' : '1px solid rgba(255,255,255,0.2)'); ?>;
               border-radius: 50px;
               font-weight: 600;
               text-decoration: none;
               transition: all 0.3s;
           "
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.3)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
        >
            📂 全部
        </a>
        <a href="<?php echo e(route('favorites.index', ['type' => 'projects'])); ?>" 
           style="
               padding: 10px 20px;
               background: <?php echo e($type === 'projects' ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'var(--dark-light)'); ?>;
               color: <?php echo e($type === 'projects' ? 'white' : 'var(--white)'); ?>;
               border: <?php echo e($type === 'projects' ? 'none' : '1px solid rgba(255,255,255,0.2)'); ?>;
               border-radius: 50px;
               font-weight: 600;
               text-decoration: none;
               transition: all 0.3s;
           "
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.3)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
        >
            🚀 项目
        </a>
        <a href="<?php echo e(route('favorites.index', ['type' => 'articles'])); ?>" 
           style="
               padding: 10px 20px;
               background: <?php echo e($type === 'articles' ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'var(--dark-light)'); ?>;
               color: <?php echo e($type === 'articles' ? 'white' : 'var(--white)'); ?>;
               border: <?php echo e($type === 'articles' ? 'none' : '1px solid rgba(255,255,255,0.2)'); ?>;
               border-radius: 50px;
               font-weight: 600;
               text-decoration: none;
               transition: all 0.3s;
           "
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.3)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
        >
            📚 文章
        </a>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($favorites->count()): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $favorites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $favorite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $item = $favorite->favoritable;
                    $isProject = $item instanceof \App\Models\Project;
                ?>
                
                <div style="
                    background: var(--dark-light);
                    border-radius: 16px;
                    padding: 20px;
                    border: 1px solid rgba(255,255,255,0.1);
                    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                    transition: all 0.3s;
                    position: relative;
                "
                onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.15)'"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'"
                >
                    
                    <button onclick="removeFavorite(<?php echo e($favorite->id); ?>, '<?php echo e($isProject ? 'project' : 'article'); ?>')"
                            style="
                                position: absolute;
                                top: 16px;
                                right: 16px;
                                width: 36px;
                                height: 36px;
                                background: rgba(239, 68, 68, 0.1);
                                color: #ef4444;
                                border: none;
                                border-radius: 50%;
                                font-size: 18px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.3s;
                            "
                            onmouseover="this.style.background='#ef4444'; this.style.color='white'"
                            onmouseout="this.style.background='rgba(239, 68, 68, 0.1)'; this.style.color='#ef4444'"
                            title="取消收藏"
                    >
                        ✕
                    </button>
                    
                    
                    <a href="<?php echo e($isProject ? route('projects.show', $item) : route('articles.show', $item)); ?>" 
                       style="text-decoration: none; color: inherit;"
                    >
                        <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 16px;">
                            <div style="
                                width: 60px;
                                height: 60px;
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                border-radius: 12px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 28px;
                                flex-shrink: 0;
                            ">
                                <?php echo e($isProject ? '🚀' : '📚'); ?>

                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php echo e($item->name ?? $item->title); ?>

                                </h3>
                                <p style="color: #64748b; font-size: 14px; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php echo e($item->description ?? $item->summary ?? '暂无描述'); ?>

                                </p>
                            </div>
                        </div>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isProject && $item->stars): ?>
                        <div style="display: flex; gap: 16px; color: #94a3b8; font-size: 13px;">
                            <span>⭐ <?php echo e(number_format($item->stars)); ?></span>
                            <span>🍴 <?php echo e(number_format($item->forks ?? 0)); ?></span>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isProject && $item->view_count): ?>
                        <div style="display: flex; gap: 16px; color: #94a3b8; font-size: 13px;">
                            <span>👁️ <?php echo e(number_format($item->view_count)); ?></span>
                            <span>❤️ <?php echo e(number_format($item->like_count ?? 0)); ?></span>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </a>
                    
                    
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                        <a href="<?php echo e($isProject ? route('projects.show', $item) : route('articles.show', $item)); ?>" 
                           style="
                               color: #667eea;
                               font-size: 14px;
                               font-weight: 600;
                               text-decoration: none;
                               display: inline-flex;
                               align-items: center;
                               gap: 6px;
                               transition: all 0.3s;
                           "
                           onmouseover="this.style.color='#764ba2'"
                           onmouseout="this.style.color='#667eea'"
                        >
                            查看详情 <span>→</span>
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        
        
        <div style="margin-top: 48px; display: flex; justify-content: center;">
            <?php echo e($favorites->links()); ?>

        </div>
    <?php else: ?>
        
        <div style="text-align: center; padding: 80px 20px;">
            <div style="font-size: 96px; margin-bottom: 24px;">📭</div>
            <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 12px;">
                暂无收藏
            </h2>
            <p style="color: #64748b; font-size: 16px; margin-bottom: 32px;">
                快去发现有趣的项目和文章吧！
            </p>
            <div style="display: flex; gap: 16px; justify-content: center;">
                <a href="<?php echo e(route('projects.index')); ?>" 
                   style="
                       padding: 14px 32px;
                       background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                       color: white;
                       border-radius: 50px;
                       font-size: 16px;
                       font-weight: 700;
                       text-decoration: none;
                       transition: all 0.3s;
                   "
                   onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(102, 126, 234, 0.4)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    浏览项目
                </a>
                <a href="<?php echo e(route('articles.index')); ?>" 
                   style="
                       padding: 14px 32px;
                       background: white;
                       color: #667eea;
                       border: 2px solid #667eea;
                       border-radius: 50px;
                       font-size: 16px;
                       font-weight: 700;
                       text-decoration: none;
                       transition: all 0.3s;
                   "
                   onmouseover="this.style.background='#667eea'; this.style.color='white'"
                   onmouseout="this.style.background='white'; this.style.color='#667eea'"
                >
                    浏览文章
                </a>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>

<script>
// 取消收藏
function removeFavorite(favoriteId, type) {
    if (!confirm('确定要取消收藏吗？')) return;
    
    fetch(`/favorites/${type}/${favoriteId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || '取消收藏失败');
        }
    })
    .catch(err => {
        console.error(err);
        alert('取消收藏失败，请重试');
    });
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/favorites/index.blade.php ENDPATH**/ ?>