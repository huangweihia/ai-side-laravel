<x-filament-panels::page>
    <div style="display: grid; gap: 32px;" wire:poll.5s>
        
        <!-- 收件人管理 -->
        <x-filament::section>
            <x-slot name="heading">
                📧 邮件接收人管理
            </x-slot>
            <x-slot name="description">
                配置每日资讯日报的接收邮箱
            </x-slot>
            
            <div style="display: flex; gap: 12px; margin-bottom: 24px;">
                <form wire:submit="addRecipient" style="display: flex; gap: 12px; flex: 1;">
                    <input 
                        type="email" 
                        wire:model="recipient" 
                        placeholder="输入邮箱地址"
                        style="flex: 1; padding: 12px 16px; background: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px;"
                    />
                    <button 
                        type="submit"
                        style="padding: 12px 24px; background: #6366f1; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;"
                    >
                        添加收件人
                    </button>
                </form>
            </div>
            
            @if(count($this->recipients) > 0)
                <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                    <button 
                        wire:click="bulkDelete"
                        wire:confirm="确定要删除选中的 {{ count($this->selectedForBulk) }} 个邮箱吗？"
                        style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 6px;"
                    >
                        🗑️ 批量删除 (<span x-text="{{ count($this->selectedForBulk) }}">0</span>)
                    </button>
                </div>
            @endif
            
            <div style="display: grid; gap: 12px;">
                @foreach($this->recipients as $email)
                    @php
                        $status = $this->getSubscriptionStatus($email);
                    @endphp
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #1e293b; border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                            <input 
                                type="checkbox" 
                                wire:click="toggleBulkSelect('{{ $email }}')"
                                {{ in_array($email, $this->selectedForBulk) ? 'checked' : '' }}
                                style="width: 18px; height: 18px; cursor: pointer;"
                            />
                            <span style="font-size: 20px;">📧</span>
                            <div style="flex: 1;">
                                <div style="color: white; font-weight: 500; margin-bottom: 4px;">{{ $email }}</div>
                                <div style="display: flex; gap: 8px; font-size: 12px;">
                                    <button 
                                        wire:click="toggleSubscription('{{ $email }}', 'subscribed_to_daily')"
                                        style="background: none; border: none; padding: 4px 8px; cursor: pointer; color: {{ $status['daily'] ? '#10b981' : '#64748b' }}; display: flex; align-items: center; gap: 4px;"
                                        title="点击切换日报订阅"
                                    >
                                        {{ $status['daily'] ? '✅' : '❌' }} 日报
                                    </button>
                                    <button 
                                        wire:click="toggleSubscription('{{ $email }}', 'subscribed_to_weekly')"
                                        style="background: none; border: none; padding: 4px 8px; cursor: pointer; color: {{ $status['weekly'] ? '#10b981' : '#64748b' }}; display: flex; align-items: center; gap: 4px;"
                                        title="点击切换周报订阅"
                                    >
                                        {{ $status['weekly'] ? '✅' : '❌' }} 周报
                                    </button>
                                    <button 
                                        wire:click="toggleSubscription('{{ $email }}', 'subscribed_to_notifications')"
                                        style="background: none; border: none; padding: 4px 8px; cursor: pointer; color: {{ $status['notifications'] ? '#10b981' : '#64748b' }}; display: flex; align-items: center; gap: 4px;"
                                        title="点击切换通知订阅"
                                    >
                                        {{ $status['notifications'] ? '✅' : '❌' }} 通知
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button 
                                wire:click="confirmAction('send_test', '{{ $email }}')"
                                wire:loading.attr="disabled"
                                style="padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 4px;"
                            >
                                📧 测试
                            </button>
                            <button 
                                wire:click="confirmAction('delete', '{{ $email }}')"
                                style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 4px;"
                            >
                                🗑️ 删除
                            </button>
                        </div>
                    </div>
                @endforeach
                
                @if(empty($this->recipients))
                    <div style="text-align: center; padding: 40px; color: #64748b;">
                        暂无收件人，请添加邮箱地址
                    </div>
                @endif
            </div>
        </x-filament::section>

        <!-- 批量导入/导出 -->
        <x-filament::section>
            <x-slot name="heading">
                📥 批量导入/导出
            </x-slot>
            <x-slot name="description">
                批量导入邮箱地址或导出当前列表
            </x-slot>
            
            <div style="display: grid; gap: 16px;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #94a3b8;">
                        批量导入（每行一个邮箱地址）
                    </label>
                    <textarea 
                        wire:model="bulkEmails"
                        placeholder="example1@qq.com&#10;example2@qq.com&#10;example3@qq.com"
                        rows="5"
                        style="width: 100%; padding: 12px 16px; background: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; font-size: 14px; font-family: monospace;"
                    ></textarea>
                    <div style="margin-top: 12px; display: flex; gap: 12px;">
                        <button 
                            wire:click="bulkImport"
                            style="padding: 10px 20px; background: #6366f1; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;"
                        >
                            📥 导入邮箱列表
                        </button>
                        <a 
                            href="{{ url('/admin/email-manager/export') }}"
                            style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;"
                        >
                            📤 导出邮箱列表
                        </a>
                    </div>
                </div>
            </div>
        </x-filament::section>

        <!-- 发送时间配置 -->
        <x-filament::section>
            <x-slot name="heading">
                ⏰ 定时发送配置
            </x-slot>
            <x-slot name="description">
                当前定时任务配置为每天 {{ $this->sendTime }} 自动发送
            </x-slot>
            
            <div style="padding: 20px; background: #1e293b; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <span style="font-size: 24px;">🕐</span>
                    <div>
                        <div style="color: #94a3b8; font-size: 14px;">当前发送时间</div>
                        <div style="color: white; font-size: 20px; font-weight: 600;">{{ $this->sendTime }} (北京时间)</div>
                    </div>
                </div>
                <div style="color: #64748b; font-size: 14px; line-height: 1.6;">
                    <p>如需修改发送时间，请在 OpenClaw 定时任务管理中调整：</p>
                    <code style="display: block; margin-top: 8px; padding: 12px; background: #0f172a; border-radius: 6px; color: #10b981;">
                        cron schedule: 0 2 * * * (UTC) = 每天 10:00 (Asia/Shanghai)
                    </code>
                </div>
            </div>
        </x-filament::section>

        <!-- 最近发送记录 -->
        <x-filament::section>
            <x-slot name="heading">
                📋 最近发送记录
            </x-slot>
            <x-slot name="description">
                查看最近的邮件发送历史
            </x-slot>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">收件人</th>
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">主题</th>
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">状态</th>
                            <th style="text-align: left; padding: 12px; color: #94a3b8; font-size: 13px;">发送时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->recentLogs as $log)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 12px; color: white;">{{ $log->recipient }}</td>
                                <td style="padding: 12px; color: #94a3b8; max-width: 300px; overflow: hidden; text-overflow: ellipsis;">{{ $log->subject }}</td>
                                <td style="padding: 12px;">
                                    <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; {{ $log->status === 'sent' ? 'background: #10b981; color: white;' : 'background: #ef4444; color: white;' }}">
                                        {{ $log->status === 'sent' ? '✅ 已发送' : '❌ 失败' }}
                                    </span>
                                </td>
                                <td style="padding: 12px; color: #94a3b8;">{{ $log->sent_at?->format('Y-m-d H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px; color: #64748b;">
                                    暂无发送记录
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <!-- 快捷操作 -->
        <x-filament::section>
            <x-slot name="heading">
                ⚡ 快捷操作
            </x-slot>
            
            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                <button 
                    wire:click="sendTestEmail"
                    style="padding: 12px 24px; background: #6366f1; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;"
                >
                    <span>🚀</span> 立即发送日报
                </button>
                <a 
                    href="{{ url('/admin/email-logs') }}"
                    style="padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;"
                >
                    <span>📧</span> 查看完整日志
                </a>
            </div>
        </x-filament::section>
    </div>

    {{-- 确认模态窗 --}}
    @if($showModal)
        <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center; z-index: 9999;">
            <div style="background: #1e293b; border-radius: 12px; padding: 30px; max-width: 450px; width: 90%; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <span style="font-size: 32px;">
                        @if($modalAction === 'delete') 🗑️
                        @elseif($modalAction === 'send_test') 📧
                        @elseif($modalAction === 'bulk_delete') ⚠️
                        @endif
                    </span>
                    <h3 style="color: white; font-size: 20px; font-weight: 600; margin: 0;">
                        @if($modalAction === 'delete') 确认删除
                        @elseif($modalAction === 'send_test') 发送测试邮件
                        @elseif($modalAction === 'bulk_delete') 批量删除
                        @endif
                    </h3>
                </div>

                <div style="color: #94a3b8; font-size: 15px; line-height: 1.6; margin-bottom: 25px;">
                    @if($modalAction === 'delete')
                        确定要删除邮箱 <strong style="color: white;">{{ $modalEmail }}</strong> 吗？
                        <br>删除后将不再收到任何邮件。
                    @elseif($modalAction === 'send_test')
                        将发送测试邮件到：
                        <br><strong style="color: white;">{{ $modalEmail }}</strong>
                        <br>请检查收件箱确认是否收到。
                    @elseif($modalAction === 'bulk_delete')
                        确定要删除选中的 <strong style="color: white;">{{ count($selectedForBulk) }}</strong> 个邮箱吗？
                        <br>此操作不可恢复。
                    @endif
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button 
                        wire:click="$set('showModal', false)"
                        wire:loading.attr="disabled"
                        style="padding: 10px 20px; background: #334155; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;"
                    >
                        取消
                    </button>
                    <button 
                        wire:click="executeModalAction"
                        wire:loading.attr="disabled"
                        style="padding: 10px 20px; background: {{ $modalAction === 'delete' || $modalAction === 'bulk_delete' ? '#ef4444' : '#10b981' }}; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;"
                    >
                        @if($isLoading)
                            <span style="display: inline-block; animation: spin 1s linear infinite;">⏳</span>
                            处理中...
                        @else
                            @if($modalAction === 'delete') 🗑️ 确认删除
                            @elseif($modalAction === 'send_test') 📧 发送
                            @elseif($modalAction === 'bulk_delete') ⚠️ 确认删除
                            @endif
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <style>
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        </style>
    @endif
</x-filament-panels::page>
