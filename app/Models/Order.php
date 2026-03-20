<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'user_id',
        'product_type',
        'product_id',
        'amount',
        'status',
        'payment_method',
        'payment_time',
        'paid_amount',
        'remark',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_time' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_no)) {
                $order->order_no = 'ORD-' . strtoupper(Str::random(12));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function getStatusLabel(): string
    {
        return [
            'pending' => '瀵板懏鏁禒?,
            'paid' => '瀹稿弶鏁禒?,
            'cancelled' => '瀹告彃褰囧☉?,
            'refunded' => '瀹告煡鈧偓濞?,
        ][$this->status] ?? $this->status;
    }
}
