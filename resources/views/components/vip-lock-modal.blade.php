{{-- VIP 列表/入口拦截弹窗：含 goVipPage、openVipModal、closeVipModal；页面内用 @include 一次即可 --}}
@once
<script>
function goVipPage() {
    const redirectUrl = encodeURIComponent(window.location.href);
    window.location.href = `{{ route('vip') }}?redirect=${redirectUrl}`;
}

function buildVipModalHtml() {
    return `
        <div id="vip-lock-modal" style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background: rgba(2,6,23,0.72); backdrop-filter: blur(4px); padding: 16px;" onclick="if(event.target.id==='vip-lock-modal'){closeVipModal();}">
            <div style="position: relative; width: min(92vw, 460px); background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border: 2px solid #fbbf24; border-radius: 18px; padding: 30px 26px; color: white; box-shadow: 0 20px 60px rgba(251,191,36,.3);">
                <button type="button" onclick="closeVipModal()" aria-label="关闭" style="position:absolute; right:12px; top:10px; width:30px; height:30px; border:none; border-radius:50%; background:rgba(255,255,255,.12); color:#fff; cursor:pointer; font-size:16px;">×</button>
                <div style="font-size:56px; text-align:center; margin-bottom:14px;">👑</div>
                <h3 style="margin:0 0 10px; font-size:30px; text-align:center; font-weight:800;">VIP 专属内容</h3>
                <p style="margin:0 0 18px; text-align:center; color:#cbd5e1; line-height:1.7;">该文章为 VIP 专属，开通 VIP 即可立即解锁完整内容。</p>

                <div style="padding:16px; background:rgba(251,191,36,.1); border-radius:12px; margin-bottom:20px;">
                    <div style="color:#fbbf24; font-size:14px; margin-bottom:8px;"><span style="color:#10b981;">✓</span> 无限阅读 VIP 文章</div>
                    <div style="color:#fbbf24; font-size:14px; margin-bottom:8px;"><span style="color:#10b981;">✓</span> 无限次知识检索</div>
                    <div style="color:#fbbf24; font-size:14px; margin-bottom:8px;"><span style="color:#10b981;">✓</span> 专属邮件推送</div>
                    <div style="color:#fbbf24; font-size:14px;"><span style="color:#10b981;">✓</span> 优先客服支持</div>
                </div>

                <button type="button" onclick="goVipPage()" style="width:100%; padding:14px 20px; border:none; border-radius:12px; background:linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color:#fff; font-size:17px; font-weight:800; cursor:pointer; box-shadow:0 8px 25px rgba(251,191,36,.4);">立即开通 VIP</button>
                <p style="text-align:center; color:#94a3b8; font-size:13px; margin:14px 0 0;">💡 首月仅需 <span style="color:#fbbf24; font-weight:700; font-size:15px;">¥9.9</span> | 平均每天 ¥0.33</p>
            </div>
        </div>
    `;
}

function closeVipModal() {
    const modal = document.getElementById('vip-lock-modal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = '';
    }
}

function openVipModal() {
    if (document.getElementById('vip-lock-modal')) return;
    document.body.insertAdjacentHTML('beforeend', buildVipModalHtml());
    document.body.style.overflow = 'hidden';
}
</script>
@endonce
