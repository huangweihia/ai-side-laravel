

<?php $__env->startSection('title', $document->title . ' - ' . $knowledgeBase->title); ?>

<?php $__env->startSection('content'); ?>
<section style="padding: 40px 0 24px; background: linear-gradient(135deg, rgba(99,102,241,0.1) 0%, rgba(139,92,246,0.06) 100%); border-bottom: 1px solid rgba(255,255,255,0.08);">
    <div class="container">
        <nav style="font-size: 14px; color: var(--gray-light); margin-bottom: 16px;">
            <a href="<?php echo e(route('knowledge.index')); ?>" style="color: var(--primary-light); text-decoration: none;">知识库</a>
            <span style="margin: 0 8px;">/</span>
            <a href="<?php echo e(route('knowledge.show', $knowledgeBase)); ?>" style="color: var(--primary-light); text-decoration: none;"><?php echo e($knowledgeBase->title); ?></a>
            <span style="margin: 0 8px;">/</span>
            <span style="color: var(--white);"><?php echo e(\Illuminate\Support\Str::limit($document->title, 40)); ?></span>
        </nav>
        <h1 style="font-size: 28px; font-weight: 800; margin: 0 0 12px; color: var(--white); line-height: 1.3;">
            <?php echo e($document->title); ?>

        </h1>
        <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 13px; color: var(--gray-light);">
            <span>👁 <?php echo e(number_format($document->view_count ?? 0)); ?> 次浏览</span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($document->file_type): ?>
                <span>格式：<?php echo e(strtoupper($document->file_type)); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <span>更新于 <?php echo e($document->updated_at?->format('Y-m-d H:i') ?? ''); ?></span>
        </div>
    </div>
</section>

<section style="padding: 32px 0 48px;">
    <div class="container">
        <div class="card" style="padding: 28px 32px; max-width: 900px; margin: 0 auto; border: 1px solid rgba(255,255,255,0.08);">
            <div style="color: var(--white); font-size: 15px; line-height: 1.85; white-space: pre-wrap; word-break: break-word;">
                <?php echo e($document->content); ?>

            </div>
        </div>

        <div style="max-width: 900px; margin: 40px auto 0;">
            <h2 style="font-size: 20px; font-weight: 700; color: var(--white); margin: 0 0 24px;">
                💬 评论 (<span id="kb-doc-comment-count"><?php echo e($commentsTotal); ?></span>)
            </h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <div style="margin-bottom: 32px;">
                    <textarea id="kb-doc-comment-content"
                              placeholder="分享你的看法…"
                              style="
                                  width: 100%;
                                  min-height: 100px;
                                  padding: 16px;
                                  border: 2px solid #e2e8f0;
                                  border-radius: 12px;
                                  font-size: 14px;
                                  font-family: inherit;
                                  resize: vertical;
                                  transition: border-color 0.3s;
                                  background: #fff;
                                  color: #1e293b;
                              "
                              onfocus="this.style.borderColor='#667eea'"
                              onblur="this.style.borderColor='#e2e8f0'"
                    ></textarea>
                    <div id="kb-doc-reply-state" style="display:none; margin-top:10px; padding:8px 12px; border-radius:10px; background:rgba(102,126,234,.12); color:#cbd5e1; font-size:13px;">
                        <span id="kb-doc-reply-state-text"></span>
                        <button type="button" onclick="cancelKbDocReply()" style="margin-left:10px; border:none; background:transparent; color:#a5b4fc; cursor:pointer; font-weight:700;">取消回复</button>
                    </div>
                    <div style="text-align: right; margin-top: 12px;">
                        <button type="button" onclick="submitKbDocComment()"
                                style="
                                    padding: 12px 32px;
                                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                    color: white;
                                    border: none;
                                    border-radius: 50px;
                                    font-size: 14px;
                                    font-weight: 700;
                                    cursor: pointer;
                                    transition: all 0.3s;
                                "
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.4)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                        >发表评论</button>
                    </div>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 32px; background: rgba(102, 126, 234, 0.08); border-radius: 12px; margin-bottom: 32px; border: 1px solid rgba(255,255,255,0.08);">
                    <p style="color: var(--gray-light); margin-bottom: 16px;">登录后才能发表评论</p>
                    <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
                        <a href="<?php echo e(route('login', ['redirect' => request()->fullUrl()])); ?>"
                           style="padding: 12px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50px; font-size: 14px; font-weight: 700; text-decoration: none; display: inline-block;">立即登录</a>
                        <button type="button" onclick="promptLoginKbDocComment()"
                            style="padding: 12px 32px; background: rgba(15,23,42,0.6); border: 2px dashed rgba(148,163,184,0.5); color: #e2e8f0; border-radius: 50px; font-size: 14px; font-weight: 700; cursor: pointer;">发表评论</button>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <style>
                .kb-doc-comment-item .reply-btn { opacity: 0; transition: opacity 0.15s ease; }
                .kb-doc-comment-item .comment-item-head:hover .reply-btn,
                .kb-doc-comment-item .comment-item-head:focus-within .reply-btn,
                .kb-doc-comment-item .reply-item:hover .reply-btn,
                .kb-doc-comment-item .reply-item:focus-within .reply-btn { opacity: 1; }
            </style>

            <?php
                $totalComments = $comments->count();
                $showLastN = 5;
                $shouldCollapse = $totalComments > 10;
                $startIndex = $shouldCollapse ? max(0, $totalComments - $showLastN) : 0;
            ?>

            <div id="kb-doc-comment-list" style="display: grid; gap: 16px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isFeatured = isset($featuredComment) && $featuredComment && $featuredComment->id === $comment->id;
                        $isVisible = !$shouldCollapse || $idx >= $startIndex || $isFeatured;
                    ?>
                    <div class="kb-doc-comment-item comment-item"
                         data-comment-id="<?php echo e($comment->id); ?>"
                         data-parent-name="<?php echo e($comment->user->name ?? '匿名用户'); ?>"
                         style="
                            padding: 20px;
                            border-radius: 12px;
                            background: #f0f7ff;
                            <?php echo e(!$isVisible ? 'display:none;' : ''); ?>;
                            word-wrap: break-word;
                            word-break: break-word;
                            overflow-wrap: break-word;
                            border: 1px solid rgba(99,102,241,0.15);
                        ">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($featuredComment) && $featuredComment && $featuredComment->id === $comment->id): ?>
                            <div style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px; padding: 4px 10px; border-radius: 999px; background: rgba(245, 158, 11, 0.12); color: #d97706; font-size: 12px; font-weight: 700;">
                                <span>🏆</span><span>精品评论</span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div style="display: flex; gap: 16px; align-items: flex-start;">
                            <a href="<?php echo e(route('users.show', $comment->user_id)); ?>" title="查看用户主页" style="flex-shrink: 0; text-decoration: none;">
                                <img src="<?php echo e($comment->user?->avatarUrl()); ?>" alt="" width="40" height="40" loading="lazy" decoding="async"
                                    style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; display: block; border: 1px solid rgba(99,102,241,0.25); background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            </a>
                            <div style="flex: 1; min-width: 0;">
                                <div class="comment-item-head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <div>
                                        <a href="<?php echo e(route('users.show', $comment->user_id)); ?>" style="color: #1e293b; font-weight: 600; text-decoration:none; font-size: 15px;"><?php echo e($comment->user->name ?? '匿名用户'); ?></a>
                                        <span style="color: #94a3b8; font-size: 13px; margin-left: 12px;"><?php echo e($comment->created_at->format('Y-m-d H:i')); ?></span>
                                    </div>
                                    <div style="display: flex; gap: 8px; align-items:center;">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                                        <button type="button"
                                            onclick="toggleCommentLike(<?php echo e($comment->id); ?>, this)"
                                            style="border: 1px solid <?php echo e(in_array($comment->id, $likedCommentIds ?? []) ? 'rgba(239, 68, 68, 0.4)' : '#e2e8f0'); ?>; background: <?php echo e(in_array($comment->id, $likedCommentIds ?? []) ? 'rgba(239, 68, 68, 0.12)' : '#fff'); ?>; border-radius: 999px; padding: 4px 10px; color: #ef4444; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s;">
                                            👍 <span class="comment-like-count"><?php echo e($comment->like_count ?? 0); ?></span>
                                        </button>
                                        <button type="button"
                                            onclick="startKbDocReply(<?php echo e($comment->id); ?>, '<?php echo e(addslashes($comment->user->name ?? '匿名用户')); ?>')"
                                            style="border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 4px 10px; color: #667eea; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s;"
                                            class="reply-btn">回复</button>
                                        <?php else: ?>
                                        <span style="font-size:12px; color:#94a3b8;">👍 <?php echo e($comment->like_count ?? 0); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                                <p style="color: #64748b; line-height: 1.6; margin: 0; font-size: 14px;"><?php echo e($comment->content); ?></p>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($comment->replies->count()): ?>
                                    <?php
                                        $replyCount = $comment->replies->count();
                                        $shouldCollapseReplies = $replyCount > 10;
                                        $showLastNReplies = 3;
                                        $replyStartIndex = $shouldCollapseReplies ? max(0, $replyCount - $showLastNReplies) : 0;
                                    ?>
                                    <div class="replies-container" data-comment-id="<?php echo e($comment->id); ?>" style="margin-top: 16px; padding-left: 24px; border-left: 2px solid #dbeafe;">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ridx => $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $isReplyVisible = !$shouldCollapseReplies || $ridx >= $replyStartIndex;
                                            ?>
                                            <div class="reply-item" style="padding: 12px 0; border-bottom: 1px solid #e2e8f0; <?php echo e(!$isReplyVisible ? 'display:none;' : ''); ?>; word-wrap: break-word; word-break: break-word; overflow-wrap: break-word;">
                                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; gap:8px;">
                                                    <div style="display: flex; gap: 10px; align-items: center; min-width: 0;">
                                                        <a href="<?php echo e(route('users.show', $reply->user_id)); ?>" title="查看用户主页" style="flex-shrink: 0;">
                                                            <img src="<?php echo e($reply->user?->avatarUrl()); ?>" alt="" width="32" height="32" loading="lazy" decoding="async"
                                                                style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; display: block; border: 1px solid rgba(99,102,241,0.2); background: #e2e8f0;">
                                                        </a>
                                                        <div style="min-width: 0;">
                                                            <a href="<?php echo e(route('users.show', $reply->user_id)); ?>" style="color: #1e293b; font-weight: 600; font-size: 14px; text-decoration: none;"><?php echo e($reply->user->name ?? '匿名用户'); ?></a>
                                                            <span style="color: #94a3b8; font-size: 12px; margin-left: 8px;"><?php echo e($reply->created_at->format('Y-m-d H:i')); ?></span>
                                                        </div>
                                                    </div>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                                                    <button type="button"
                                                        onclick="startKbDocReply(<?php echo e($comment->id); ?>, '<?php echo e(addslashes($reply->user->name ?? '匿名用户')); ?>', <?php echo e($reply->id); ?>)"
                                                        style="border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 4px 10px; color: #667eea; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s;"
                                                        class="reply-btn">回复</button>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($reply->replyTo): ?>
                                                    <div style="margin-bottom:6px; padding:6px 10px; border-radius:8px; background:#fff; color:#64748b; font-size:12px;">
                                                        <span style="color: #667eea; font-weight: 600;"><?php echo e($reply->replyTo->user->name ?? '匿名用户'); ?></span>：<?php echo e(\Illuminate\Support\Str::limit($reply->replyTo->content, 40)); ?>

                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <p style="color: #64748b; font-size: 14px; line-height: 1.5; margin: 0;"><?php echo e($reply->content); ?></p>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shouldCollapseReplies): ?>
                                            <div style="padding: 8px 0;">
                                                <button type="button" onclick="toggleKbDocRepliesExpand('<?php echo e($comment->id); ?>', <?php echo e($replyCount); ?>)"
                                                    style="padding: 6px 14px; border-radius: 999px; border: 1px solid #e2e8f0; background: #fff; color: #667eea; cursor: pointer; font-size: 12px; font-weight: 600;">
                                                    查看全部 <?php echo e($replyCount); ?> 条回复
                                                </button>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div id="kb-doc-comment-empty" style="text-align: center; padding: 60px; color: #94a3b8;">
                        <div style="font-size: 64px; margin-bottom: 16px;">💬</div>
                        <p>暂无评论，快来抢沙发吧！</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shouldCollapse): ?>
                <div style="text-align:center; margin-top: 20px;">
                    <button id="kb-doc-toggle-comments-btn" type="button" onclick="toggleKbDocCommentsExpand()"
                        style="padding: 10px 24px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.15); background: rgba(30,41,59,0.6); color: #a5b4fc; cursor:pointer; font-weight:600; font-size: 14px;">
                        查看全部 <?php echo e($totalComments); ?> 条评论
                    </button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 36px;">
            <a href="<?php echo e(route('knowledge.show', $knowledgeBase)); ?>" style="color: var(--gray-light); font-size: 14px; text-decoration: none;">← 返回「<?php echo e($knowledgeBase->title); ?>」</a>
        </div>
    </div>
</section>

<script>
function promptLoginKbDocComment() {
    alert('请先登录后评论');
    window.location.href = <?php echo json_encode(route('login', ['redirect' => request()->fullUrl()]), 512) ?>;
}
</script>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
<script>
let kbDocCommentsExpanded = false;
let kbDocReplyParentId = null;
let kbDocReplyToId = null;
const KB_DOC_SHOW_LAST_N = 5;
const KB_DOC_SHOW_LAST_N_REPLIES = 3;
const kbDocReplyExpandedState = {};

function kbDocCommentUserAvatarUrl(user, fallbackName) {
    const name = fallbackName || user?.name || 'U';
    const a = user?.avatar;
    if (a && String(a).trim()) {
        const s = String(a).trim();
        if (s.startsWith('http://') || s.startsWith('https://')) return s;
        return s.startsWith('/') ? s : '/' + s.replace(/^\//, '');
    }
    return 'https://ui-avatars.com/api/?name=' + encodeURIComponent(name) + '&background=6366f1&color=fff&size=128';
}

function toggleKbDocCommentsExpand() {
    const items = document.querySelectorAll('#kb-doc-comment-list .comment-item');
    const btn = document.getElementById('kb-doc-toggle-comments-btn');
    if (!btn || items.length === 0) return;

    const totalItems = items.length;
    const anchorTop = btn.getBoundingClientRect().top + window.scrollY;

    if (!kbDocCommentsExpanded) {
        items.forEach((item) => { item.style.display = 'block'; });
        kbDocCommentsExpanded = true;
        btn.textContent = `收起评论（共 ${totalItems} 条）`;
    } else {
        const startIndex = Math.max(0, totalItems - KB_DOC_SHOW_LAST_N);
        items.forEach((item, idx) => {
            item.style.display = idx >= startIndex ? 'block' : 'none';
        });
        kbDocCommentsExpanded = false;
        btn.textContent = `查看全部 ${totalItems} 条评论`;
    }

    window.scrollTo({ top: anchorTop - 100, behavior: 'smooth' });
}

function toggleKbDocRepliesExpand(commentId, replyCount) {
    const container = document.querySelector(`.replies-container[data-comment-id="${commentId}"]`);
    if (!container) return;

    const btn = container.querySelector('button[onclick^="toggleKbDocRepliesExpand"]');
    if (!btn) return;

    const replyItems = container.querySelectorAll('.reply-item');
    const isExpanded = kbDocReplyExpandedState[commentId] || false;

    if (!isExpanded) {
        replyItems.forEach((item) => { item.style.display = 'block'; });
        kbDocReplyExpandedState[commentId] = true;
        btn.textContent = `收起回复（共 ${replyCount} 条）`;
        btn.style.background = '#e2e8f0';
    } else {
        const startIndex = Math.max(0, replyCount - KB_DOC_SHOW_LAST_N_REPLIES);
        replyItems.forEach((item, idx) => {
            item.style.display = idx >= startIndex ? 'block' : 'none';
        });
        kbDocReplyExpandedState[commentId] = false;
        btn.textContent = `查看全部 ${replyCount} 条回复`;
        btn.style.background = '#fff';
    }
}

function startKbDocReply(commentId, userName, targetCommentId = null) {
    kbDocReplyParentId = commentId;
    kbDocReplyToId = targetCommentId || commentId;
    const textarea = document.getElementById('kb-doc-comment-content');
    if (!textarea) return;
    textarea.focus();
    if (!textarea.value.trim()) {
        textarea.value = `回复 @${userName}：`;
    }
    const box = document.getElementById('kb-doc-reply-state');
    const text = document.getElementById('kb-doc-reply-state-text');
    if (box && text) {
        text.textContent = `正在回复 ${userName}`;
        box.style.display = 'block';
    }
}

function cancelKbDocReply() {
    kbDocReplyParentId = null;
    kbDocReplyToId = null;
    const box = document.getElementById('kb-doc-reply-state');
    const text = document.getElementById('kb-doc-reply-state-text');
    const textarea = document.getElementById('kb-doc-comment-content');
    if (text) text.textContent = '';
    if (box) box.style.display = 'none';
    if (textarea) textarea.value = '';
}

function kbDocRenderReplyItem(reply) {
    const userName = reply?.user?.name ?? '匿名用户';
    const uid = reply?.user_id ?? reply?.user?.id ?? '';
    const profileUrl = uid ? `<?php echo e(url('/users')); ?>/${uid}` : '#';
    const avatarSrc = kbDocCommentUserAvatarUrl(reply?.user, userName);
    const quotedUser = reply?.reply_to?.user?.name || '';
    const quotedContent = reply?.reply_to?.content || '';

    return `
        <div class="reply-item" style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; gap:8px;">
                <div style="display: flex; gap: 10px; align-items: center; min-width: 0;">
                    <a href="${profileUrl}" title="查看用户主页" style="flex-shrink: 0;">
                        <img src="${avatarSrc}" alt="" width="32" height="32" decoding="async" style="width:32px;height:32px;border-radius:50%;object-fit:cover;display:block;border:1px solid rgba(99,102,241,0.2);background:#e2e8f0;">
                    </a>
                    <div style="min-width:0;">
                        <a href="${profileUrl}" style="color: #1e293b; font-weight: 600; font-size: 14px; text-decoration: none;">${userName}</a>
                        <span style="color: #94a3b8; font-size: 12px; margin-left: 8px;">刚刚</span>
                    </div>
                </div>
                <button type="button" class="reply-btn"
                    onclick="startKbDocReply(${reply?.parent_id ?? 0}, '${String(userName).replace(/'/g, "\\'")}', ${reply?.id ?? 0})"
                    style="border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 4px 10px; color: #667eea; cursor: pointer; font-size: 12px; font-weight: 600;">回复</button>
            </div>
            ${quotedUser ? `<div style="margin-bottom:6px; padding:6px 10px; border-radius:8px; background:#f8fafc; color:#64748b; font-size:12px;">引用 ${quotedUser}：${String(quotedContent).slice(0, 40)}</div>` : ''}
            <p style="color: #64748b; font-size: 14px; line-height: 1.5; margin: 0;">${reply?.content ?? ''}</p>
        </div>
    `;
}

function kbDocAppendReplyToComment(reply) {
    const parentId = reply?.parent_id;
    if (!parentId) return;

    const commentItem = document.querySelector(`#kb-doc-comment-list .comment-item[data-comment-id="${parentId}"]`);
    if (!commentItem) return;

    let container = commentItem.querySelector('.replies-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'replies-container';
        container.setAttribute('data-comment-id', String(parentId));
        container.style.marginTop = '16px';
        container.style.paddingLeft = '24px';
        container.style.borderLeft = '2px solid #dbeafe';
        commentItem.querySelector('div[style*="flex: 1"]')?.appendChild(container);
    }

    container.insertAdjacentHTML('beforeend', kbDocRenderReplyItem(reply));

    const lastReply = container.lastElementChild;
    if (lastReply) {
        lastReply.style.background = 'rgba(102,126,234,0.06)';
        lastReply.style.borderRadius = '8px';
        lastReply.style.padding = '8px';
        lastReply.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => {
            lastReply.style.background = 'transparent';
            lastReply.style.padding = '';
        }, 1200);
    }
}

function kbDocRenderCommentItem(comment) {
    const userName = comment?.user?.name ?? '匿名用户';
    const content = comment?.content ?? '';
    const uid = comment?.user_id ?? comment?.user?.id ?? '';
    const profileUrl = uid ? `<?php echo e(url('/users')); ?>/${uid}` : '#';
    const avatarSrc = kbDocCommentUserAvatarUrl(comment?.user, userName);

    return `
        <div class="kb-doc-comment-item comment-item" data-comment-id="${comment?.id ?? ''}" data-parent-name="${String(userName).replace(/"/g, '&quot;')}"
            style="padding: 20px; border-radius: 12px; background: #f0f7ff; word-wrap: break-word; border: 1px solid rgba(99,102,241,0.15);">
            <div style="display: flex; gap: 16px; align-items: flex-start;">
                <a href="${profileUrl}" title="查看用户主页" style="flex-shrink:0; text-decoration:none;">
                    <img src="${avatarSrc}" alt="" width="40" height="40" decoding="async" style="width:40px;height:40px;border-radius:50%;object-fit:cover;display:block;border:1px solid rgba(99,102,241,0.25);background:linear-gradient(135deg,#667eea,#764ba2);">
                </a>
                <div style="flex: 1;">
                    <div class="comment-item-head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <div>
                            <a href="${profileUrl}" style="color: #1e293b; font-weight: 600; text-decoration: none; font-size: 15px;">${userName}</a>
                            <span style="color: #94a3b8; font-size: 13px; margin-left: 12px;">刚刚</span>
                        </div>
                        <div style="display: flex; gap: 8px; align-items:center;">
                            <button type="button"
                                onclick="toggleCommentLike(${comment?.id ?? 0}, this)"
                                style="border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 4px 10px; color: #ef4444; cursor: pointer; font-size: 12px; font-weight: 600;">
                                👍 <span class="comment-like-count">${comment?.like_count ?? 0}</span>
                            </button>
                            <button type="button" class="reply-btn"
                                onclick="startKbDocReply(${comment?.id ?? 0}, '${String(userName).replace(/'/g, "\\'")}')"
                                style="border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 4px 10px; color: #667eea; cursor: pointer; font-size: 12px; font-weight: 600;">回复</button>
                        </div>
                    </div>
                    <p style="color: #64748b; line-height: 1.6; margin: 0; font-size: 14px;">${content}</p>
                </div>
            </div>
        </div>
    `;
}

function submitKbDocComment() {
    const textarea = document.getElementById('kb-doc-comment-content');
    if (!textarea) return;
    const content = textarea.value.trim();
    if (!content) {
        alert('请输入评论内容');
        return;
    }

    fetch(<?php echo json_encode(route('knowledge.documents.comments.store', $document), 512) ?>, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            content: content,
            parent_id: kbDocReplyParentId,
            reply_to_id: kbDocReplyToId,
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (!data.success) {
                alert(data.message || '发表失败');
                return;
            }
            const list = document.getElementById('kb-doc-comment-list');
            const empty = document.getElementById('kb-doc-comment-empty');
            const countEl = document.getElementById('kb-doc-comment-count');

            if (empty) empty.remove();

            if (kbDocReplyParentId) {
                kbDocAppendReplyToComment(data.comment);
            } else if (list) {
                list.insertAdjacentHTML('afterbegin', kbDocRenderCommentItem(data.comment));
                const next = Number(data.total ?? (parseInt(countEl?.textContent || '0', 10) + 1));
                if (countEl) countEl.textContent = String(next);
            }

            kbDocReplyParentId = null;
            kbDocReplyToId = null;
            cancelKbDocReply();
        })
        .catch(() => alert('网络异常，请稍后重试'));
}
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
<script>
function submitKbDocComment() {
    promptLoginKbDocComment();
}
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/knowledge/document.blade.php ENDPATH**/ ?>