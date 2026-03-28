<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ContentSubmission;
use App\Models\JobListing;
use App\Models\KnowledgeBase;
use App\Models\Project;

class SubmissionPublisher
{
    public static function publish(ContentSubmission $submission): void
    {
        if ($submission->status !== 'approved') {
            return;
        }

        if ($submission->published_model_type && $submission->published_model_id) {
            return;
        }

        $target = match ($submission->type) {
            'document' => self::publishAsArticle($submission),
            'project' => self::publishAsProject($submission),
            'job' => self::publishAsJob($submission),
            'knowledge' => self::publishAsKnowledge($submission),
            default => null,
        };

        if ($target) {
            $submission->published_model_type = get_class($target);
            $submission->published_model_id = $target->id;
            $submission->published_at = $submission->published_at ?: now();
            $submission->save();
        }
    }

    private static function publishAsArticle(ContentSubmission $submission): Article
    {
        return Article::create([
            'title' => $submission->title,
            'summary' => $submission->summary,
            'content' => $submission->content,
            'author_id' => $submission->user_id,
            'is_premium' => (bool) $submission->is_paid,
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    private static function publishAsProject(ContentSubmission $submission): Project
    {
        return Project::create([
            'name' => $submission->title,
            'full_name' => $submission->title,
            'description' => $submission->summary ?: mb_substr(strip_tags($submission->content), 0, 600),
            'url' => 'https://submission.local/project/' . $submission->id . '-' . uniqid(),
            'monetization' => $submission->is_paid ? ('付费内容：' . $submission->price . ' ' . $submission->currency) : '免费内容',
            'difficulty' => 'medium',
            'stars' => 0,
            'forks' => 0,
            'score' => 0,
        ]);
    }

    private static function publishAsJob(ContentSubmission $submission): JobListing
    {
        return JobListing::create([
            'title' => $submission->title,
            'company' => '社区投稿',
            'salary' => $submission->is_paid ? ($submission->price . ' ' . $submission->currency) : null,
            'location' => '远程',
            'description' => $submission->content,
            'url' => null,
            'source' => 'submission',
            'published_at' => now(),
            'is_sent' => false,
        ]);
    }

    private static function publishAsKnowledge(ContentSubmission $submission): KnowledgeBase
    {
        return KnowledgeBase::create([
            'user_id' => $submission->user_id,
            'title' => $submission->title,
            'description' => $submission->summary,
            'category' => 'general',
            'is_public' => true,
            'is_vip_only' => (bool) $submission->is_paid,
        ]);
    }
}
