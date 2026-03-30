<x-filament-panels::page>
    <div style="display: grid; gap: 24px;">
        @php
            $registerVipEnabled = \App\Models\Setting::getValue('register_default_vip_enabled', false);
            $registerVipDays = (int) (\App\Models\Setting::getValue('register_default_vip_days', 7) ?? 7);
            $registerVipDays = max(0, $registerVipDays);
        @endphp

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

        <!-- 注册赠送 VIP 设置 -->
        <x-filament::section>
            <x-slot name="heading">
                👑 注册赠送 VIP 设置
            </x-slot>
            <x-slot name="description">
                开启后，用户注册成功将自动设置为 VIP，并按天数赠送有效期。
            </x-slot>

            <form method="POST" action="{{ route('admin.settings.register-vip') }}" style="margin-top: 12px;">
                @csrf
                <div style="display: grid; gap: 12px;">
                    <label style="display:flex; align-items:center; gap:10px; font-weight: 700;">
                        <input type="checkbox" name="enabled" value="1" {{ $registerVipEnabled ? 'checked' : '' }}>
                        启用：注册后自动赠送 VIP
                    </label>

                    <div style="display:flex; gap: 12px; align-items:center;">
                        <label style="min-width: 120px; color: rgba(148,163,184,1); font-size: 13px; font-weight: 700;">赠送天数</label>
                        <input type="number"
                               name="days"
                               value="{{ $registerVipDays }}"
                               min="0"
                               max="3650"
                               step="1"
                               style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.12); background: rgba(15,23,42,0.4); color: inherit;">
                    </div>

                    <button type="submit"
                            class="fi-button fi-button-primary"
                            style="justify-content:center; padding: 10px 16px; border-radius: 10px; font-weight: 800; cursor:pointer;">
                        保存设置
                    </button>
                </div>
            </form>
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
