<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'status',
        'plan',
        'starts_at',
        'ends_at',
        'cancelled_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * 用户关联
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 作用域：有效订阅
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('ends_at', '>', now());
    }

    /**
     * 是否有效
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at?->isFuture();
    }

    /**
     * 获取计划名称
     */
    public function getPlanName(): string
    {
        return [
            'monthly' => '月度会员',
            'yearly' => '年度会员',
            'lifetime' => '终身会员',
        ][$this->plan] ?? '未知计划';
    }
}
