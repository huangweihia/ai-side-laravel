<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company',
        'salary',
        'location',
        'description',
        'url',
        'source',
        'published_at',
        'is_sent',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    /**
     * 作用域：未发送的职位
     */
    public function scopeNotSent($query)
    {
        return $query->where('is_sent', false);
    }

    /**
     * 作用域：今日职位
     */
    public function scopeToday($query)
    {
        return $query->whereDate('published_at', today());
    }
}
