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
     * 閹兼粎鍌ㄩ悜顓㈡， AI 妞ゅ湱娲?     */
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
     * 閼惧嘲褰?trending 妞ゅ湱娲?     */
    public function getTrending(string $since = 'daily'): array
    {
        // GitHub API 濞屸剝婀侀惄瀛樺复閻?trending 閹恒儱褰涢敍宀勬付鐟曚焦鎮崇槐銏℃付鏉╂垵鍨卞铏规畱妤?star 妞ゅ湱娲?        $date = now()->subDay()->format('Y-m-d');
        
        return $this->searchPopularProjects(
            "stars:>100 created:>{$date}",
            30
        );
    }

    /**
     * 娣囨繂鐡ㄦい鍦窗閸掔増鏆熼幑顔肩氨
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
     * 閹靛綊鍣洪弨鍫曟肠妞ゅ湱娲?     */
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

                // 闁灝鍘?API 闂勬劖绁?                usleep(500000); // 500ms
            }

            // 濮ｅ繋閲滈崗鎶芥暛鐠囧秳绠ｉ梻瀵哥搼瀵?2 缁?            sleep(2);
        }

        Log::info("Collection completed", ['total' => $collected]);

        return $collected;
    }

    /**
     * 鐠侊紕鐣婚幒銊ㄥ礃閸掑棙鏆?     */
    protected function calculateScore(array $data): float
    {
        $stars = $data['stargazers_count'] ?? 0;
        $hasWebsite = !empty($data['homepage']);
        $hasDiscussions = $data['has_discussions'] ?? false;

        $starScore = min($stars / 10000, 10);
        $growthScore = 5; // 姒涙顓绘稉顓犵搼婢х偤鏆?        $monetizationScore = $this->assessMonetizationPotential($data);

        return round($starScore * 0.3 + $growthScore * 0.3 + $monetizationScore * 0.4, 2);
    }

    /**
     * 鐠囧嫪鍙婇崣妯煎箛濞兼粌濮?     */
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
     * 閸掑棙鐎介崣妯煎箛閺傜懓绱?     */
    protected function analyzeMonetization(array $data): ?string
    {
        $description = strtolower($data['description'] ?? '');
        $methods = [];

        if (str_contains($description, 'platform') || str_contains($description, 'saas')) {
            $methods[] = 'SaaS 鐠併垽妲?;
        }
        if (str_contains($description, 'tool') || str_contains($description, 'library')) {
            $methods[] = '娴间椒绗熺€规艾鍩?;
        }
        if (str_contains($description, 'template') || str_contains($description, 'boilerplate')) {
            $methods[] = '濡剝婢橀柨鈧崬?;
        }
        if (str_contains($description, 'course') || str_contains($description, 'tutorial')) {
            $methods[] = '閸╃顔勭拠鍓р柤';
        }

        if (empty($methods)) {
            $methods[] = '閹垛偓閺堫垰鎸╃拠?;
            $methods[] = '瀵偓濠ф劘绂愰崝?;
        }

        return implode(', ', $methods);
    }

    /**
     * 鐠囧嫪鍙婇梾鎯у
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
     * 閹绘劕褰囬弽鍥╊劮
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
