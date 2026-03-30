<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemFeedback extends Model
{
    protected $table = 'problem_feedback';

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image_path',
        'status',
        'review_note',
        'reviewed_by',
        'reviewed_at',
        'rewarded_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'rewarded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function imageUrl(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return '/storage/' . ltrim(str_replace('\\', '/', $this->image_path), '/');
    }
}

