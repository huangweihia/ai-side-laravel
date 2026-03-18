<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\GitHub\GitHubService;
use Mockery;

class GitHubServiceTest extends TestCase
{
    /** @test */
    public function it_can_search_popular_projects()
    {
        $service = new GitHubService();
        
        // 这个测试会真实调用 GitHub API，如果没有 token 可能会限流
        // 实际开发中应该用 Mock
        $projects = $service->searchPopularProjects('AI agent', 5);
        
        $this->assertIsArray($projects);
    }

    /** @test */
    public function it_calculates_score_based_on_stars()
    {
        $service = new GitHubService();
        
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('calculateScore');
        $method->setAccessible(true);
        
        $data = [
            'stargazers_count' => 50000,
            'homepage' => 'https://example.com',
            'has_discussions' => true,
        ];
        
        $score = $method->invoke($service, $data);
        
        $this->assertIsFloat($score);
        $this->assertGreaterThan(5, $score);
        $this->assertLessThanOrEqual(10, $score);
    }

    /** @test */
    public function it_assesses_monetization_potential_correctly()
    {
        $service = new GitHubService();
        
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('assessMonetizationPotential');
        $method->setAccessible(true);
        
        // High potential
        $highData = ['stargazers_count' => 60000, 'homepage' => 'https://example.com'];
        $this->assertEquals(10, $method->invoke($service, $highData));
        
        // Medium potential
        $mediumData = ['stargazers_count' => 15000];
        $this->assertEquals(6, $method->invoke($service, $mediumData));
        
        // Low potential
        $lowData = ['stargazers_count' => 100];
        $this->assertEquals(3, $method->invoke($service, $lowData));
    }

    /** @test */
    public function it_analyzes_monetization_methods()
    {
        $service = new GitHubService();
        
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('analyzeMonetization');
        $method->setAccessible(true);
        
        $data = [
            'description' => 'A platform for building AI agents with SaaS capabilities',
        ];
        
        $methods = $method->invoke($service, $data);
        
        $this->assertStringContainsString('SaaS 订阅', $methods);
        $this->assertStringContainsString('企业定制', $methods);
    }

    /** @test */
    public function it_assesses_difficulty_based_on_description()
    {
        $service = new GitHubService();
        
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('assessDifficulty');
        $method->setAccessible(true);
        
        $easyData = ['description' => 'No-code visual workflow builder', 'stargazers_count' => 1000];
        $this->assertEquals('easy', $method->invoke($service, $easyData));
        
        $hardData = ['description' => 'Advanced AI framework', 'stargazers_count' => 60000];
        $this->assertEquals('hard', $method->invoke($service, $hardData));
        
        $mediumData = ['description' => 'Regular project', 'stargazers_count' => 5000];
        $this->assertEquals('medium', $method->invoke($service, $mediumData));
    }

    /** @test */
    public function it_extracts_tags_from_description()
    {
        $service = new GitHubService();
        
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('extractTags');
        $method->setAccessible(true);
        
        $data = [
            'language' => 'Python',
            'description' => 'Build AI agents and LLM workflows with RAG capabilities',
        ];
        
        $tags = $method->invoke($service, $data);
        
        $this->assertContains('Python', $tags);
        $this->assertContains('Agent', $tags);
        $this->assertContains('LLM', $tags);
        $this->assertContains('RAG', $tags);
        $this->assertContains('Workflow', $tags);
    }
}
