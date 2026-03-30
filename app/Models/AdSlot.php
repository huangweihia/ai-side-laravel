<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdSlot extends Model
{
    protected $fillable = [
        'name',
        'is_enabled',
        'title',
        'body',
        'cta_label',
        'display_mode',
        'image_url',
        'image_path',
        'html_content',
        'link_url',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public static function globalSlot(): ?self
    {
        return static::query()->orderBy('id')->first();
    }

    /**
     * 解析后的图片地址：优先本地上传，其次外链（二选一）。
     */
    public function resolvedImageUrl(): ?string
    {
        if ($this->image_path) {
            // 若外部是全 URL，直接返回
            if (is_string($this->image_path) && (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://'))) {
                return $this->image_path;
            }

            $path = str_replace('\\', '/', (string) $this->image_path);
            $path = ltrim($path, '/');

            // 兼容 Filament / 手动填入时可能出现的不同前缀：
            // - storage/ad-slots/x.jpg  => /storage/ad-slots/x.jpg
            // - public/ad-slots/x.jpg   => /storage/ad-slots/x.jpg
            // - ad-slots/x.jpg          => /storage/ad-slots/x.jpg
            if (str_starts_with($path, 'storage/')) {
                return '/' . $path;
            }

            if (str_starts_with($path, 'public/')) {
                $path = substr($path, strlen('public/'));
            }

            // 使用站点根相对路径，避免 APP_URL 与浏览器访问域名/协议不一致时图片 404
            return '/storage/' . $path;
        }

        return $this->image_url ?: null;
    }

    /**
     * 侧边栏是否应渲染（启用且至少有一种可展示内容）。
     */
    public function shouldDisplaySidebar(): bool
    {
        if (! $this->is_enabled) {
            return false;
        }

        if ($this->display_mode === 'html' && filled($this->html_content)) {
            return true;
        }

        return filled($this->resolvedImageUrl())
            || filled($this->title)
            || filled($this->body);
    }
}
