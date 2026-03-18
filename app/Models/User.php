<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
        'subscription_ends_at',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'subscription_ends_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * 是否是管理员
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * 是否是会员
     */
    public function isVip(): bool
    {
        return $this->role === 'vip' || ($this->subscription_ends_at && $this->subscription_ends_at->isFuture());
    }

    /**
     * 获取活跃订阅
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active');
    }

    /**
     * 获取所有订阅
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * 获取所有订单
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * 获取作者的文章
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * 更新最后登录信息
     */
    public function updateLastLogin($ip = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ]);
    }
}
