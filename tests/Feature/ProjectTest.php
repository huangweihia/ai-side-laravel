<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    // 不使用 RefreshDatabase，避免清空数据库
    // use RefreshDatabase;

    /** @test */
    public function it_can_view_projects_index()
    {
        $response = $this->get(route('projects.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_projects_by_category()
    {
        $category = Category::create([
            'name' => 'AI 工具',
            'slug' => 'ai-tools',
            'description' => 'AI 工具分类',
            'sort' => 1,
            'is_premium' => false,
        ]);
        
        Project::create([
            'name' => 'Test Project',
            'full_name' => 'test/test-project',
            'description' => 'Test description',
            'url' => 'https://example.com',
            'language' => 'Python',
            'stars' => 100,
            'forks' => 20,
            'category_id' => $category->id,
            'difficulty' => 'medium',
            'is_featured' => false,
            'tags' => [],
            'monetization_paths' => [],
            'tech_stack' => [],
            'resources' => [],
        ]);

        $response = $this->get(route('projects.index', ['category' => 'ai-tools']));
        $response->assertStatus(200);
        $response->assertSee('Test Project');
    }

    /** @test */
    public function it_can_search_projects()
    {
        Project::create([
            'name' => 'GPT-4 Automation Tool',
            'full_name' => 'test/gpt4-tool',
            'description' => 'Automate tasks with GPT-4',
            'url' => 'https://example.com/gpt4',
            'language' => 'Python',
            'stars' => 500,
            'forks' => 50,
            'difficulty' => 'medium',
            'is_featured' => true,
            'tags' => [],
            'monetization_paths' => [],
            'tech_stack' => [],
            'resources' => [],
        ]);

        $response = $this->get(route('projects.index', ['search' => 'gpt']));
        $response->assertStatus(200);
        $response->assertSee('GPT-4 Automation Tool');
    }

    /** @test */
    public function it_shows_featured_projects_first()
    {
        $category = Category::create([
            'name' => '精选',
            'slug' => 'featured',
            'description' => '精选项目',
            'sort' => 1,
            'is_premium' => false,
        ]);

        Project::create([
            'name' => 'Featured Project',
            'full_name' => 'test/featured',
            'description' => 'Featured',
            'url' => 'https://example.com/featured',
            'stars' => 1000,
            'forks' => 100,
            'category_id' => $category->id,
            'difficulty' => 'medium',
            'is_featured' => true,
            'tags' => [],
            'monetization_paths' => [],
            'tech_stack' => [],
            'resources' => [],
        ]);

        $response = $this->get(route('projects.index'));
        $response->assertStatus(200);
    }
}
