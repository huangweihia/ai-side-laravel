<?php $__env->startSection('title', '我的投稿 - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 1000px; margin: 0 auto; padding: 40px 20px;">
    
    
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 32px; gap:16px; flex-wrap:wrap;">
        <div>
            <h1 style="margin:0; color: var(--white); font-size: 28px; font-weight: 800;">📝 我的投稿</h1>
            <p style="margin: 8px 0 0 0; color: var(--gray-light); font-size: 14px;">管理和查看你的投稿内容</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="<?php echo e(route('submissions.create')); ?>" 
               style="padding:12px 20px; border-radius:12px; background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:#fff; text-decoration:none; font-weight:700; font-size: 14px; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.5)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.4)'">
                ✨ 新建投稿
            </a>
        </div>
    </div>

    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; margin-bottom: 32px;">
        <div style="padding: 20px; background: var(--dark-light); border-radius: 14px; border: 1px solid rgba(255,255,255,0.08); text-align: center;">
            <div style="font-size: 32px; font-weight: 800; color: var(--white);"><?php echo e($submissions->total()); ?></div>
            <div style="font-size: 13px; color: var(--gray); margin-top: 4px;">总投稿</div>
        </div>
        <div style="padding: 20px; background: rgba(245, 158, 11, 0.15); border-radius: 14px; border: 1px solid rgba(245, 158, 11, 0.3); text-align: center;">
            <div style="font-size: 32px; font-weight: 800; color: #fbbf24;"><?php echo e($submissions->where('status', 'pending')->count()); ?></div>
            <div style="font-size: 13px; color: var(--gray); margin-top: 4px;">待审核</div>
        </div>
        <div style="padding: 20px; background: rgba(16, 185, 129, 0.15); border-radius: 14px; border: 1px solid rgba(16, 185, 129, 0.3); text-align: center;">
            <div style="font-size: 32px; font-weight: 800; color: #10b981;"><?php echo e($submissions->where('status', 'approved')->count()); ?></div>
            <div style="font-size: 13px; color: var(--gray); margin-top: 4px;">已通过</div>
        </div>
        <div style="padding: 20px; background: rgba(239, 68, 68, 0.15); border-radius: 14px; border: 1px solid rgba(239, 68, 68, 0.3); text-align: center;">
            <div style="font-size: 32px; font-weight: 800; color: #ef4444;"><?php echo e($submissions->where('status', 'rejected')->count()); ?></div>
            <div style="font-size: 13px; color: var(--gray); margin-top: 4px;">已驳回</div>
        </div>
    </div>

    
    <div style="display:grid; gap:16px;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $statusConfig = [
                    'pending' => ['bg' => 'rgba(245, 158, 11, 0.15)', 'border' => 'rgba(245, 158, 11, 0.3)', 'color' => '#fbbf24', 'text' => '待审核', 'icon' => '⏳'],
                    'approved' => ['bg' => 'rgba(16, 185, 129, 0.15)', 'border' => 'rgba(16, 185, 129, 0.3)', 'color' => '#10b981', 'text' => '已通过', 'icon' => '✅'],
                    'rejected' => ['bg' => 'rgba(239, 68, 68, 0.15)', 'border' => 'rgba(239, 68, 68, 0.3)', 'color' => '#ef4444', 'text' => '已驳回', 'icon' => '❌'],
                ];
                $config = $statusConfig[$item->status] ?? $statusConfig['pending'];
                $typeIcons = ['document' => '📄', 'project' => '🚀', 'job' => '💼', 'knowledge' => '📚'];
                $typeNames = ['document' => '文档', 'project' => '项目', 'job' => '职位', 'knowledge' => '知识库'];
            ?>
            
            <div class="submission-card" 
                 data-submission-id="<?php echo e($item->id); ?>"
                 data-status="<?php echo e($item->status); ?>"
                 style="background: var(--dark-light); border-radius: 16px; padding: 20px 24px; border: 1px solid rgba(255,255,255,0.08); transition: all 0.3s; <?php echo e($item->status === 'pending' ? 'cursor: pointer;' : ''); ?>"
                 <?php if($item->status === 'pending'): ?> onclick="viewSubmissionDetail(<?php echo e($item->id); ?>)" <?php endif; ?>
                 onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.3)'; this.style.borderColor='rgba(255,255,255,0.15)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='rgba(255,255,255,0.08)'">
                
                <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
                    <div style="flex: 1; min-width: 280px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <span style="font-size: 24px;"><?php echo e($typeIcons[$item->type] ?? '📄'); ?></span>
                            <div style="font-size: 18px; font-weight: 700; color: var(--white);"><?php echo e($item->title); ?></div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; color: var(--gray); font-size: 13px; flex-wrap: wrap;">
                            <span><?php echo e($typeNames[$item->type] ?? $item->type); ?></span>
                            <span>·</span>
                            <span>提交于 <?php echo e($item->created_at->format('Y-m-d H:i')); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->reviewed_at): ?>
                                <span>·</span>
                                <span>审核于 <?php echo e($item->reviewed_at->format('Y-m-d H:i')); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->summary): ?>
                            <p style="margin: 12px 0 0; color: var(--gray-light); font-size: 14px; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?php echo e($item->summary); ?>

                            </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    
                    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                        
                        <span style="padding:6px 14px; border-radius:999px; font-size:12px; font-weight:700; background:<?php echo e($config['bg']); ?>; color:<?php echo e($config['color']); ?>; border: 1px solid <?php echo e($config['border']); ?>; display: flex; align-items: center; gap: 6px;">
                            <span><?php echo e($config['icon']); ?></span>
                            <span><?php echo e($config['text']); ?></span>
                        </span>
                        
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->status === 'approved' && $item->published_model_type && $item->published_model_id): ?>
                            <?php
                                $link = null;
                                if ($item->published_model_type === \App\Models\Article::class) {
                                    $link = route('articles.show', $item->published_model_id);
                                } elseif ($item->published_model_type === \App\Models\Project::class) {
                                    $link = route('projects.show', $item->published_model_id);
                                }
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($link): ?>
                                <a href="<?php echo e($link); ?>" 
                                   style="padding:6px 14px; border-radius:999px; font-size:12px; font-weight:700; background:rgba(99,102,241,.15); color:#818cf8; text-decoration:none; border: 1px solid rgba(99,102,241,.3); transition: all 0.2s;"
                                   onmouseover="this.style.background='rgba(99,102,241,.25)'"
                                   onmouseout="this.style.background='rgba(99,102,241,.15)'">
                                    🔗 查看内容
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->status === 'rejected'): ?>
                            <a href="<?php echo e(route('submissions.edit', $item->id)); ?>" 
                               style="padding:6px 14px; border-radius:999px; font-size:12px; font-weight:700; background:rgba(239,68,68,.15); color:#f87171; text-decoration:none; border: 1px solid rgba(239,68,68,.3); transition: all 0.2s;"
                               onmouseover="this.style.background='rgba(239,68,68,.25)'"
                               onmouseout="this.style.background='rgba(239,68,68,.15)'">
                                ✏️ 重新编辑
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->status === 'pending'): ?>
                            <span style="padding:6px 14px; border-radius:999px; font-size:12px; font-weight:600; background:rgba(255,255,255,.08); color:var(--gray-light); border: 1px solid rgba(255,255,255,.1);">
                                👁️ 点击查看详情
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->review_note): ?>
                    <div style="margin-top:16px; padding:14px 16px; border-radius:12px; background:<?php echo e($item->status === 'rejected' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(255,255,255,0.05)'); ?>; border: 1px solid <?php echo e($item->status === 'rejected' ? 'rgba(239, 68, 68, 0.2)' : 'rgba(255,255,255,0.08)'); ?>; color: var(--gray-light); font-size:13px; line-height: 1.6;">
                        <div style="font-weight: 600; margin-bottom: 6px; color: <?php echo e($item->status === 'rejected' ? '#f87171' : 'var(--gray)'); ?>;">
                            <?php echo e($item->status === 'rejected' ? '🚫 驳回原因' : '📝 审核备注'); ?>

                        </div>
                        <?php echo e($item->review_note); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="background: var(--dark-light); border-radius: 16px; padding: 60px 30px; text-align: center; color: var(--gray-light); border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 64px; margin-bottom: 20px;">📭</div>
                <h3 style="font-size: 20px; font-weight: 700; color: var(--white); margin-bottom: 8px;">暂无投稿记录</h3>
                <p style="margin-bottom: 24px;">开始分享你的第一篇内容吧！</p>
                <a href="<?php echo e(route('submissions.create')); ?>" 
                   style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 15px; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);"
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.5)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.4)'">
                    ✨ 创建第一篇投稿
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submissions->hasPages()): ?>
        <div style="margin-top: 32px;">
            <?php echo e($submissions->links()); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>


<div id="submission-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(5px); z-index: 100000; align-items: center; justify-content: center; padding: 20px;" onclick="closeSubmissionModal(event)">
    <div style="background: var(--dark-light); border-radius: 20px; max-width: 900px; width: 100%; max-height: 85vh; overflow-y: auto; border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 25px 100px rgba(0,0,0,0.6);">
        
        <div style="padding: 24px 30px; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: var(--dark-light); z-index: 10; border-radius: 20px 20px 0 0;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div id="modal-type-icon" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px;">📄</div>
                <div>
                    <h2 id="modal-title" style="margin: 0; color: var(--white); font-size: 20px; font-weight: 700; line-height: 1.3;">投稿详情</h2>
                    <div id="modal-meta" style="font-size: 12px; color: var(--gray); margin-top: 2px;">提交时间</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span id="modal-status" style="padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; background: rgba(245, 158, 11, 0.15); color: #fbbf24;">⏳ 待审核</span>
                <button onclick="closeSubmissionModal()" style="background: rgba(255,255,255,0.08); border: none; color: var(--gray-light); font-size: 24px; cursor: pointer; padding: 0; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.color='var(--white)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'; this.style.color='var(--gray-light)'">×</button>
            </div>
        </div>
        
        
        <div id="modal-content" style="padding: 30px;">
            <div style="text-align: center; color: var(--gray-light); padding: 60px 20px;">
                <div style="font-size: 48px; margin-bottom: 16px; animation: pulse 2s infinite;">🔄</div>
                <div style="font-size: 15px;">加载中...</div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 0.6; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.05); }
}
#submission-modal .trix-content {
    color: var(--gray-light) !important;
    line-height: 1.7 !important;
}
#submission-modal .trix-content h1,
#submission-modal .trix-content h2,
#submission-modal .trix-content h3 {
    color: var(--white) !important;
    margin-top: 20px;
    margin-bottom: 10px;
}
#submission-modal .trix-content p {
    margin-bottom: 12px;
}
#submission-modal .trix-content ul,
#submission-modal .trix-content ol {
    padding-left: 24px;
    margin-bottom: 12px;
}
#submission-modal .trix-content blockquote {
    border-left: 3px solid var(--primary);
    padding-left: 16px;
    margin: 16px 0;
    color: var(--gray-light);
    font-style: italic;
}
#submission-modal .trix-content pre {
    background: rgba(0,0,0,0.3);
    border-radius: 8px;
    padding: 16px;
    overflow-x: auto;
    font-family: 'Fira Code', monospace;
    font-size: 13px;
}
</style>

<script>
// 查看投稿详情
function viewSubmissionDetail(id) {
    const modal = document.getElementById('submission-modal');
    const content = document.getElementById('modal-content');
    const title = document.getElementById('modal-title');
    const meta = document.getElementById('modal-meta');
    const typeIcon = document.getElementById('modal-type-icon');
    const status = document.getElementById('modal-status');
    
    modal.style.display = 'flex';
    content.innerHTML = '<div style="text-align: center; color: var(--gray-light); padding: 60px 20px;"><div style="font-size: 48px; margin-bottom: 16px; animation: pulse 2s infinite;">🔄</div><div style="font-size: 15px;">加载中...</div></div>';
    
    fetch(`/submissions/${id}`)
        .then(res => res.json())
        .then(data => {
            // 更新头部信息
            title.textContent = data.title || '投稿详情';
            meta.textContent = `提交于 ${data.created_at}`;
            
            const typeIcons = {'document': '📄', 'project': '🚀', 'job': '💼', 'knowledge': '📚'};
            const typeNames = {'document': '文档', 'project': '项目', 'job': '职位', 'knowledge': '知识库'};
            const statusConfig = {
                'pending': {'bg': 'rgba(245, 158, 11, 0.15)', 'color': '#fbbf24', 'text': '⏳ 待审核'},
                'approved': {'bg': 'rgba(16, 185, 129, 0.15)', 'color': '#10b981', 'text': '✅ 已通过'},
                'rejected': {'bg': 'rgba(239, 68, 68, 0.15)', 'color': '#ef4444', 'text': '❌ 已驳回'}
            };
            
            // 更新类型图标
            typeIcon.textContent = typeIcons[data.type] || '📄';
            typeIcon.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            
            // 更新状态标签
            const config = statusConfig[data.status] || statusConfig['pending'];
            status.style.background = config.bg;
            status.style.color = config.color;
            status.textContent = config.text;
            
            content.innerHTML = `
                ${data.summary ? `
                    <div style="margin-bottom: 24px;">
                        <div style="font-weight: 600; color: var(--white); margin-bottom: 10px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <span>📋</span> 摘要
                        </div>
                        <div style="padding: 16px; background: rgba(255,255,255,0.03); border-radius: 12px; color: var(--gray-light); line-height: 1.7; border: 1px solid rgba(255,255,255,0.06);">
                            ${data.summary}
                        </div>
                    </div>
                ` : ''}
                
                ${data.content ? `
                    <div style="margin-bottom: 24px;">
                        <div style="font-weight: 600; color: var(--white); margin-bottom: 12px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <span>📝</span> 正文内容
                        </div>
                        <div class="trix-content" style="padding: 20px; background: rgba(255,255,255,0.03); border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">${data.content}</div>
                    </div>
                ` : ''}
                
                ${data.review_note ? `
                    <div style="padding: 20px; border-radius: 12px; background: ${data.status === 'rejected' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(255,255,255,0.05)'}; border: 1px solid ${data.status === 'rejected' ? 'rgba(239, 68, 68, 0.3)' : 'rgba(255,255,255,0.08)'};">
                        <div style="font-weight: 700; margin-bottom: 10px; color: ${data.status === 'rejected' ? '#f87171' : 'var(--gray)'}; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            ${data.status === 'rejected' ? '🚫 驳回原因' : '📝 审核备注'}
                        </div>
                        <div style="color: var(--gray-light); line-height: 1.7; white-space: pre-wrap;">${data.review_note}</div>
                    </div>
                ` : ''}
                
                ${data.status === 'rejected' ? `
                    <div style="margin-top: 30px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.08); text-align: center;">
                        <a href="/submissions/${id}/edit" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 15px; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.5)'"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.4)'">
                            ✏️ 重新编辑并提交
                        </a>
                    </div>
                ` : ''}
            `;
        })
        .catch(err => {
            content.innerHTML = '<div style="text-align: center; color: #ef4444; padding: 60px 20px;"><div style="font-size: 48px; margin-bottom: 16px;">❌</div><div style="font-size: 15px;">加载失败，请稍后重试</div></div>';
        });
}

// 关闭模态框
function closeSubmissionModal(e) {
    if (!e || e.target.id === 'submission-modal') {
        document.getElementById('submission-modal').style.display = 'none';
    }
}

// ESC 键关闭
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSubmissionModal();
    }
});
</script>

<style>
/* 模态框内容样式 */
#submission-modal .trix-content {
    color: var(--gray-light) !important;
}
#submission-modal .trix-content h1,
#submission-modal .trix-content h2,
#submission-modal .trix-content h3 {
    color: var(--white) !important;
    margin-top: 20px;
    margin-bottom: 10px;
}
#submission-modal .trix-content p {
    margin-bottom: 12px;
}
#submission-modal .trix-content ul,
#submission-modal .trix-content ol {
    padding-left: 24px;
    margin-bottom: 12px;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/submissions/index.blade.php ENDPATH**/ ?>