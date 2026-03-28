<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Project;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    // 不使用 RefreshDatabase，避免清空数据库
    // use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_fields()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test',
            'description' => 'test',
            'sort' => 1,
            'is_premium' => false,
        ]);

        $project = Project::create([
            'name' => 'Test Project',
            'full_name' => 'test/test-project',
            'description' => 'Test description',
            'url' => 'https://example.com',
            'language' => 'Python',
            'stars' => 100,
            'forks' => 20,
            'category_id' => $category->id,
            'difficulty' => 'medium',
            'is_featured' => true,
            'tags' => ['ai', 'automation'],
            'monetization_paths' => ['subscription', 'ads'],
            'tech_stack' => ['Python', 'FastAPI'],
            'resources' => [
                ['title' => 'Docs', 'url' => 'https://example.com/docs', 'type' => 'documentation']
            ],
        ]);

        $this->assertEquals('Test Project', $project->name);
        $this->assertEquals(['ai', 'automation'], $project->tags);
        $this->assertTrue($project->is_featured);
    }

    /** @test */
    public function it_can_be_favorited_by_user()
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'description' => 'test',
            'sort' => 1,
            'is_premium' => false,
        ]);

        $project = Project::create([
            'name' => 'Favorite Project',
            'full_name' => 'test/favorite',
            'description' => 'Test',
            'url' => 'https://example.com',
            'category_id' => $category->id,
            'difficulty' => 'medium',
            'is_featured' => false,
            'tags' => [],
            'monetization_paths' => [],
            'tech_stack' => [],
            'resources' => [],
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Project::class,
            'favoritable_id' => $project->id,
        ]);

        $this->assertTrue($project->favorites()->exists());
        $this->assertTrue($project->isFavoritedBy($user));
    }

    /** @test */
    public function it_has_difficulty_label_attribute()
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'description' => 'test',
            'sort' => 1,
            'is_premium' => false,
        ]);

        $project = Project::create([
            'name' => 'Difficulty Test',
            'full_name' => 'test/difficulty',
            'description' => 'Test',
            'url' => 'https://example.com',
            'category_id' => $category->id,
            'difficulty' => 'medium',
            'is_featured' => false,
            'tags' => [],
            'monetization_paths' => [],
            'tech_stack' => [],
            'resources' => [],
        ]);

        $this->assertEquals('中等', $project->difficulty_label);
    }

    /** @test */
    public function it_has_income_label_attribute()
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'description' => 'test',
            'sort' => 1,
            'is_premium' => false,
        ]);

        $project = Project::create([
            'name' => 'Income Test',
            'full_name' => 'test/income',
            'description' => 'Test',
            'url' => 'https://example.com',
            'category_id' => $category->id,
            'difficulty' => 'medium',
            'income_range' => '5000-20000',
            'is_featured' => false,
            'tags' => [],
            'monetization_paths' => [],
            'tech_stack' => [],
            'resources' => [],
        ]);

        $this->assertEquals('5000-20000 元/月', $project->income_label);
    }
}
