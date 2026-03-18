<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'full_name',
        'description',
        'url',
        'language',
        'stars',
        'forks',
        'score',
        'tags',
        'monetization',
        'difficulty',
        'is_featured',
        'collected_at',
    ];

    protected $casts = [
        'stars' => 'integer',
        'forks' => 'integer',
        'score' => 'decimal:2',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'collected_at' => 'datetime',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query, $limit = 20)
    {
        return $query->orderBy('stars', 'desc')->limit($limit);
    }

    public function scopeLatest($query, $limit = 20)
    {
        return $query->orderBy('collected_at', 'desc')->limit($limit);
    }

    /**
     * 计算推荐分数
     */
    public static function calculateScore($stars, $growth = 0, $monetizationPotential = 'medium')
    {
        $starScore = min($stars / 10000, 10);
        $growthScore = min($growth, 10);
        $monetizationScores = ['low' => 3, 'medium' => 6, 'high' => 10];
        $monetizationScore = $monetizationScores[$monetizationPotential] ?? 6;

        return round($starScore * 0.3 + $growthScore * 0.3 + $monetizationScore * 0.4, 2);
    }

    /**
     * 获取难度标签
     */
    public function getDifficultyLabel(): string
    {
        return [
            'easy' => '简单',
            'medium' => '中等',
            'hard' => '困难',
        ][$this->difficulty] ?? '中等';
    }
}
