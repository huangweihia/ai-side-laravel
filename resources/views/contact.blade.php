@extends('layouts.app')

@section('title', '联系方式 - AI 副业情报局')

@section('content')
<section style="padding: 80px 0;">
    <div class="container" style="max-width: 700px;">
        <h1 style="font-size: 42px; margin-bottom: 20px; text-align: center;">联系我们</h1>
        <p style="text-align: center; color: var(--gray-light); font-size: 18px; margin-bottom: 60px;">
            有任何问题或建议？给我们留言吧！
        </p>

        <div class="card" style="padding: 40px;">
            <form id="contactForm" onsubmit="return false;">
                <div class="form-group">
                    <label class="form-label" for="name">姓名</label>
                    <input class="form-input" id="name" type="text" placeholder="你的姓名" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">邮箱</label>
                    <input class="form-input" id="email" type="email" placeholder="your@email.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="subject">主题</label>
                    <select class="form-input" id="subject" required>
                        <option value="">请选择主题</option>
                        <option value="question">问题咨询</option>
                        <option value="suggestion">功能建议</option>
                        <option value="cooperation">商务合作</option>
                        <option value="other">其他</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="message">留言内容</label>
                    <textarea class="form-input" id="message" rows="6" placeholder="请详细描述你的问题或需求..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px;" onclick="showConfirmModal()">
                    发送留言
                </button>
            </form>
        </div>

        <div class="card" style="padding: 30px; margin-top: 40px; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.3);">
            <h3 style="margin-bottom: 16px;">💡 快速响应</h3>
            <p style="color: var(--gray-light); line-height: 1.8;">
                我们会在 1-2 个工作日内回复你的留言。如果是紧急问题，建议直接发送邮件至 contact@example.com。
            </p>
        </div>
    </div>
</section>

<!-- 确认模态窗 -->
<div id="confirmModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: var(--dark-light); border-radius: 16px; padding: 40px; max-width: 450px; width: 90%; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <span style="font-size: 32px;">📧</span>
            <h3 style="color: white; font-size: 20px; font-weight: 600; margin: 0;">确认发送</h3>
        </div>

        <div style="color: var(--gray-light); font-size: 15px; line-height: 1.8; margin-bottom: 25px;">
            确定要发送这条留言吗？<br>
            我们会在 1-2 个工作日内回复你的邮箱。
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <button onclick="hideConfirmModal()" style="padding: 12px 24px; background: var(--dark); color: white; border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; font-weight: 600; cursor: pointer;">
                取消
            </button>
            <button onclick="submitForm()" id="submitBtn" style="padding: 12px 24px; background: var(--gradient-primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <span id="submitText">确认发送</span>
                <span id="submitLoading" style="display: none;">⏳</span>
            </button>
        </div>
    </div>
</div>

<script>
function showConfirmModal() {
    // 验证表单
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value.trim();

    if (!name || !email || !subject || !message) {
        alert('请填写所有必填字段');
        return;
    }

    if (!isValidEmail(email)) {
        alert('请输入有效的邮箱地址');
        return;
    }

    // 显示确认模态窗
    document.getElementById('confirmModal').style.display = 'flex';
}

function hideConfirmModal() {
    document.getElementById('confirmModal').style.display = 'none';
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function submitForm() {
    const btn = document.getElementById('submitBtn');
    const text = document.getElementById('submitText');
    const loading = document.getElementById('submitLoading');

    // 显示加载状态
    btn.disabled = true;
    text.textContent = '发送中...';
    loading.style.display = 'inline';

    // 模拟表单提交
    setTimeout(() => {
        alert('✅ 留言发送成功！我们会尽快回复你的邮箱。');
        document.getElementById('contactForm').reset();
        hideConfirmModal();
        
        // 重置按钮状态
        btn.disabled = false;
        text.textContent = '确认发送';
        loading.style.display = 'none';
    }, 1500);
}

// 点击模态窗外部关闭
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideConfirmModal();
    }
});
</script>
@endsection
