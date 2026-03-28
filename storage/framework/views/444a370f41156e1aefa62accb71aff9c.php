<?php $__env->startSection('title', $article->title . ' - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>


<div id="reading-progress" style="
    position: fixed;
    top: 0;
    left: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    z-index: 9999;
    width: 0%;
    transition: width 0.1s;
"></div>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    
    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 40px;">
        
        
        <div>
            
            <a href="<?php echo e(route('articles.index')); ?>" 
               style="display: inline-flex; align-items: center; gap: 8px; color: #667eea; text-decoration: none; font-weight: 600; margin-bottom: 24px;"
               onmouseover="this.style.color='#764ba2'"
               onmouseout="this.style.color='#667eea'"
            >
                <span>←</span> 返回文章列表
            </a>
            
            
            <h1 style="color: #1e293b; font-size: 36px; font-weight: 800; margin-bottom: 24px; line-height: 1.4;">
                <?php echo e($article->title); ?>

            </h1>
            
            
            <div style="display: flex; gap: 24px; color: #64748b; font-size: 14px; margin-bottom: 24px; flex-wrap: wrap; align-items: center;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->author): ?>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="
                        width: 32px;
                        height: 32px;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;
                        font-weight: 700;
                        color: white;
                    ">
                        <?php echo e(substr($article->author->name ?? 'A', 0, 1)); ?>

                    </div>
                    <span style="color: #1e293b; font-weight: 600;"><?php echo e($article->author->name ?? '匿名'); ?></span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <span>📅 <?php echo e($article->published_at?->format('Y-m-d') ?? $article->created_at->format('Y-m-d')); ?></span>
                <span>👁️ <?php echo e(number_format($article->view_count)); ?> 阅读</span>
                <span>⏱️ <?php echo e($article->reading_time); ?> 分钟阅读</span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->is_vip): ?>
                    <span style="
                        padding: 4px 12px;
                        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
                        color: white;
                        border-radius: 20px;
                        font-size: 12px;
                        font-weight: 700;
                    ">👑 VIP</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            
            
            <div style="display: flex; gap: 12px; margin-bottom: 32px;">
                <button onclick="toggleLike(<?php echo e($article->id); ?>)" 
                        id="btn-like"
                        style="
                            padding: 10px 20px;
                            background: <?php echo e($isLiked ? 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)' : 'rgba(239, 68, 68, 0.1)'); ?>;
                            color: <?php echo e($isLiked ? 'white' : '#ef4444'); ?>;
                            border: <?php echo e($isLiked ? 'none' : '2px solid #ef4444'); ?>;
                            border-radius: 50px;
                            font-weight: 600;
                            cursor: pointer;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            transition: all 0.3s;
                        "
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(239, 68, 68, 0.3)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <span id="btn-like-icon"><?php echo e($isLiked ? '❤️' : '🤍'); ?></span>
                    <span id="btn-like-text"><?php echo e($isLiked ? '已点赞' : '点赞'); ?></span>
                    <span id="btn-like-count" style="margin-left: 4px; color: <?php echo e($isLiked ? 'rgba(255,255,255,0.9)' : '#94a3b8'); ?>;">(<?php echo e(number_format($article->like_count)); ?>)</span>
                </button>
                
                <button onclick="toggleFavorite(<?php echo e($article->id); ?>)" 
                        id="btn-favorite"
                        style="
                            padding: 10px 20px;
                            background: <?php echo e($isFavorited ? 'linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%)' : 'rgba(245, 158, 11, 0.1)'); ?>;
                            color: <?php echo e($isFavorited ? 'white' : '#f59e0b'); ?>;
                            border: <?php echo e($isFavorited ? 'none' : '2px solid #f59e0b'); ?>;
                            border-radius: 50px;
                            font-weight: 600;
                            cursor: pointer;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            transition: all 0.3s;
                        "
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(245, 158, 11, 0.3)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <span id="btn-favorite-icon"><?php echo e($isFavorited ? '⭐' : '☆'); ?></span>
                    <span id="btn-favorite-text"><?php echo e($isFavorited ? '已收藏' : '收藏'); ?></span>
                    <span id="btn-favorite-count" style="margin-left: 4px; color: <?php echo e($isFavorited ? 'rgba(255,255,255,0.9)' : '#94a3b8'); ?>;">(<?php echo e(number_format($article->favorite_count)); ?>)</span>
                </button>
            </div>
            
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->cover_image): ?>
            <figure style="margin: 0 0 32px 0;">
                <img src="<?php echo e($article->cover_image); ?>"
                     alt="<?php echo e($article->title); ?>"
                     style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);"
                     onerror="this.style.display='none'"
                />
            </figure>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canViewFullArticle && $article->source_url): ?>
            <div style="
                padding: 16px 20px;
                background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
                border: 1px solid rgba(99, 102, 241, 0.2);
                border-radius: 12px;
                margin-bottom: 32px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 12px;
            ">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 20px;">📰</span>
                    <div>
                        <div style="color: #64748b; font-size: 12px; margin-bottom: 4px;">文章来源</div>
                        <div style="color: #1e293b; font-weight: 600;"><?php echo e($article->meta?->source ?? '网络整理'); ?></div>
                    </div>
                </div>
                <a href="<?php echo e($article->source_url); ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   style="
                       padding: 10px 20px;
                       background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                       color: white;
                       border-radius: 50px;
                       font-size: 13px;
                       font-weight: 600;
                       text-decoration: none;
                       display: inline-flex;
                       align-items: center;
                       gap: 6px;
                       transition: all 0.3s;
                   "
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.4)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <span>🔗</span>
                    查看原文
                </a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            
            <div style="margin-top: 30px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canViewFullArticle): ?>
                    <?php echo $article->content; ?>

                <?php else: ?>
                    <div style="padding: 28px 24px; border-radius: 16px; border: 2px solid rgba(251, 191, 36, 0.45); background: linear-gradient(135deg, rgba(30, 41, 59, 0.06) 0%, rgba(15, 23, 42, 0.04) 100%);">
                        <div style="font-size: 40px; text-align: center; margin-bottom: 12px;">👑</div>
                        <p style="color: #475569; font-size: 16px; line-height: 1.85; margin: 0 0 20px;">
                            <?php echo e($article->summary ?? '本文为 VIP 专属内容，开通会员或使用积分解锁后可阅读全文。'); ?>

                        </p>
                        <div style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: center;">
                            <a href="<?php echo e(route('vip', ['redirect' => request()->fullUrl()])); ?>" style="padding: 12px 28px; border-radius: 12px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: #fff; font-weight: 700; font-size: 15px; text-decoration: none; display: inline-block;">
                                开通 VIP 阅读全文
                            </a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!auth()->user()->isVip()): ?>
                                <button type="button" onclick="unlockArticleBody(<?php echo e($article->id); ?>)" style="padding: 12px 28px; border-radius: 12px; border: 2px dashed #94a3b8; background: #fff; color: #475569; font-weight: 700; cursor: pointer; font-size: 15px;">
                                    使用积分解锁（100 积分）
                                </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canViewFullArticle && $article->meta_keywords): ?>
            <div style="margin-top: 40px; padding-top: 24px; border-top: 2px solid #e2e8f0;">
                <div style="color: #64748b; font-size: 14px; margin-bottom: 12px;">标签：</div>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = explode(',', $article->meta_keywords); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span style="
                            padding: 6px 14px;
                            background: rgba(102, 126, 234, 0.1);
                            color: #667eea;
                            border-radius: 20px;
                            font-size: 13px;
                            font-weight: 600;
                        ">
                            #<?php echo e(trim($keyword)); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            
            <div style="margin-top: 48px; padding-top: 40px; border-top: 2px solid rgba(255,255,255,0.1);">
                <h2 style="font-size: 24px; font-weight: 700; color: var(--white); margin-bottom: 24px;">
                    💬 评论 (<span id="comment-count"><?php echo e($commentsTotal ?? $comments->count()); ?></span>)
                </h2>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <div style="margin-bottom: 32px;">
                        <textarea id="comment-content"
                                  placeholder="分享你的看法..."
                                  style="
                                      width: 100%;
                                      min-height: 100px;
                                      padding: 16px;
                                      border: 2px solid rgba(255,255,255,0.15);
                                      border-radius: 12px;
                                      font-size: 14px;
                                      font-family: inherit;
                                      resize: vertical;
                                      transition: border-color 0.3s;
                                      background: var(--dark-light);
                                      color: var(--white);
                                  "
                                  onfocus="this.style.borderColor='var(--primary)'"
                                  onblur="this.style.borderColor='rgba(255,255,255,0.15)'"
                        ></textarea>
                        <div id="reply-state" style="display:none; margin-top:10px; padding:8px 12px; border-radius:10px; background:rgba(102,126,234,.08); color:#475569; font-size:13px;">
                            <span id="reply-state-text"></span>
                            <button type="button" onclick="cancelReply()" style="margin-left:10px; border:none; background:transparent; color:#667eea; cursor:pointer; font-weight:700;">取消回复</button>
                        </div>
                        <div style="text-align: right; margin-top: 12px;">
                            <button onclick="submitComment()"
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
                            >
                                发表评论
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 32px; background: rgba(102, 126, 234, 0.05); border-radius: 12px; margin-bottom: 32px;">
                        <p style="color: #64748b; margin-bottom: 16px;">登录后才能发表评论</p>
                        <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
                            <a href="<?php echo e(route('login')); ?>?redirect=<?php echo e(urlencode(request()->fullUrl())); ?>"
                               style="
                                   padding: 12px 32px;
                                   background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                   color: white;
                                   border-radius: 50px;
                                   font-size: 14px;
                                   font-weight: 700;
                                   text-decoration: none;
                                   display: inline-block;
                               "
                            >
                                立即登录
                            </a>
                            <button type="button" onclick="promptLoginForComment()"
                                style="padding: 12px 32px; background: white; border: 2px dashed #cbd5e1; color: #475569; border-radius: 50px; font-size: 14px; font-weight: 700; cursor: pointer;">
                                发表评论
                            </button>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                
                <div id="comment-list" style="display: grid; gap: 20px;">
                    <?php
                        $totalComments = $comments->count();
                        $showLastN = 5;
                        $shouldCollapse = $totalComments > 10;
                        $visibleCount = $shouldCollapse ? $showLastN : $totalComments;
                        $startIndex = $shouldCollapse ? max(0, $totalComments - $showLastN) : 0;
                    ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $isVisible = !$shouldCollapse || $idx >= $startIndex;
                        ?>
                        <div class="comment-item" 
                             data-comment-id="<?php echo e($comment->id); ?>" 
                             data-parent-name="<?php echo e($comment->user->name ?? '匿名用户'); ?>" 
                             style="padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.08); <?php echo e(!$isVisible ? 'display:none;' : ''); ?>; word-wrap: break-word; word-break: break-word; overflow-wrap: break-word;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($featuredComment) && $featuredComment && $featuredComment->id === $comment->id): ?>
                                <div style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px; padding: 4px 10px; border-radius: 999px; background: rgba(251, 191, 36, 0.15); color: #fbbf24; font-size: 12px; font-weight: 700;">
                                    <span>🏆</span><span>精品评论</span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div style="display: flex; gap: 16px; align-items: flex-start;">
                                <div style="
                                    width: 48px;
                                    height: 48px;
                                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 20px;
                                    font-weight: 700;
                                    color: white;
                                ">
                                    <?php echo e(substr($comment->user->name ?? 'U', 0, 1)); ?>

                                </div>
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                        <div>
                                            <a href="<?php echo e(route('users.show', $comment->user_id)); ?>" style="color: var(--white); font-weight: 600; text-decoration:none;"><?php echo e($comment->user->name ?? '匿名用户'); ?></a>
                                            <span style="color: var(--gray-light); font-size: 13px; margin-left: 12px;"><?php echo e($comment->created_at->diffForHumans()); ?></span>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <button type="button"
                                                onclick="toggleCommentLike(<?php echo e($comment->id); ?>, this)"
                                                style="border: 1px solid <?php echo e(in_array($comment->id, $likedCommentIds ?? []) ? 'rgba(239, 68, 68, 0.4)' : 'rgba(255,255,255,0.15)'); ?>; background: <?php echo e(in_array($comment->id, $likedCommentIds ?? []) ? 'rgba(239, 68, 68, 0.15)' : 'var(--dark-light)'); ?>; border-radius: 999px; padding: 6px 10px; color: #ef4444; cursor: pointer; font-size: 12px; font-weight: 600;">
                                                👍 <span class="comment-like-count"><?php echo e($comment->like_count ?? 0); ?></span>
                                            </button>
                                            <button type="button"
                                                onclick="startReply(<?php echo e($comment->id); ?>, '<?php echo e(addslashes($comment->user->name ?? '匿名用户')); ?>')"
                                                style="border: 1px solid rgba(255,255,255,0.15); background: var(--dark-light); border-radius: 999px; padding: 6px 10px; color: var(--primary-light); cursor: pointer; font-size: 12px; font-weight: 600;">
                                                回复
                                            </button>
                                        </div>
                                    </div>
                                    <p style="color: var(--gray-light); line-height: 1.6; margin: 0;"><?php echo e($comment->content); ?></p>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($comment->replies->count()): ?>
                                        <?php
                                            $replyCount = $comment->replies->count();
                                            $shouldCollapseReplies = $replyCount > 10;
                                            $showLastNReplies = 3;
                                            $replyStartIndex = $shouldCollapseReplies ? max(0, $replyCount - $showLastNReplies) : 0;
                                        ?>
                                        <div class="replies-container" data-comment-id="<?php echo e($comment->id); ?>" style="margin-top: 12px; padding-left: 16px; border-left: 2px solid rgba(255,255,255,0.1); display:grid; gap: 10px;">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $isReplyVisible = !$shouldCollapseReplies || $idx >= $replyStartIndex;
                                                ?>
                                                <div class="reply-item" style="<?php echo e(!$isReplyVisible ? 'display:none;' : ''); ?>; word-wrap: break-word; word-break: break-word; overflow-wrap: break-word;">
                                                    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:4px;">
                                                        <div style="color:var(--gray-light); font-size:13px;">
                                                            <strong style="color:var(--white);"><?php echo e($reply->user->name ?? '匿名用户'); ?></strong>
                                                            <span style="color:var(--gray); margin-left:8px;"><?php echo e($reply->created_at->diffForHumans()); ?></span>
                                                        </div>
                                                        <button type="button"
                                                            onclick="startReply(<?php echo e($comment->id); ?>, '<?php echo e(addslashes($reply->user->name ?? '匿名用户')); ?>', <?php echo e($reply->id); ?>)"
                                                            style="border: 1px solid rgba(255,255,255,0.15); background: var(--dark-light); border-radius: 999px; padding: 4px 10px; color: var(--primary-light); cursor: pointer; font-size: 12px; font-weight: 600;">
                                                            回复
                                                        </button>
                                                    </div>
                                                    <div style="margin-bottom:6px; padding:6px 10px; border-radius:8px; background:rgba(255,255,255,0.05); color:var(--gray-light); font-size:12px;">
                                                        引用 <?php echo e($comment->user->name ?? '匿名用户'); ?>：<?php echo e(\Illuminate\Support\Str::limit($comment->content, 40)); ?>

                                                    </div>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($reply->replyTo): ?>
                                                        <div style="margin-bottom:6px; padding:6px 10px; border-radius:8px; background:rgba(255,255,255,0.05); color:var(--gray-light); font-size:12px;">
                                                            引用 <?php echo e($reply->replyTo->user->name ?? '匿名用户'); ?>：<?php echo e(\Illuminate\Support\Str::limit($reply->replyTo->content, 40)); ?>

                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <div style="color:var(--gray-light); font-size:14px; line-height:1.6;"><?php echo e($reply->content); ?></div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shouldCollapseReplies): ?>
                                                <div style="padding: 4px 0;">
                                                    <button type="button" onclick="toggleRepliesExpand('<?php echo e($comment->id); ?>', <?php echo e($replyCount); ?>)"
                                                        style="padding: 6px 14px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.15); background: var(--dark-light); color: var(--primary-light); cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s;"
                                                        onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(-1px)'"
                                                        onmouseout="this.style.background='var(--dark-light)'; this.style.transform='translateY(0)'">
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
                        <div id="comment-empty" style="text-align: center; padding: 60px; color: var(--gray-light);">
                            <div style="font-size: 64px; margin-bottom: 16px;">💬</div>
                            <p>暂无评论，快来抢沙发吧！</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shouldCollapse): ?>
                    <div style="text-align:center; margin-top: 20px;">
                        <button id="toggle-comments-btn" type="button" onclick="toggleCommentsExpand()"
                            style="padding: 10px 24px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.15); background: var(--dark-light); color: var(--white); cursor:pointer; font-weight:600; font-size: 14px; transition: all 0.2s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.2)'"
                            onmouseout="this.style.background='var(--dark-light)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            查看全部 <?php echo e($totalComments); ?> 条评论
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            
        </div>
        
        
        <div>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($relatedArticles->count()): ?>
            <div style="background: white; border-radius: 16px; padding: 20px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 16px;">
                    📚 相关文章
                </h3>
                <div style="display: grid; gap: 12px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $relatedArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('articles.show', $related)); ?>"
                           style="
                               padding: 12px;
                               background: rgba(102, 126, 234, 0.05);
                               border-radius: 8px;
                               text-decoration: none;
                               color: inherit;
                               transition: all 0.3s;
                               display: block;
                           "
                           onmouseover="this.style.background='rgba(102, 126, 234, 0.1)'; this.style.transform='translateX(5px)'"
                           onmouseout="this.style.background='rgba(102, 126, 234, 0.05)'; this.style.transform='translateX(0)'"
                        >
                            <div style="font-weight: 600; color: #1e293b; font-size: 14px; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?php echo e(Str::limit($related->title, 40)); ?>

                            </div>
                            <div style="color: #64748b; font-size: 12px;">
                                👁️ <?php echo e($related->view_count ?? 0); ?>

                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            
        </div>
        
    </div>
    
</div>

<script>
<?php if(!$canViewFullArticle): ?>
function unlockArticleBody(articleId) {
    fetch(`/interactions/articles/${articleId}/unlock`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    })
        .then((r) => r.json())
        .then((data) => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || '解锁失败');
            }
        })
        .catch(() => alert('网络异常，请稍后重试'));
}
<?php endif; ?>
// 阅读进度条
window.addEventListener('scroll', function() {
    const article = document.querySelector('.container');
    if (!article) return;

    const articleTop = article.offsetTop;
    const articleHeight = article.offsetHeight;
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const windowHeight = window.innerHeight;

    let progress = ((scrollTop - articleTop) / (articleHeight - windowHeight)) * 100;
    progress = Math.max(0, Math.min(100, progress));

    document.getElementById('reading-progress').style.width = progress + '%';

    const key = `article-progress-<?php echo e($article->id); ?>`;
    localStorage.setItem(key, String(scrollTop));
});

window.addEventListener('load', function() {
    const key = `article-progress-<?php echo e($article->id); ?>`;
    const saved = Number(localStorage.getItem(key) || 0);
    if (saved > 200) {
        setTimeout(() => window.scrollTo({ top: saved, behavior: 'smooth' }), 120);
    }
});

// 点赞功能
function toggleLike(articleId) {
    <?php if(auth()->guard()->check()): ?>
        fetch(`/articles/${articleId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert(data.message || '操作失败');
                return;
            }

            const btn = document.getElementById('btn-like');
            const icon = document.getElementById('btn-like-icon');
            const text = document.getElementById('btn-like-text');
            const count = document.getElementById('btn-like-count');

            if (data.liked) {
                btn.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                btn.style.color = 'white';
                btn.style.border = 'none';
                btn.animate([{ transform: 'scale(1)' }, { transform: 'scale(1.08)' }, { transform: 'scale(1)' }], { duration: 260 });
                icon.textContent = '❤️';
                text.textContent = '已点赞';
                count.style.color = 'rgba(255,255,255,0.9)';
            } else {
                btn.style.background = 'rgba(239, 68, 68, 0.1)';
                btn.style.color = '#ef4444';
                btn.style.border = '2px solid #ef4444';
                icon.textContent = '🤍';
                text.textContent = '点赞';
                count.style.color = '#94a3b8';
            }

            count.textContent = `(${data.like_count ?? 0})`;
        })
        .catch(() => alert('网络异常，请稍后重试'));
    <?php else: ?>
        alert('请先登录后点赞');
        window.location.href = '<?php echo e(route("login")); ?>';
    <?php endif; ?>
}

// 收藏功能
function toggleFavorite(articleId) {
    <?php if(auth()->guard()->check()): ?>
        fetch(`/articles/${articleId}/favorite`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert(data.message || '操作失败');
                return;
            }

            const btn = document.getElementById('btn-favorite');
            const icon = document.getElementById('btn-favorite-icon');
            const text = document.getElementById('btn-favorite-text');
            const count = document.getElementById('btn-favorite-count');

            if (data.isFavorited) {
                btn.style.background = 'linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%)';
                btn.style.color = 'white';
                btn.style.border = 'none';
                btn.animate([{ transform: 'scale(1)' }, { transform: 'scale(1.08)' }, { transform: 'scale(1)' }], { duration: 260 });
                icon.textContent = '⭐';
                text.textContent = '已收藏';
                count.style.color = 'rgba(255,255,255,0.9)';
            } else {
                btn.style.background = 'rgba(245, 158, 11, 0.1)';
                btn.style.color = '#f59e0b';
                btn.style.border = '2px solid #f59e0b';
                icon.textContent = '☆';
                text.textContent = '收藏';
                count.style.color = '#94a3b8';
            }

            count.textContent = `(${data.favorites_count ?? 0})`;
        })
        .catch(() => alert('网络异常，请稍后重试'));
    <?php else: ?>
        alert('请先登录后收藏');
        window.location.href = '<?php echo e(route("login")); ?>';
    <?php endif; ?>
}

let commentsExpanded = false;
let replyParentId = null;
let replyToId = null;
const SHOW_LAST_N = 5;
const SHOW_LAST_N_REPLIES = 3;
const replyExpandedState = {};

function startReply(commentId, userName, targetCommentId = null) {
    replyParentId = commentId;
    replyToId = targetCommentId || commentId;
    const textarea = document.getElementById('comment-content');
    if (!textarea) return;
    textarea.focus();
    if (!textarea.value.trim()) {
        textarea.value = `回复 @${userName}：`;
    }
    showReplyState(userName);
}

function toggleRepliesExpand(commentId, replyCount) {
    const container = document.querySelector(`.replies-container[data-comment-id="${commentId}"]`);
    if (!container) return;
    
    const btn = container.querySelector('button[onclick^="toggleRepliesExpand"]');
    if (!btn) return;
    
    const replyItems = container.querySelectorAll('.reply-item');
    const isExpanded = replyExpandedState[commentId] || false;
    
    if (!isExpanded) {
        replyItems.forEach((item) => {
            item.style.display = 'block';
        });
        replyExpandedState[commentId] = true;
        btn.textContent = `收起回复（共 ${replyCount} 条）`;
        btn.style.background = 'rgba(255,255,255,0.1)';
    } else {
        const startIndex = Math.max(0, replyCount - SHOW_LAST_N_REPLIES);
        replyItems.forEach((item, idx) => {
            item.style.display = idx >= startIndex ? 'block' : 'none';
        });
        replyExpandedState[commentId] = false;
        btn.textContent = `查看全部 ${replyCount} 条回复`;
        btn.style.background = 'var(--dark-light)';
    }
}

function showReplyState(userName) {
    const box = document.getElementById('reply-state');
    const text = document.getElementById('reply-state-text');
    if (!box || !text) return;
    text.textContent = `正在回复 ${userName}`;
    box.style.display = 'block';
}

function cancelReply() {
    replyParentId = null;
    const box = document.getElementById('reply-state');
    const text = document.getElementById('reply-state-text');
    const textarea = document.getElementById('comment-content');
    if (text) text.textContent = '';
    if (box) box.style.display = 'none';
    if (textarea) textarea.value = '';
}

function promptLoginForComment() {
    alert('请先登录后评论');
    window.location.href = '<?php echo e(route("login")); ?>?redirect=<?php echo e(urlencode(request()->fullUrl())); ?>';
}

function toggleCommentsExpand() {
    const items = document.querySelectorAll('#comment-list .comment-item');
    const btn = document.getElementById('toggle-comments-btn');
    if (!btn || items.length === 0) return;

    const totalItems = items.length;
    const anchorTop = btn.getBoundingClientRect().top + window.scrollY;

    if (!commentsExpanded) {
        // 展开：显示全部评论
        items.forEach((item) => {
            item.style.display = 'block';
        });
        commentsExpanded = true;
        btn.textContent = `收起评论（共 ${totalItems} 条）`;
        btn.style.background = 'linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%)';
    } else {
        // 收起：只显示最后 5 条
        const startIndex = Math.max(0, totalItems - SHOW_LAST_N);
        items.forEach((item, idx) => {
            item.style.display = idx >= startIndex ? 'block' : 'none';
        });
        commentsExpanded = false;
        btn.textContent = `查看全部 ${totalItems} 条评论`;
        btn.style.background = 'linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%)';
    }

    window.scrollTo({ top: anchorTop - 100, behavior: 'smooth' });
}

function toggleCommentLike(commentId, btnEl) {
    <?php if(auth()->guard()->check()): ?>
        fetch(`/interactions/comments/${commentId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert(data.message || '操作失败');
                return;
            }

            const countEl = btnEl.querySelector('.comment-like-count');
            if (countEl) {
                countEl.textContent = String(data.count ?? 0);
            }
            const active = data.liked;
            btnEl.style.background = active ? 'rgba(239, 68, 68, 0.12)' : '#fff';
            btnEl.style.borderColor = active ? 'rgba(239, 68, 68, 0.4)' : '#e2e8f0';
        })
        .catch(() => alert('网络异常，请稍后重试'));
    <?php else: ?>
        alert('请先登录后点赞');
        window.location.href = '<?php echo e(route("login")); ?>';
    <?php endif; ?>
}

function renderReplyItem(reply) {
    const userName = reply?.user?.name ?? '匿名用户';
    const quotedUser = reply?.reply_to?.user?.name || '';
    const quotedContent = reply?.reply_to?.content || '';

    return `
        <div>
            <div style="display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:4px;">
                <div style="color:#334155; font-size:13px;">
                    <strong>${userName}</strong>
                    <span style="color:#94a3b8; margin-left:8px;">刚刚</span>
                </div>
                <button type="button"
                    onclick="startReply(${reply?.parent_id ?? 0}, '${userName.replace(/'/g, "\\'")}', ${reply?.id ?? 0})"
                    style="border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 4px 10px; color: #667eea; cursor: pointer; font-size: 12px; font-weight: 600;">
                    回复
                </button>
            </div>
            ${quotedUser ? `<div style="margin-bottom:6px; padding:6px 10px; border-radius:8px; background:#f8fafc; color:#64748b; font-size:12px;">引用 ${quotedUser}：${String(quotedContent).slice(0, 40)}</div>` : ''}
            <div style="color:#64748b; font-size:14px; line-height:1.6;">${reply?.content ?? ''}</div>
        </div>
    `;
}

function appendReplyToComment(reply) {
    const parentId = reply?.parent_id;
    if (!parentId) return;

    const commentItem = document.querySelector(`.comment-item[data-comment-id="${parentId}"]`);
    if (!commentItem) return;

    let container = commentItem.querySelector('.replies-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'replies-container';
        container.style.marginTop = '12px';
        container.style.paddingLeft = '16px';
        container.style.borderLeft = '2px solid #e2e8f0';
        container.style.display = 'grid';
        container.style.gap = '10px';
        commentItem.querySelector('div[style*="flex: 1"]')?.appendChild(container);
    }

    container.insertAdjacentHTML('beforeend', renderReplyItem(reply));

    const lastReply = container.lastElementChild;
    if (lastReply) {
        lastReply.style.background = 'rgba(102,126,234,0.06)';
        lastReply.style.borderRadius = '8px';
        lastReply.style.padding = '8px';
        lastReply.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => {
            lastReply.style.background = 'transparent';
            lastReply.style.padding = '0';
        }, 1200);
    }
}

function renderCommentItem(comment) {
    const userName = comment?.user?.name ?? '匿名用户';
    const userInitial = userName ? userName.substring(0, 1) : 'U';
    const content = comment?.content ?? '';

    return `
        <div class="comment-item" data-comment-id="${comment?.id ?? ''}" style="padding: 20px; border-bottom: 1px solid #e2e8f0;">
            <div style="display: flex; gap: 16px; align-items: flex-start;">
                <div style="
                    width: 48px;
                    height: 48px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 20px;
                    font-weight: 700;
                    color: white;
                ">${userInitial}</div>
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <div>
                            <span style="color: #1e293b; font-weight: 600;">${userName}</span>
                            <span style="color: #94a3b8; font-size: 13px; margin-left: 12px;">刚刚</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <button type="button"
                                onclick="toggleCommentLike(${comment?.id ?? 0}, this)"
                                style="border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 6px 10px; color: #ef4444; cursor: pointer; font-size: 12px; font-weight: 600;">
                                👍 <span class="comment-like-count">${comment?.like_count ?? 0}</span>
                            </button>
                            <button type="button"
                                onclick="startReply(${comment?.id ?? 0}, '${userName.replace(/'/g, "\\'")}')"
                                style="border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 6px 10px; color: #667eea; cursor: pointer; font-size: 12px; font-weight: 600;">
                                回复
                            </button>
                        </div>
                    </div>
                    <p style="color: #64748b; line-height: 1.6; margin: 0;">${content}</p>
                </div>
            </div>
        </div>
    `;
}

// 发表评论 / 回复
function submitComment() {
    <?php if(auth()->guard()->check()): ?>
        const textarea = document.getElementById('comment-content');
        const content = textarea.value.trim();

        if (!content) {
            alert('请输入评论内容');
            return;
        }

        fetch(`/articles/<?php echo e($article->id); ?>/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                content,
                parent_id: replyParentId,
                reply_to_id: replyToId,
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                textarea.value = '';

                const list = document.getElementById('comment-list');
                const empty = document.getElementById('comment-empty');
                const countEl = document.getElementById('comment-count');

                if (empty) {
                    empty.remove();
                }

                // 仅顶级评论进入列表计数，回复不改总数
                if (!replyParentId && list) {
                    list.insertAdjacentHTML('afterbegin', renderCommentItem(data.comment));
                }

                if (!replyParentId && countEl) {
                    const next = Number(data.total ?? (parseInt(countEl.textContent || '0', 10) + 1));
                    countEl.textContent = String(next);
                }

                if (replyParentId) {
                    appendReplyToComment(data.comment);
                }

                replyParentId = null;
                replyToId = null;
                cancelReply();
            } else {
                alert(data.message || '发表失败');
            }
        })
        .catch(() => alert('网络异常，请稍后重试'));
    <?php else: ?>
        alert('请先登录后评论');
        window.location.href = '<?php echo e(route("login")); ?>';
    <?php endif; ?>
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/articles/show.blade.php ENDPATH**/ ?>