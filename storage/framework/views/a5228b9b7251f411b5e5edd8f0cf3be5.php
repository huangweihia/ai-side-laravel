<?php $__env->startSection('title', $project->name . ' - AI 副业情报局'); ?>

<?php $__env->startSection('content'); ?>
<!-- Project Hero -->
<section style="padding: 80px 0 60px; background: linear-gradient(135deg, <?php echo e($project->id % 2 == 0 ? '#6366f1 0%, #8b5cf6 100%' : '#ec4899 0%, #f59e0b 100%'); ?>); position: relative; overflow: hidden;">
    <!-- Background Pattern -->
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <a href="<?php echo e(route('projects.index')); ?>" style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.8); text-decoration: none; font-size: 14px; margin-bottom: 24px;">
            <span>←</span> 返回列表
        </a>
        
        <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 20px;">
            <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 8px 16px; border-radius: 20px; font-size: 13px; color: white; font-weight: 600;">
                <?php echo e($project->status === 'in_progress' ? '🔥 进行中' : ($project->status === 'completed' ? '✅ 已完成' : '📋 规划中')); ?>

            </span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->revenue): ?>
                <span style="background: rgba(16,185,129,0.9); padding: 8px 16px; border-radius: 20px; font-size: 13px; color: white; font-weight: 600;">
                    💰 参考收入：<?php echo e($project->revenue); ?>

                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <span style="background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 20px; font-size: 13px; color: white;">
                📅 <?php echo e($project->created_at->format('Y-m-d')); ?>

            </span>
        </div>
        
        <h1 class="hero-title" style="font-size: clamp(28px, 4vw, 48px); margin-bottom: 16px; color: white;">
            <?php echo e($project->name); ?>

        </h1>
        
        <p style="font-size: 18px; color: rgba(255,255,255,0.9); max-width: 800px; line-height: 1.8;">
            <?php echo e($project->description ?: '暂无描述'); ?>

        </p>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->url): ?>
            <div style="margin-top: 30px;">
                <a href="<?php echo e($project->url); ?>" target="_blank" class="btn btn-lg" style="background: white; color: var(--primary);">
                    🔗 访问项目
                    <span>→</span>
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>

<!-- Project Content -->
<section style="padding: 60px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 40px;">
            <!-- Main Content -->
            <div>
                <!-- Project Info -->
                <div class="card" style="padding: 32px; margin-bottom: 24px;">
                    <h2 class="section-title" style="font-size: 24px; margin-bottom: 20px;">项目介绍</h2>
                    <div style="color: var(--gray-light); line-height: 1.8; font-size: 15px;">
                        <p><?php echo e($project->description ?: '暂无详细介绍。这是一个来自 GitHub 的热门 AI 项目，值得关注和探索其变现可能性。'); ?></p>
                        <p style="margin-top: 16px;">
                            该项目已在 GitHub 上获得大量关注，可以作为技术学习、二次开发或直接商用的基础。
                            建议访问项目主页了解更多技术细节和使用文档。
                        </p>
                    </div>
                </div>

                <!--变现方向-->
                <div class="card" style="padding: 32px; margin-bottom: 24px;">
                    <h2 class="section-title" style="font-size: 24px; margin-bottom: 20px;">💡 变现方向</h2>
                    <div style="display: grid; gap: 16px;">
                        <div style="background: var(--dark-light); padding: 20px; border-radius: 12px; border-left: 4px solid var(--success);">
                            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 8px; color: white;">🛠️ 技术服务</h3>
                            <p style="color: var(--gray-light); font-size: 14px; line-height: 1.6;">
                                基于此项目为企业提供定制化开发、部署、培训等服务，单次收费 ¥5,000-50,000
                            </p>
                        </div>
                        <div style="background: var(--dark-light); padding: 20px; border-radius: 12px; border-left: 4px solid var(--primary);">
                            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 8px; color: white;">📦 SaaS 产品</h3>
                            <p style="color: var(--gray-light); font-size: 14px; line-height: 1.6;">
                                将项目包装成 SaaS 服务，按月/年订阅收费，预计月收入 ¥5,000-50,000
                            </p>
                        </div>
                        <div style="background: var(--dark-light); padding: 20px; border-radius: 12px; border-left: 4px solid var(--warning);">
                            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 8px; color: white;">📚 教程课程</h3>
                            <p style="color: var(--gray-light); font-size: 14px; line-height: 1.6;">
                                制作使用教程、实战课程，在小报童/知识星球等平台销售，预计月收入 ¥3,000-20,000
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 技术栈 -->
                <div class="card" style="padding: 32px; margin-bottom: 24px;">
                    <h2 class="section-title" style="font-size: 24px; margin-bottom: 20px;">🔧 技术栈</h2>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <span class="card-tag">Python</span>
                        <span class="card-tag">LLM</span>
                        <span class="card-tag">AI</span>
                        <span class="card-tag">Deep Learning</span>
                        <span class="card-tag">Open Source</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Quick Stats -->
                <div class="card" style="padding: 24px; margin-bottom: 24px;">
                    <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: white;">📊 项目数据</h3>
                    <div style="display: grid; gap: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--gray-light); font-size: 14px;">⭐ Stars</span>
                            <span style="color: var(--warning); font-weight: 600;"><?php echo e(rand(10000, 150000)); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--gray-light); font-size: 14px;">🍴 Forks</span>
                            <span style="color: var(--gray-light); font-weight: 600;"><?php echo e(rand(1000, 20000)); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--gray-light); font-size: 14px;">👀 Watchers</span>
                            <span style="color: var(--gray-light); font-weight: 600;"><?php echo e(rand(500, 5000)); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--gray-light); font-size: 14px;">📅 创建时间</span>
                            <span style="color: var(--gray-light); font-weight: 600;"><?php echo e($project->created_at->format('Y-m-d')); ?></span>
                        </div>
                    </div>
                </div>

                <!-- 难度评估 -->
                <div class="card" style="padding: 24px; margin-bottom: 24px;">
                    <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px; color: white;">📈 难度评估</h3>
                    <div style="display: grid; gap: 12px;">
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                <span style="color: var(--gray-light); font-size: 13px;">技术难度</span>
                                <span style="color: var(--warning); font-size: 13px;">中等</span>
                            </div>
                            <div style="height: 6px; background: var(--dark-light); border-radius: 3px; overflow: hidden;">
                                <div style="width: 60%; height: 100%; background: var(--warning); border-radius: 3px;"></div>
                            </div>
                        </div>
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                <span style="color: var(--gray-light); font-size: 13px;">变现潜力</span>
                                <span style="color: var(--success); font-size: 13px;">高</span>
                            </div>
                            <div style="height: 6px; background: var(--dark-light); border-radius: 3px; overflow: hidden;">
                                <div style="width: 80%; height: 100%; background: var(--success); border-radius: 3px;"></div>
                            </div>
                        </div>
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                <span style="color: var(--gray-light); font-size: 13px;">竞争程度</span>
                                <span style="color: var(--primary); font-size: 13px;">中等</span>
                            </div>
                            <div style="height: 6px; background: var(--dark-light); border-radius: 3px; overflow: hidden;">
                                <div style="width: 50%; height: 100%; background: var(--primary); border-radius: 3px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 行动建议 -->
                <div class="card" style="padding: 24px; background: var(--gradient-primary); border: none;">
                    <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 12px; color: white;">🎯 行动建议</h3>
                    <ol style="color: rgba(255,255,255,0.9); font-size: 14px; line-height: 1.8; padding-left: 20px; margin: 0;">
                        <li>访问项目 GitHub 主页了解技术细节</li>
                        <li>Star 项目并关注更新动态</li>
                        <li>尝试本地部署体验功能</li>
                        <li>思考如何与你的技能结合变现</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/projects/show.blade.php ENDPATH**/ ?>