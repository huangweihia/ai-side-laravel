<?php $__env->startSection('title', '重新编辑投稿 - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 900px; margin: 0 auto; padding: 40px 20px;">
    
    
    <div style="text-align: center; margin-bottom: 40px;">
        <?php
            $typeIcons = ['document' => '📄', 'project' => '🚀', 'job' => '💼', 'knowledge' => '📚'];
            $typeNames = ['document' => '文档', 'project' => '项目', 'job' => '职位', 'knowledge' => '知识库'];
            $typeName = $typeNames[$submission->type] ?? '投稿';
            $typeIcon = $typeIcons[$submission->type] ?? '📝';
        ?>
        <div style="display: inline-flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <span style="font-size: 40px;"><?php echo e($typeIcon); ?></span>
            <h1 style="font-size: 32px; font-weight: 800; color: var(--white); margin: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                重新编辑<?php echo e($typeName); ?>

            </h1>
        </div>
        <p style="color: var(--gray-light); font-size: 15px;">
            根据审核意见修改后重新提交
        </p>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->review_note): ?>
        <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 16px; padding: 20px 24px; margin-bottom: 32px;">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="font-size: 24px;">🚫</div>
                <div style="flex: 1;">
                    <div style="font-weight: 700; color: #f87171; margin-bottom: 8px; font-size: 15px;">审核意见</div>
                    <div style="color: var(--gray-light); line-height: 1.7; white-space: pre-wrap;"><?php echo e($submission->review_note); ?></div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('submissions.update', $submission->id)); ?>" id="submission-form">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="form-section" style="background: var(--dark-light); border-radius: 16px; padding: 24px; margin-bottom: 24px; border: 1px solid rgba(255,255,255,0.08);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <?php echo e($typeIcon); ?>

                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--white); margin: 0;"><?php echo e($typeName); ?>信息</h3>
                    <p style="font-size: 13px; color: var(--gray); margin: 4px 0 0 0;">当前投稿类型（不可修改）</p>
                </div>
            </div>

            <div style="padding: 14px 18px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; color: var(--white); font-size: 15px;">
                <?php echo e($typeIcon); ?> <?php echo e($typeName); ?>

            </div>
            <input type="hidden" name="type" value="<?php echo e($submission->type); ?>">
        </div>

        
        <div class="form-section" style="background: var(--dark-light); border-radius: 16px; padding: 24px; margin-bottom: 24px; border: 1px solid rgba(255,255,255,0.08);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    ✏️
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--white); margin: 0;">基本信息</h3>
                    <p style="font-size: 13px; color: var(--gray); margin: 4px 0 0 0;">修改投稿的核心信息</p>
                </div>
            </div>

            <div style="display: grid; gap: 20px;">
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; color: var(--white); margin-bottom: 8px;">
                        标题 <span style="color: #ef4444;">*</span>
                    </label>
                    <input name="title" required maxlength="255" value="<?php echo e(old('title', $submission->title)); ?>" 
                           placeholder="请输入吸引人的标题..." 
                           style="width: 100%; padding: 14px 16px; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; color: var(--white); font-size: 15px; transition: all 0.2s;"
                           onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.15)'"
                           onblur="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.boxShadow='none'">
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; color: var(--white); margin-bottom: 8px;">
                        摘要 <span style="color: var(--gray); font-weight: 400;">（可选）</span>
                    </label>
                    <textarea name="summary" rows="3" maxlength="500" 
                              placeholder="简要描述内容亮点..." 
                              style="width: 100%; padding: 14px 16px; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; color: var(--white); font-size: 15px; resize: vertical; transition: all 0.2s;"
                              onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.15)'"
                              onblur="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.boxShadow='none'"><?php echo e(old('summary', $submission->summary)); ?></textarea>
                    <div style="font-size: 12px; color: var(--gray); margin-top: 6px; text-align: right;">
                        <span id="summary-count"><?php echo e(strlen($submission->summary ?? '')); ?></span>/500
                    </div>
                </div>
            </div>
        </div>

        
        <div class="form-section" style="background: var(--dark-light); border-radius: 16px; padding: 24px; margin-bottom: 24px; border: 1px solid rgba(255,255,255,0.08);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    📝
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--white); margin: 0;">正文内容 <span style="color: #ef4444;">*</span></h3>
                    <p style="font-size: 13px; color: var(--gray); margin: 4px 0 0 0;">修改和完善你的内容</p>
                </div>
            </div>

            <input id="content" type="hidden" name="content" value="<?php echo e(old('content', $submission->content)); ?>" required>
            <div style="background: rgba(0,0,0,0.2); border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.15);">
                <trix-editor input="content" 
                             placeholder="开始撰写精彩内容..." 
                             style="min-height: 350px; background: transparent; color: var(--white); font-size: 15px; line-height: 1.7; padding: 16px;"></trix-editor>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px;">
                <div style="font-size: 12px; color: var(--gray);">
                    💡 提示：支持富文本编辑，可以添加图片、标题、列表等格式
                </div>
                <div style="font-size: 12px; color: var(--gray);">
                    <span id="content-count">0</span> 字
                </div>
            </div>
        </div>

        
        <div class="form-section" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%); border-radius: 16px; padding: 24px; margin-bottom: 24px; border: 1px solid rgba(99, 102, 241, 0.3);">
            <div style="display: flex; align-items: flex-start; gap: 16px;">
                <label style="display: flex; align-items: flex-start; gap: 14px; cursor: pointer; flex: 1;">
                    <input type="checkbox" name="is_paid" value="1" <?php echo e(old('is_paid', $submission->is_paid) ? 'checked' : ''); ?>

                           style="width: 22px; height: 22px; margin-top: 2px; accent-color: #6366f1; cursor: pointer;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <span style="font-size: 16px; font-weight: 700; color: var(--white);">⭐ 设为 VIP 专属内容</span>
                        </div>
                        <p style="font-size: 13px; color: var(--gray-light); line-height: 1.6; margin: 0;">
                            开启后仅 VIP 用户可观看完整内容，普通用户只能看到摘要。
                        </p>
                    </div>
                </label>
            </div>
        </div>

        
        <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 8px;">
            <a href="<?php echo e(route('submissions.index')); ?>" 
               style="padding: 14px 32px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); color: var(--gray-light); text-decoration: none; font-weight: 600; font-size: 15px; transition: all 0.2s;"
               onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.3)'; this.style.color='var(--white)'"
               onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(255,255,255,0.2)'; this.style.color='var(--gray-light)'">
                取消
            </a>
            <button type="submit" 
                    style="padding: 14px 40px; border: none; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 700; font-size: 15px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.5)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.4)'">
                ✨ 重新提交审核
            </button>
        </div>
    </form>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

<style>
/* Trix 暗色主题适配（与 create.blade.php 相同） */
trix-toolbar {
    position: sticky;
    top: 0;
    z-index: 10;
    background: rgba(30, 41, 59, 0.95);
    backdrop-filter: blur(10px);
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    padding: 12px 16px;
}

trix-toolbar .trix-button-row {
    border-bottom: none !important;
    gap: 8px;
}

trix-toolbar .trix-button-group {
    border: none !important;
    margin: 0 !important;
}

trix-toolbar .trix-button {
    color: #94a3b8 !important;
    border-color: transparent !important;
    background: transparent !important;
    border-radius: 6px !important;
    padding: 6px 10px !important;
    transition: all 0.2s !important;
}

trix-toolbar .trix-button:hover {
    background: rgba(255,255,255,0.1) !important;
    color: #f1f5f9 !important;
}

trix-toolbar .trix-button.trix-active {
    background: rgba(99, 102, 241, 0.3) !important;
    color: #f1f5f9 !important;
}

trix-toolbar .trix-button--icon::before {
    opacity: 1 !important;
}

trix-editor {
    min-height: 350px !important;
    color: #f1f5f9 !important;
    line-height: 1.7 !important;
}

trix-editor:focus {
    outline: none !important;
}

.trix-content {
    line-height: 1.7 !important;
}
</style>

<script>
// 摘要字数统计
const summaryInput = document.querySelector('textarea[name="summary"]');
const summaryCount = document.getElementById('summary-count');
if (summaryInput && summaryCount) {
    summaryInput.addEventListener('input', () => {
        summaryCount.textContent = summaryInput.value.length;
    });
}

// 内容字数统计（Trix）
document.addEventListener('trix-change', function(e) {
    const editor = e.target;
    const content = editor.editor.getDocument().toString();
    const countEl = document.getElementById('content-count');
    if (countEl) {
        countEl.textContent = content.length;
    }
});

// 表单验证
document.getElementById('submission-form').addEventListener('submit', function(e) {
    const content = document.getElementById('content').value;
    if (!content || content.trim().length === 0) {
        e.preventDefault();
        alert('请输入正文内容');
        document.querySelector('trix-editor').focus();
        return false;
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/submissions/edit.blade.php ENDPATH**/ ?>