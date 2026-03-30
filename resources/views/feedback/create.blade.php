@extends('layouts.app')

@section('title', '问题反馈 - AI 副业情报局')

@section('content')
<div class="container" style="max-width: 900px; margin: 40px auto 60px;">
    <div class="card" style="padding: 28px;">
        <h1 style="font-size: 24px; margin-bottom: 8px;">问题反馈</h1>
        <p style="color: var(--gray-light); margin-bottom: 18px;">欢迎反馈 bug / 建议，审核采纳后自动奖励 1 天 VIP。</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('feedback.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label" for="title">标题</label>
                <input id="title" class="form-input" type="text" name="title" value="{{ old('title') }}" required placeholder="例如：广告位上传失败无法清除">
            </div>
            <div class="form-group">
                <label class="form-label" for="content">问题详情</label>
                <textarea id="content" class="form-input" name="content" rows="7" required placeholder="请描述复现步骤、预期结果、实际结果">{{ old('content') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="image">截图（可选）</label>
                <input id="image" class="form-input" type="file" name="image" accept="image/*">
                <p style="margin-top: 8px; color: var(--gray-light); font-size: 12px;">支持 jpg/png/webp，最大 4MB。</p>
                <div id="imagePreviewWrap" style="margin-top: 12px; padding: 12px; border-radius: 12px; border: 1px dashed rgba(99, 102, 241, 0.35); background: rgba(99, 102, 241, 0.06); display:none;">
                    <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; margin-bottom: 10px;">
                        <div style="font-weight: 700; color: var(--primary-light); font-size: 13px;">预览图</div>
                        <div id="imagePreviewMeta" style="font-size: 12px; color: var(--gray-light);"></div>
                    </div>
                    <img id="imagePreview" alt="反馈截图预览" style="max-width:100%; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08);">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">提交反馈</button>
        </form>
    </div>

    <div class="card" style="padding: 22px; margin-top: 18px;">
        <h2 style="font-size: 18px; margin-bottom: 12px;">我的最近反馈</h2>
        @forelse($feedbacks as $f)
            <div style="padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.08);">
                <div style="display:flex; justify-content:space-between; gap:12px;">
                    <strong>{{ $f->title }}</strong>
                    <span style="font-size:12px; color: var(--gray-light);">
                        {{ ['pending' => '待审核', 'approved' => '已采纳', 'rejected' => '已拒绝'][$f->status] ?? $f->status }}
                    </span>
                </div>
                <div style="margin-top:6px; color: var(--gray-light); font-size: 13px;">
                    {{ $f->created_at?->format('Y-m-d H:i') }}
                    @if($f->status === 'approved' && $f->rewarded_at)
                        · 已奖励 1 天 VIP
                    @endif
                </div>
                @if($f->review_note)
                    <div style="margin-top:8px; font-size:13px; color:#a5b4fc;">审核备注：{{ $f->review_note }}</div>
                @endif
            </div>
        @empty
            <p style="color: var(--gray-light);">还没有提交过反馈。</p>
        @endforelse
    </div>
</div>
    <script>
        (function () {
            var input = document.getElementById('image');
            if (!input) return;
            var wrap = document.getElementById('imagePreviewWrap');
            var img = document.getElementById('imagePreview');
            var meta = document.getElementById('imagePreviewMeta');
            if (!wrap || !img || !meta) return;

            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file) {
                    wrap.style.display = 'none';
                    img.removeAttribute('src');
                    meta.textContent = '';
                    return;
                }

                var url = URL.createObjectURL(file);
                img.src = url;
                meta.textContent = file.name + ' · ' + Math.max(1, Math.round(file.size / 1024)) + 'KB';
                wrap.style.display = 'block';
            });
        })();
    </script>
@endsection

