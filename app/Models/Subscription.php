<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan',
        'amount',
        'status',
        'started_at',
        'expires_at',
        'payment_id',
        'payment_method',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && 
               (!$this->expires_at || $this->expires_at->isFuture());
    }

    public function getPlanLabel(): string
    {
        return [
            'monthly' => '月度会员',
            'yearly' => '年度会员',
            'lifetime' => '终身会员',
        ][$this->plan] ?? $this->plan;
    }
}
