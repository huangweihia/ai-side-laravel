<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_view_articles_index()
    {
        $response = $this->get(route('articles.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_articles_by_category()
    {
        $category = Category::create(['name' => '教程', 'slug' => 'learning']);
        
        Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'summary' => 'Test',
            'content' => 'Test content',
            'category_id' => $category->id,
            'is_published' => true,
            'author_id' => User::factory()->create()->id,
        ]);

        $response = $this->get(route('articles.index', ['category' => 'learning']));
        $response->assertStatus(200);
        $response->assertSee('Test Article');
    }

    /** @test */
    public function it_can_search_articles()
    {
        $user = User::factory()->create();
        
        Article::create([
            'title' => 'GPT-4 Tutorial',
            'slug' => 'gpt4-tutorial',
            'summary' => 'Learn GPT-4',
            'content' => 'Content',
            'is_published' => true,
            'author_id' => $user->id,
        ]);

        $response = $this->get(route('articles.index', ['search' => 'gpt']));
        $response->assertStatus(200);
        $response->assertSee('GPT-4 Tutorial');
    }

    /** @test */
    public function it_only_shows_published_articles()
    {
        $user = User::factory()->create();
        
        Article::create([
            'title' => 'Published Article',
            'slug' => 'published',
            'content' => 'Content',
            'is_published' => true,
            'author_id' => $user->id,
        ]);

        Article::create([
            'title' => 'Draft Article',
            'slug' => 'draft',
            'content' => 'Content',
            'is_published' => false,
            'author_id' => $user->id,
        ]);

        $response = $this->get(route('articles.index'));
        $response->assertStatus(200);
        $response->assertSee('Published Article');
        $response->assertDontSee('Draft Article');
    }
}
