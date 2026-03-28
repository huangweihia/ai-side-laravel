<?php $__env->startSection('title', $project->name . ' - AI 副业项目详情'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* 评论回复按钮默认隐藏，鼠标悬停显示 */
    .comment-item .reply-btn {
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s;
    }
    
    .comment-item:hover .reply-btn {
        opacity: 1;
        visibility: visible;
    }
    
    /* 回复项内的回复按钮同样处理 */
    .reply-item .reply-btn {
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s;
    }
    
    .reply-item:hover .reply-btn {
        opacity: 1;
        visibility: visible;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">

    
    <a href="<?php echo e(route('projects.index')); ?>" 
       style="display: inline-flex; align-items: center; gap: 8px; color: #667eea; text-decoration: none; font-weight: 600; margin-bottom: 24px;"
       onmouseover="this.style.color='#764ba2'"
       onmouseout="this.style.color='#667eea'"
    >
        <span>←</span> 返回项目列表
    </a>

    <div style="display: grid; grid-template-columns: 1fr 320px; gap: 40px;">
        
        
        <div style="min-width: 0;">
            
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
                <div style="flex: 1;">
                    <h1 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 0 0 12px;">
                        🚀 <?php echo e($project->name); ?>

                    </h1>
                    <div style="display: flex; gap: 16px; color: #64748b; font-size: 14px;">
                        <span>⭐ <?php echo e(number_format($project->stars ?? 0)); ?> stars</span>
                        <span>🍴 <?php echo e(number_format($project->forks ?? 0)); ?> forks</span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->language): ?>
                            <span>💻 <?php echo e($project->language); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <span>📅 <?php echo e($project->created_at?->diffForHumans() ?? '近期'); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->is_vip): ?>
                            <span style="padding: 4px 12px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; border-radius: 20px; font-size: 12px; font-weight: 700;">👑 VIP</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                
                
                <button id="favorite-btn" 
                        onclick="toggleFavorite()"
                        style="
                            padding: 12px 24px;
                            background: <?php echo e($isFavorited ? 'linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%)' : 'white'); ?>;
                            color: <?php echo e($isFavorited ? 'white' : '#f59e0b'); ?>;
                            border: <?php echo e($isFavorited ? 'none' : '2px solid #f59e0b'); ?>;
                            border-radius: 12px;
                            font-weight: 700;
                            cursor: pointer;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            transition: all 0.3s;
                        "
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(245, 158, 11, 0.3)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <span><?php echo e($isFavorited ? '⭐' : '☆'); ?></span>
                    <span><?php echo e($isFavorited ? '已收藏' : '收藏'); ?></span>
                </button>
            </div>

            
            <div style="background: white; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0 0 16px;">
                    📋 项目介绍
                </h2>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canViewFullProject): ?>
                    <p style="color: #64748b; font-size: 16px; line-height: 1.8; margin: 0;">
                        <?php echo e($project->description ?? '暂无描述'); ?>

                    </p>
                <?php else: ?>
                    <p style="color: #64748b; font-size: 16px; line-height: 1.8; margin: 0 0 16px;">
                        <?php echo e(\Illuminate\Support\Str::limit(strip_tags($project->description ?? '本项目为 VIP 专属，开通后可查看完整介绍与资源。'), 220)); ?>

                    </p>
                    <div style="padding: 20px; border-radius: 14px; border: 2px solid rgba(251, 191, 36, 0.45); background: linear-gradient(135deg, rgba(30, 41, 59, 0.04) 0%, rgba(15, 23, 42, 0.03) 100%); text-align: center;">
                        <div style="font-size: 36px; margin-bottom: 8px;">👑</div>
                        <p style="color: #475569; font-size: 15px; margin: 0 0 14px;">变现分析、技术栈、教程资源与仓库链接仅对 VIP 开放。</p>
                        <a href="<?php echo e(route('vip', ['redirect' => request()->fullUrl()])); ?>" style="padding: 12px 28px; border-radius: 12px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: #fff; font-weight: 700; font-size: 15px; text-decoration: none; display: inline-block;">开通 VIP 解锁</a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canViewFullProject): ?>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->difficulty || $project->income_range || $project->time_commitment || $project->monetization_paths): ?>
            <div style="background: white; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0 0 20px;">
                    💰 变现分析
                </h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 24px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->difficulty): ?>
                    <div style="text-align: center; padding: 16px; background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%); border-radius: 12px;">
                        <div style="font-size: 32px; margin-bottom: 8px;">🎯</div>
                        <div style="color: #64748b; font-size: 13px; margin-bottom: 4px;">难度等级</div>
                        <div style="color: #1e293b; font-size: 16px; font-weight: 700;"><?php echo e($project->difficulty_label); ?></div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->income_range): ?>
                    <div style="text-align: center; padding: 16px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%); border-radius: 12px;">
                        <div style="font-size: 32px; margin-bottom: 8px;">💵</div>
                        <div style="color: #64748b; font-size: 13px; margin-bottom: 4px;">月收入预估</div>
                        <div style="color: #1e293b; font-size: 16px; font-weight: 700;"><?php echo e($project->income_label); ?></div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->time_commitment): ?>
                    <div style="text-align: center; padding: 16px; background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, rgba(217, 119, 6, 0.05) 100%); border-radius: 12px;">
                        <div style="font-size: 32px; margin-bottom: 8px;">⏰</div>
                        <div style="color: #64748b; font-size: 13px; margin-bottom: 4px;">时间投入</div>
                        <div style="color: #1e293b; font-size: 16px; font-weight: 700;"><?php echo e($project->time_label); ?></div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->monetization_paths && is_array($project->monetization_paths)): ?>
                <div style="padding: 16px; background: rgba(102, 126, 234, 0.05); border-radius: 12px;">
                    <div style="color: #64748b; font-size: 14px; margin-bottom: 12px; font-weight: 600;">变现路径：</div>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $project->monetization_paths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $path): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span style="
                                padding: 6px 14px;
                                background: white;
                                color: #667eea;
                                border-radius: 20px;
                                font-size: 13px;
                                font-weight: 600;
                                border: 1px solid rgba(102, 126, 234, 0.2);
                            ">
                                💡 <?php echo e($path); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->tech_stack && is_array($project->tech_stack)): ?>
            <div style="background: white; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0 0 16px;">
                    🛠️ 技术栈
                </h2>
                <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $project->tech_stack; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span style="
                            padding: 8px 16px;
                            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
                            color: #667eea;
                            border-radius: 8px;
                            font-size: 14px;
                            font-weight: 600;
                        ">
                            <?php echo e($tech); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->resources && is_array($project->resources)): ?>
            <div style="background: white; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0 0 16px;">
                    📚 教程资源
                </h2>
                <div style="display: grid; gap: 12px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $project->resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($resource['url'] ?? '#'); ?>" 
                           target="_blank"
                           style="
                               padding: 16px;
                               background: rgba(102, 126, 234, 0.05);
                               border-radius: 12px;
                               text-decoration: none;
                               color: inherit;
                               display: flex;
                               align-items: center;
                               gap: 12px;
                               transition: all 0.3s;
                               border: 1px solid transparent;
                           "
                           onmouseover="this.style.borderColor='#667eea'; this.style.background='rgba(102, 126, 234, 0.1)'; this.style.transform='translateX(5px)'"
                           onmouseout="this.style.borderColor='transparent'; this.style.background='rgba(102, 126, 234, 0.05)'; this.style.transform='translateX(0)'"
                        >
                            <span style="font-size: 24px;">🔗</span>
                            <div style="flex: 1;">
                                <div style="color: #1e293b; font-weight: 600;"><?php echo e($resource['title'] ?? '资源链接'); ?></div>
                                <div style="color: #64748b; font-size: 13px;"><?php echo e($resource['type'] ?? '链接'); ?></div>
                            </div>
                            <span style="color: #667eea;">↗</span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->url): ?>
            <div style="text-align: center; margin: 32px 0;">
                <a href="<?php echo e($project->url); ?>" 
                   target="_blank"
                   style="
                       padding: 16px 40px;
                       background: linear-gradient(135deg, #24292e 0%, #1a1f24 100%);
                       color: white;
                       border-radius: 50px;
                       font-size: 18px;
                       font-weight: 700;
                       text-decoration: none;
                       display: inline-flex;
                       align-items: center;
                       gap: 12px;
                       transition: all 0.3s;
                       box-shadow: 0 10px 30px rgba(36, 41, 46, 0.3);
                   "
                   onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 40px rgba(36, 41, 46, 0.4)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(36, 41, 46, 0.3)'"
                >
                    <span>🐙</span>
                    访问 GitHub 项目
                </a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div style="margin-top: 40px;">
                <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0 0 24px;">
                    💬 用户评论 (<span id="comment-count"><?php echo e($commentsTotal ?? $comments->count()); ?></span>)
                </h2>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <div style="margin-bottom: 32px;">
                        <textarea id="comment-content" 
                                  placeholder="分享你的看法..."
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
                                  "
                                  onfocus="this.style.borderColor='#667eea'"
                                  onblur="this.style.borderColor='#e2e8f0'"
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

                
                <div id="comment-list" style="display: grid; gap: 16px;">
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
                             style="
                                padding: 20px; 
                                border-radius: 12px; 
                                background: #f0f7ff;
                                <?php echo e(!$isVisible ? 'display:none;' : ''); ?>;
                                word-wrap: break-word; 
                                word-break: break-word; 
                                overflow-wrap: break-word;
                            ">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($featuredComment) && $featuredComment && $featuredComment->id === $comment->id): ?>
                                <div style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px; padding: 4px 10px; border-radius: 999px; background: rgba(245, 158, 11, 0.12); color: #d97706; font-size: 12px; font-weight: 700;">
                                    <span>🏆</span><span>精品评论</span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div style="display: flex; gap: 16px; align-items: flex-start;">
                                <div style="
                                    width: 40px;
                                    height: 40px;
                                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 18px;
                                    font-weight: 700;
                                    color: white;
                                    flex-shrink: 0;
                                ">
                                    <?php echo e(substr($comment->user->name ?? 'U', 0, 1)); ?>

                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                        <div>
                                            <a href="<?php echo e(route('users.show', $comment->user_id)); ?>" style="color: #1e293b; font-weight: 600; text-decoration:none; font-size: 15px;"><?php echo e($comment->user->name ?? '匿名用户'); ?></a>
                                            <span style="color: #94a3b8; font-size: 13px; margin-left: 12px;"><?php echo e($comment->created_at->format('Y-m-d H:i')); ?></span>
                                        </div>
                                        <div style="display: flex; gap: 8px; align-items:center;">
                                            <button type="button"
                                                onclick="toggleCommentLike(<?php echo e($comment->id); ?>, this)"
                                                style="border: 1px solid <?php echo e(in_array($comment->id, $likedCommentIds ?? []) ? 'rgba(239, 68, 68, 0.4)' : '#e2e8f0'); ?>; background: <?php echo e(in_array($comment->id, $likedCommentIds ?? []) ? 'rgba(239, 68, 68, 0.12)' : '#fff'); ?>; border-radius: 999px; padding: 4px 10px; color: #ef4444; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s;">
                                                👍 <span class="comment-like-count"><?php echo e($comment->like_count ?? 0); ?></span>
                                            </button>
                                            <button type="button"
                                                onclick="startReply(<?php echo e($comment->id); ?>, '<?php echo e(addslashes($comment->user->name ?? '匿名用户')); ?>')"
                                                style="border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 4px 10px; color: #667eea; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s; opacity: 0; visibility: hidden;"
                                                class="reply-btn"
                                                onmouseover="this.style.background='#f1f5f9'; this.style.opacity='1'"
                                                onmouseout="this.style.background='#fff'; this.style.opacity='0'">
                                                回复
                                            </button>
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
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $isReplyVisible = !$shouldCollapseReplies || $idx >= $replyStartIndex;
                                                ?>
                                                <div class="reply-item" style="padding: 12px 0; border-bottom: 1px solid #e2e8f0; <?php echo e(!$isReplyVisible ? 'display:none;' : ''); ?>; word-wrap: break-word; word-break: break-word; overflow-wrap: break-word;">
                                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; gap:8px;">
                                                        <div style="display: flex; gap: 12px; align-items: center;">
                                                            <span style="color: #1e293b; font-weight: 600; font-size: 14px;"><?php echo e($reply->user->name ?? '匿名用户'); ?></span>
                                                            <span style="color: #94a3b8; font-size: 12px;"><?php echo e($reply->created_at->format('Y-m-d H:i')); ?></span>
                                                        </div>
                                                        <button type="button"
                                                            onclick="startReply(<?php echo e($comment->id); ?>, '<?php echo e(addslashes($reply->user->name ?? '匿名用户')); ?>', <?php echo e($reply->id); ?>)"
                                                            style="border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 4px 10px; color: #667eea; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s; opacity: 0; visibility: hidden;"
                                                            class="reply-btn"
                                                            onmouseover="this.style.background='#f1f5f9'; this.style.opacity='1'"
                                                            onmouseout="this.style.background='#fff'; this.style.opacity='0'">
                                                            回复
                                                        </button>
                                                    </div>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($reply->replyTo): ?>
                                                        <div style="margin-bottom:6px; padding:6px 10px; border-radius:8px; background:#fff; color:#64748b; font-size:12px;">
                                                            <span style="color: #667eea; font-weight: 600;">{{ $reply->replyTo->user->name ?? '匿名用户' }}</span>：<?php echo e(\Illuminate\Support\Str::limit($reply->replyTo->content, 40)); ?>

                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <p style="color: #64748b; font-size: 14px; line-height: 1.5; margin: 0;"><?php echo e($reply->content); ?></p>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shouldCollapseReplies): ?>
                                                <div style="padding: 8px 0;">
                                                    <button type="button" onclick="toggleRepliesExpand('<?php echo e($comment->id); ?>', <?php echo e($replyCount); ?>)"
                                                        style="padding: 6px 14px; border-radius: 999px; border: 1px solid #e2e8f0; background: #fff; color: #667eea; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s;"
                                                        onmouseover="this.style.background='#f1f5f9'; this.style.transform='translateY(-1px)'"
                                                        onmouseout="this.style.background='#fff'; this.style.transform='translateY(0)'">
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
                        <div id="comment-empty" style="text-align: center; padding: 60px; color: #64748b;">
                            <div style="font-size: 64px; margin-bottom: 16px;">💬</div>
                            <p>暂无评论，快来抢沙发吧！</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shouldCollapse): ?>
                    <div style="text-align:center; margin-top: 20px;">
                        <button id="toggle-comments-btn" type="button" onclick="toggleCommentsExpand()"
                            style="padding: 10px 24px; border-radius: 999px; border: 1px solid #e2e8f0; background: #fff; color: #667eea; cursor:pointer; font-weight:600; font-size: 14px; transition: all 0.2s;"
                            onmouseover="this.style.background='#f1f5f9'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'"
                            onmouseout="this.style.background='#fff'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            查看全部 <?php echo e($totalComments); ?> 条评论
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
            </div>

        </div>

        
        <div>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($adSlot) && $adSlot->shouldDisplaySidebar()): ?>
            <div style="background: white; border-radius: 16px; padding: 16px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <span style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em;">推广</span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($adSlot->display_mode === 'html' && filled($adSlot->html_content)): ?>
                    <div style="font-size: 14px; line-height: 1.6;"><?php echo $adSlot->html_content; ?></div>
                <?php else: ?>
                    <?php $adResolvedImg = $adSlot->resolvedImageUrl(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($adResolvedImg): ?>
                        <div style="margin-bottom: 12px; border-radius: 8px; overflow: hidden;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($adSlot->link_url): ?>
                                <a href="<?php echo e($adSlot->link_url); ?>" target="_blank">
                                    <img src="<?php echo e($adResolvedImg); ?>" style="width: 100%; height: auto; display: block;" alt="">
                                </a>
                            <?php else: ?>
                                <img src="<?php echo e($adResolvedImg); ?>" style="width: 100%; height: auto; display: block;" alt="">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($adSlot->title)): ?>
                        <h3 style="font-size: 15px; font-weight: 700; color: #1e293b; margin: 0 0 8px; line-height: 1.4;"><?php echo e($adSlot->title); ?></h3>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($adSlot->body)): ?>
                        <div style="font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 12px;"><?php echo nl2br(e($adSlot->body)); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($adSlot->cta_label) && filled($adSlot->link_url)): ?>
                        <a href="<?php echo e($adSlot->link_url); ?>" target="_blank" style="display: block; text-align: center; padding: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;"><?php echo e($adSlot->cta_label); ?></a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($relatedProjects->count()): ?>
            <div style="background: white; border-radius: 16px; padding: 20px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 16px;">
                    相关项目
                </h3>
                <div style="display: grid; gap: 12px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $relatedProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('projects.show', $related)); ?>" 
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
                            <div style="font-weight: 600; color: #1e293b; font-size: 14px; margin-bottom: 4px;">
                                <?php echo e(Str::limit($related->name, 30)); ?>

                            </div>
                            <div style="color: #64748b; font-size: 12px;">
                                ⭐ <?php echo e($related->stars ?? 0); ?>

                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

    </div>

</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
<script>
// 收藏功能
function toggleFavorite() {
    fetch('<?php echo e(route('projects.favorite', $project->id)); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const btn = document.getElementById('favorite-btn');
            if (data.isFavorited) {
                btn.style.background = 'linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%)';
                btn.style.color = 'white';
                btn.style.border = 'none';
                btn.animate([{ transform: 'scale(1)' }, { transform: 'scale(1.08)' }, { transform: 'scale(1)' }], { duration: 260 });
                btn.querySelector('span:first-child').textContent = '⭐';
                btn.querySelector('span:last-child').textContent = '已收藏';
            } else {
                btn.style.background = 'white';
                btn.style.color = '#f59e0b';
                btn.style.border = '2px solid #f59e0b';
                btn.querySelector('span:first-child').textContent = '☆';
                btn.querySelector('span:last-child').textContent = '收藏';
            }
        }
    });
}

let commentsExpanded = false;
let replyParentId = null;
let replyToId = null;
const SHOW_LAST_N = 5;
const SHOW_LAST_N_REPLIES = 3;
const replyExpandedState = {};

function toggleCommentsExpand() {
    const items = document.querySelectorAll('#comment-list .comment-item');
    const btn = document.getElementById('toggle-comments-btn');
    if (!btn || items.length === 0) return;

    const totalItems = items.length;
    const anchorTop = btn.getBoundingClientRect().top + window.scrollY;

    if (!commentsExpanded) {
        items.forEach((item) => {
            item.style.display = 'block';
        });
        commentsExpanded = true;
        btn.textContent = `收起评论（共 ${totalItems} 条）`;
        btn.style.background = 'linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%)';
    } else {
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
        btn.style.background = '#e2e8f0';
    } else {
        const startIndex = Math.max(0, replyCount - SHOW_LAST_N_REPLIES);
        replyItems.forEach((item, idx) => {
            item.style.display = idx >= startIndex ? 'block' : 'none';
        });
        replyExpandedState[commentId] = false;
        btn.textContent = `查看全部 ${replyCount} 条回复`;
        btn.style.background = '#f8fafc';
    }
}

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

function renderReplyItem(reply) {
    const userName = reply?.user?.name ?? '匿名用户';
    const quotedUser = reply?.reply_to?.user?.name || '';
    const quotedContent = reply?.reply_to?.content || '';

    return `
        <div style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; gap:8px;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <span style="color: #1e293b; font-weight: 600; font-size: 14px;">${userName}</span>
                    <span style="color: #94a3b8; font-size: 12px;">刚刚</span>
                </div>
                <button type="button"
                    onclick="startReply(${reply?.parent_id ?? 0}, '${userName.replace(/'/g, "\\'")}', ${reply?.id ?? 0})"
                    style="border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 4px 10px; color: #667eea; cursor: pointer; font-size: 12px; font-weight: 600;">
                    回复
                </button>
            </div>
            ${quotedUser ? `<div style="margin-bottom:6px; padding:6px 10px; border-radius:8px; background:#f8fafc; color:#64748b; font-size:12px;">引用 ${quotedUser}：${String(quotedContent).slice(0, 40)}</div>` : ''}
            <p style="color: #64748b; font-size: 14px; line-height: 1.5; margin: 0;">${reply?.content ?? ''}</p>
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
        container.style.marginTop = '16px';
        container.style.paddingLeft = '24px';
        container.style.borderLeft = '2px solid #e2e8f0';
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

function toggleCommentLike(commentId, btnEl) {
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
        if (active) {
            btnEl.animate([{ transform: 'scale(1)' }, { transform: 'scale(1.08)' }, { transform: 'scale(1)' }], { duration: 220 });
        }
    })
    .catch(() => alert('网络异常，请稍后重试'));
}

function renderCommentItem(comment) {
    const userName = comment?.user?.name ?? '匿名用户';
    const userInitial = userName ? userName.substring(0, 1) : 'U';
    const content = comment?.content ?? '';

    return `
        <div class="comment-item" data-comment-id="${comment?.id ?? ''}" style="padding: 20px; border-bottom: 1px solid #e2e8f0;">
            <div style="display: flex; gap: 16px; align-items: flex-start;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700; color: white;">
                    ${userInitial}
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <div>
                            <span style="color: #1e293b; font-weight: 600;">${userName}</span>
                            <span style="color: #94a3b8; font-size: 13px; margin-left: 12px;">刚刚</span>
                        </div>
                        <div style="display: flex; gap: 8px; align-items:center; color: #64748b; font-size: 13px;">
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
    const textarea = document.getElementById('comment-content');
    const content = textarea.value.trim();
    if (!content) {
        alert('请输入评论内容');
        return;
    }

    fetch('<?php echo e(route("projects.comments.store", $project->id)); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            content: content,
            parent_id: replyParentId,
            reply_to_id: replyToId,
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const list = document.getElementById('comment-list');
            const empty = document.getElementById('comment-empty');
            const countEl = document.getElementById('comment-count');

            if (empty) {
                empty.remove();
            }

            if (replyParentId) {
                appendReplyToComment(data.comment);
            } else if (list) {
                list.insertAdjacentHTML('afterbegin', renderCommentItem(data.comment));
                const next = Number(data.total ?? (parseInt(countEl?.textContent || '0', 10) + 1));
                if (countEl) countEl.textContent = String(next);
            }

            replyParentId = null;
            replyToId = null;
            cancelReply();
            textarea.value = '';
        } else {
            alert(data.message || '发表失败');
        }
    })
    .catch(() => alert('网络异常，请稍后重试'));
}
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
<script>
function toggleFavorite() {
    alert('请先登录后收藏');
    window.location.href = '<?php echo e(route("login")); ?>';
}

function submitComment() {
    alert('请先登录后评论');
    window.location.href = '<?php echo e(route("login")); ?>';
}
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/projects/show.blade.php ENDPATH**/ ?>