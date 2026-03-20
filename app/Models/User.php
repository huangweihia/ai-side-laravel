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
     * 閺勵垰鎯侀弰顖滎吀閻炲棗鎲?     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * 閺勵垰鎯侀弰顖欑窗閸?     */
    public function isVip(): bool
    {
        return $this->role === 'vip' || ($this->subscription_ends_at && $this->subscription_ends_at->isFuture());
    }

    /**
     * 閼惧嘲褰囧ú鏄忕┈鐠併垽妲?     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active');
    }

    /**
     * 閼惧嘲褰囬幍鈧張澶庮吂闂?     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * 閼惧嘲褰囬幍鈧張澶庮吂閸?     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * 閼惧嘲褰囨担婊嗏偓鍛畱閺傚洨鐝?     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * 閺囧瓨鏌婇張鈧崥搴ｆ瑜版洑淇婇幁?     */
    public function updateLastLogin($ip = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ]);
    }
}
