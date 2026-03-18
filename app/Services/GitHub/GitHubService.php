<?php

namespace App\Services\GitHub;

use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubService
{
    protected string $baseUrl = 'https://api.github.com';
    protected ?string $token;

    public function __construct()
    {
        $this->token = config('services.github.token') ?? env('GITHUB_TOKEN');
    }

    /**
     * 搜索热门 AI 项目
     */
    public function searchPopularProjects(string $query = 'AI agent', int $perPage = 20): array
    {
        $url = "{$this->baseUrl}/search/repositories";
        
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github.v3+json',
            'Authorization' => $this->token ? "token {$this->token}" : null,
        ])->get($url, [
            'q' => $query,
            'sort' => 'stars',
            'order' => 'desc',
            'per_page' => $perPage,
        ]);

        if ($response->successful()) {
            return $response->json('items', []);
        }

        Log::error('GitHub API request failed', [
            'query' => $query,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return [];
    }

    /**
     * 获取 trending 项目
     */
    public function getTrending(string $since = 'daily'): array
    {
        // GitHub API 没有直接的 trending 接口，需要搜索最近创建的高 star 项目
        $date = now()->subDay()->format('Y-m-d');
        
        return $this->searchPopularProjects(
            "stars:>100 created:>{$date}",
            30
        );
    }

    /**
     * 保存项目到数据库
     */
    public function saveProject(array $data): ?Project
    {
        try {
            $project = Project::updateOrCreate(
                ['url' => $data['html_url']],
                [
                    'name' => $data['name'],
                    'full_name' => $data['full_name'] ?? null,
                    'description' => $data['description'] ?? null,
                    'language' => $data['language'] ?? null,
                    'stars' => $data['stargazers_count'] ?? 0,
                    'forks' => $data['forks_count'] ?? 0,
                    'score' => $this->calculateScore($data),
                    'tags' => $this->extractTags($data),
                    'monetization' => $this->analyzeMonetization($data),
                    'difficulty' => $this->assessDifficulty($data),
                    'is_featured' => ($data['stargazers_count'] ?? 0) > 10000,
                    'collected_at' => now(),
                ]
            );

            Log::info("Project saved: {$project->name}", [
                'stars' => $project->stars,
                'score' => $project->score,
            ]);

            return $project;
        } catch (\Exception $e) {
            Log::error('Failed to save project', [
                'name' => $data['name'] ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * 批量收集项目
     */
    public function collectProjects(array $keywords = []): int
    {
        $keywords = $keywords ?: [
            'AI agent',
            'LLM',
            'RAG',
            'langchain',
            'AI workflow',
            'chatbot',
            'generative AI',
        ];

        $collected = 0;

        foreach ($keywords as $keyword) {
            Log::info("Collecting projects for: {$keyword}");

            $projects = $this->searchPopularProjects($keyword);

            foreach ($projects as $projectData) {
                if ($this->saveProject($projectData)) {
                    $collected++;
                }

                // 避免 API 限流
                usleep(500000); // 500ms
            }

            // 每个关键词之间等待 2 秒
            sleep(2);
        }

        Log::info("Collection completed", ['total' => $collected]);

        return $collected;
    }

    /**
     * 计算推荐分数
     */
    protected function calculateScore(array $data): float
    {
        $stars = $data['stargazers_count'] ?? 0;
        $hasWebsite = !empty($data['homepage']);
        $hasDiscussions = $data['has_discussions'] ?? false;

        $starScore = min($stars / 10000, 10);
        $growthScore = 5; // 默认中等增长
        $monetizationScore = $this->assessMonetizationPotential($data);

        return round($starScore * 0.3 + $growthScore * 0.3 + $monetizationScore * 0.4, 2);
    }

    /**
     * 评估变现潜力
     */
    protected function assessMonetizationPotential(array $data): float
    {
        $stars = $data['stargazers_count'] ?? 0;
        $hasWebsite = !empty($data['homepage']);
        $hasDiscussions = $data['has_discussions'] ?? false;

        if ($stars > 50000 || ($hasWebsite && $stars > 10000)) {
            return 10; // high
        } elseif ($stars > 10000 || $hasDiscussions) {
            return 6; // medium
        }

        return 3; // low
    }

    /**
     * 分析变现方式
     */
    protected function analyzeMonetization(array $data): ?string
    {
        $description = strtolower($data['description'] ?? '');
        $methods = [];

        if (str_contains($description, 'platform') || str_contains($description, 'saas')) {
            $methods[] = 'SaaS 订阅';
        }
        if (str_contains($description, 'tool') || str_contains($description, 'library')) {
            $methods[] = '企业定制';
        }
        if (str_contains($description, 'template') || str_contains($description, 'boilerplate')) {
            $methods[] = '模板销售';
        }
        if (str_contains($description, 'course') || str_contains($description, 'tutorial')) {
            $methods[] = '培训课程';
        }

        if (empty($methods)) {
            $methods[] = '技术咨询';
            $methods[] = '开源赞助';
        }

        return implode(', ', $methods);
    }

    /**
     * 评估难度
     */
    protected function assessDifficulty(array $data): string
    {
        $description = strtolower($data['description'] ?? '');
        $stars = $data['stargazers_count'] ?? 0;

        if (str_contains($description, 'no-code') || str_contains($description, 'visual')) {
            return 'easy';
        } elseif ($stars > 50000) {
            return 'hard';
        }

        return 'medium';
    }

    /**
     * 提取标签
     */
    protected function extractTags(array $data): array
    {
        $tags = [];

        if (!empty($data['language'])) {
            $tags[] = $data['language'];
        }

        $description = strtolower($data['description'] ?? '');
        
        $tagMappings = [
            'agent' => 'Agent',
            'llm' => 'LLM',
            'language model' => 'LLM',
            'rag' => 'RAG',
            'workflow' => 'Workflow',
            'chatbot' => 'Chatbot',
            'api' => 'API',
            'sdk' => 'SDK',
        ];

        foreach ($tagMappings as $keyword => $tag) {
            if (str_contains($description, $keyword)) {
                $tags[] = $tag;
            }
        }

        return array_unique($tags);
    }
}
