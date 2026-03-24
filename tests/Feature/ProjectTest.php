<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_view_projects_index()
    {
        $response = $this->get(route('projects.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_projects_by_category()
    {
        $category = Category::create(['name' => 'AI 工具', 'slug' => 'ai-tools']);
        
        Project::create([
            'name' => 'test-project',
            'full_name' => 'Test Project',
            'description' => 'Test',
            'url' => 'https://example.com',
            'category_id' => $category->id,
        ]);

        $response = $this->get(route('projects.index', ['category' => 'ai-tools']));
        $response->assertStatus(200);
        $response->assertSee('Test Project');
    }

    /** @test */
    public function it_can_search_projects()
    {
        Project::create([
            'name' => 'chatgpt-test',
            'full_name' => 'ChatGPT Test',
            'description' => 'A test project',
            'url' => 'https://example.com',
        ]);

        $response = $this->get(route('projects.index', ['search' => 'chatgpt']));
        $response->assertStatus(200);
        $response->assertSee('ChatGPT Test');
    }

    /** @test */
    public function it_shows_empty_state_when_no_projects()
    {
        $response = $this->get(route('projects.index'));
        $response->assertStatus(200);
        $response->assertSee('暂无项目');
    }
}
