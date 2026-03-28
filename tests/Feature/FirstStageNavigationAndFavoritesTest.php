<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FirstStageNavigationAndFavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function project_detail_records_view_history_and_history_page_renders_it(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => '历史分类',
            'slug' => 'history-category',
            'description' => 'test',
            'sort' => 1,
            'is_premium' => false,
        ]);

        $project = Project::create([
            'name' => 'Feature History Project',
            'full_name' => 'test/feature-history-project',
            'description' => 'test project for history',
            'url' => 'https://example.com/feature-history-project',
            'language' => 'PHP',
            'stars' => 10,
            'forks' => 1,
            'score' => 1.0,
            'difficulty' => 'medium',
            'is_featured' => false,
            'tags' => [],
            'monetization' => null,
            'income_range' => null,
            'time_commitment' => null,
            'monetization_paths' => [],
            'tech_stack' => [],
            'resources' => [],
            'category_id' => $category->id,
        ]);

        $this->actingAs($user)->get(route('projects.show', $project))
            ->assertStatus(200);

        $this->assertDatabaseHas('view_histories', [
            'user_id' => $user->id,
            'viewable_type' => Project::class,
            'viewable_id' => $project->id,
        ]);

        $this->actingAs($user)->get('/history?type=projects')
            ->assertStatus(200)
            ->assertSee($project->name);
    }

    /** @test */
    public function article_detail_records_view_history_and_history_page_renders_it(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => '历史分类 2',
            'slug' => 'history-category-2',
            'description' => 'test',
            'sort' => 1,
            'is_premium' => false,
        ]);

        $article = Article::create([
            'category_id' => $category->id,
            'title' => 'Feature History Article',
            'slug' => 'feature-history-article',
            'summary' => 'test article for history',
            'content' => 'hello world ' . str_repeat('lorem ipsum ', 400),
            'is_premium' => false,
            'is_vip' => false,
            'is_published' => true,
            'published_at' => now(),
            'view_count' => 1,
            'like_count' => 0,
            'favorite_count' => 0,
            'source_url' => null,
            'meta_keywords' => null,
            'meta_description' => null,
        ]);

        $this->actingAs($user)->get(route('articles.show', $article))
            ->assertStatus(200);

        $this->assertDatabaseHas('view_histories', [
            'user_id' => $user->id,
            'viewable_type' => Article::class,
            'viewable_id' => $article->id,
        ]);

        $this->actingAs($user)->get('/history?type=articles')
            ->assertStatus(200)
            ->assertSee($article->title);
    }

    /** @test */
    public function favorites_index_renders_projects_and_articles_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => '收藏分类',
            'slug' => 'favorites-category',
            'description' => 'test',
            'sort' => 1,
            'is_premium' => false,
        ]);

        $project = Project::create([
            'name' => 'Feature Favorite Project',
            'full_name' => 'test/feature-favorite-project',
            'description' => 'test favorite project',
            'url' => 'https://example.com/feature-favorite-project',
            'language' => 'PHP',
            'stars' => 5,
            'forks' => 1,
            'score' => 1.0,
            'difficulty' => 'medium',
            'is_featured' => false,
            'tags' => [],
            'monetization' => null,
            'income_range' => null,
            'time_commitment' => null,
            'monetization_paths' => [],
            'tech_stack' => [],
            'resources' => [],
            'category_id' => $category->id,
        ]);

        $article = Article::create([
            'category_id' => $category->id,
            'title' => 'Feature Favorite Article',
            'slug' => 'feature-favorite-article',
            'summary' => 'test favorite article',
            'content' => 'hello world ' . str_repeat('lorem ipsum ', 200),
            'is_premium' => false,
            'is_vip' => false,
            'is_published' => true,
            'published_at' => now(),
            'view_count' => 1,
            'like_count' => 0,
            'favorite_count' => 0,
            'source_url' => null,
            'meta_keywords' => null,
            'meta_description' => null,
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Project::class,
            'favoritable_id' => $project->id,
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Article::class,
            'favoritable_id' => $article->id,
        ]);

        $this->actingAs($user)->get('/favorites?type=projects')
            ->assertStatus(200)
            ->assertSee($project->name);

        $this->actingAs($user)->get('/favorites?type=articles')
            ->assertStatus(200)
            ->assertSee($article->title);
    }
}

