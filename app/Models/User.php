<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
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
     * Filament 后台访问权限
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    /**
     * 是否管理员
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * 是否 VIP
     */
    public function isVip(): bool
    {
        return $this->role === 'vip' || ($this->subscription_ends_at && $this->subscription_ends_at->isFuture());
    }

    /**
     * 作者的文章
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * 用户积分
     */
    public function points()
    {
        return $this->hasOne(UserPoint::class);
    }

    /**
     * 获得积分
     */
    public function addPoints(int $amount, string $type, string $description, array $meta = []): bool
    {
        if ($amount <= 0) return false;

        $point = UserPoint::firstOrCreate(['user_id' => $this->id], ['balance' => 0, 'total_earned' => 0, 'total_spent' => 0]);
        $point->increment('balance', $amount);
        $point->increment('total_earned', $amount);

        PointTransaction::create([
            'user_id' => $this->id,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'meta' => $meta,
        ]);

        return true;
    }

    /**
     * 消耗积分
     */
    public function spendPoints(int $amount, string $type, string $description, array $meta = []): bool
    {
        if ($amount <= 0) return false;

        $point = UserPoint::where('user_id', $this->id)->first();
        
        if (!$point || $point->balance < $amount) {
            return false;
        }

        $point->decrement('balance', $amount);
        $point->increment('total_spent', $amount);

        PointTransaction::create([
            'user_id' => $this->id,
            'amount' => -$amount,
            'type' => $type,
            'description' => $description,
            'meta' => $meta,
        ]);

        return true;
    }

    /**
     * 当前积分余额
     */
    public function getPointsBalanceAttribute(): int
    {
        return $this->points?->balance ?? 0;
    }

    /**
     * 是否已点赞
     */
    public function hasLiked($model): bool
    {
        return UserAction::hasActioned($this->id, 'like', $model);
    }

    /**
     * 是否已收藏
     */
    public function hasFavorited($model): bool
    {
        return UserAction::hasActioned($this->id, 'favorite', $model);
    }

    /**
     * 更新最后登录
     */
    public function updateLastLogin($ip = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ]);
    }
}