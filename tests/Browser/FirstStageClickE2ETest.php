<?php

namespace Tests\Browser;

use App\Models\Article;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FirstStageClickE2ETest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * 为了避免 Dusk/Laravel 测试基类初始化不完整导致 Facade root 未设置，
     * createApplication 实现需要与项目已有 `tests/CreatesApplication.php` 一致，
     * 即执行 `$app->make(Kernel::class)->bootstrap()`。
     */
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function test_homepage_click_project_records_history_and_shows_in_history_page(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'AI 工具',
            'slug' => 'ai-tools',
            'description' => 'test',
            'sort' => 1,
        ]);

        $project = Project::create([
            'name' => 'History Project Dusk',
            'full_name' => 'test/history-project',
            'description' => 'test project for history',
            'url' => 'https://example.com/history-project',
            'language' => 'PHP',
            'stars' => 9999,
            'forks' => 10,
            'score' => 1.0,
            'difficulty' => 'medium',
            'is_featured' => true,
            'category_id' => $category->id,
        ]);

        Article::create([
            'category_id' => $category->id,
            'title' => 'History Article Dusk',
            'slug' => 'history-article-dusk',
            'summary' => 'test article for history',
            'content' => 'hello world',
            'is_published' => true,
            'published_at' => now(),
            'view_count' => 1,
            'like_count' => 0,
            'is_premium' => false,
            'is_vip' => false,
        ]);

        $this->browse(function (Browser $browser) use ($user, $project): void {
            $browser->loginAs($user)
                ->visit('/')
                ->waitForText($project->name, 5)
                ->clickLink($project->name)
                ->waitForText('项目介绍', 5)
                ->visit('/history?type=projects')
                ->waitForText($project->name, 5);
        });

        $this->assertTrue(
            DB::table('view_histories')->where([
                'user_id' => $user->id,
                'viewable_type' => Project::class,
                'viewable_id' => $project->id,
            ])->exists(),
            'expected view_histories record not found for project'
        );
    }

    public function test_homepage_click_article_records_history_and_shows_in_history_page(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => '学习资源',
            'slug' => 'learning',
            'description' => 'test',
            'sort' => 1,
        ]);

        Project::create([
            'name' => 'History Project Dusk 2',
            'full_name' => 'test/history-project-2',
            'description' => 'test project for homepage layout',
            'url' => 'https://example.com/history-project-2',
            'language' => 'PHP',
            'stars' => 1,
            'forks' => 1,
            'score' => 1.0,
            'difficulty' => 'medium',
            'is_featured' => false,
            'category_id' => $category->id,
        ]);

        $article = Article::create([
            'category_id' => $category->id,
            'title' => 'History Article Dusk 2',
            'slug' => 'history-article-dusk-2',
            'summary' => 'test article for history',
            'content' => 'hello world',
            'is_published' => true,
            'published_at' => now(),
            'view_count' => 100,
            'like_count' => 0,
            'is_premium' => false,
            'is_vip' => false,
        ]);

        $this->browse(function (Browser $browser) use ($user, $article): void {
            $browser->loginAs($user)
                ->visit('/')
                ->waitForText($article->title, 5)
                ->clickLink($article->title)
                ->waitForText($article->title, 5)
                ->visit('/history?type=articles')
                ->waitForText($article->title, 5);
        });

        $this->assertTrue(
            DB::table('view_histories')->where([
                'user_id' => $user->id,
                'viewable_type' => Article::class,
                'viewable_id' => $article->id,
            ])->exists(),
            'expected view_histories record not found for article'
        );
    }

    public function test_favorites_index_renders_for_projects_and_articles(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'AI 工具',
            'slug' => 'ai-tools-2',
            'description' => 'test',
            'sort' => 1,
        ]);

        $project = Project::create([
            'name' => 'Favorite Project Dusk',
            'full_name' => 'test/favorite-project',
            'description' => 'test favorite project',
            'url' => 'https://example.com/favorite-project',
            'language' => 'PHP',
            'stars' => 5,
            'forks' => 1,
            'score' => 1.0,
            'difficulty' => 'medium',
            'is_featured' => false,
            'category_id' => $category->id,
        ]);

        $article = Article::create([
            'category_id' => $category->id,
            'title' => 'Favorite Article Dusk',
            'slug' => 'favorite-article-dusk',
            'summary' => 'test favorite article',
            'content' => 'hello world',
            'is_published' => true,
            'published_at' => now(),
            'view_count' => 1,
            'like_count' => 0,
            'is_premium' => false,
            'is_vip' => false,
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

        $this->browse(function (Browser $browser) use ($user, $project, $article): void {
            $browser->loginAs($user)
                ->visit('/favorites?type=projects')
                ->waitForText($project->name, 5)
                ->visit('/favorites?type=articles')
                ->waitForText($article->title, 5);
        });
    }
}

