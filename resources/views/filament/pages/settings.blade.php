<x-filament-panels::page>
    <div style="display: grid; gap: 24px;">
        <!-- 邮件设置 -->
        <x-filament::section>
            <x-slot name="heading">
                📧 邮件配置
            </x-slot>
            <x-slot name="description">
                配置 QQ 邮箱 SMTP 发送设置
            </x-slot>
            
            <div style="display: grid; gap: 16px;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">SMTP 服务器</label>
                    <code style="background: #1e293b; padding: 12px; border-radius: 8px; display: block;">smtp.qq.com:465 (SSL)</code>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">发件邮箱</label>
                    <code style="background: #1e293b; padding: 12px; border-radius: 8px; display: block;">2801359160@qq.com</code>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">状态</label>
                    <span style="color: #10b981;">✅ 配置正常</span>
                </div>
            </div>
        </x-filament::section>

        <!-- 定时任务设置 -->
        <x-filament::section>
            <x-slot name="heading">
                ⏰ 定时任务
            </x-slot>
            <x-slot name="description">
                每日自动推送 AI 副业资讯
            </x-slot>
            
            <div style="display: grid; gap: 16px;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">推送时间</label>
                    <code style="background: #1e293b; padding: 12px; border-radius: 8px; display: block;">每天 10:00 (Asia/Shanghai)</code>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">推送内容</label>
                    <ul style="list-style: disc; padding-left: 20px; color: #94a3b8;">
                        <li>热门 AI 项目 Top 10</li>
                        <li>副业/创收灵感 5+</li>
                        <li>学习资源推荐 4+</li>
                    </ul>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">收件人</label>
                    <code style="background: #1e293b; padding: 12px; border-radius: 8px; display: block;">2801359160@qq.com</code>
                </div>
            </div>
        </x-filament::section>

        <!-- 系统信息 -->
        <x-filament::section>
            <x-slot name="heading">
                ℹ️ 系统信息
            </x-slot>
            <x-slot name="description">
                当前系统配置和版本
            </x-slot>
            
            <div style="display: grid; gap: 12px;">
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <span style="color: #94a3b8;">Laravel 版本</span>
                    <span>{{ app()->version() }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <span style="color: #94a3b8;">PHP 版本</span>
                    <span>{{ phpversion() }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <span style="color: #94a3b8;">Filament 版本</span>
                    <span>v3.x</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="color: #94a3b8;">数据库</span>
                    <span>MySQL 8.0</span>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
