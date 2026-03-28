<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
            return Storage::disk('public')->url($this->image_path);
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
