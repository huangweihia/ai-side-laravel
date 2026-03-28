<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use App\Services\ArticleFetchService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleFetchTest extends TestCase
{
    // 不使用 RefreshDatabase，避免清空用户数据
    // use RefreshDatabase;

    /** @test */
    public function it_fetches_exactly_5_unique_articles()
    {
        $service = new ArticleFetchService();
        $count = $service->fetchAll();
        
        // 验证采集了 5 篇
        $this->assertEquals(5, $count, '应该采集 exactly 5 篇文章');
        
        // 验证数据库有 5 篇
        $this->assertDatabaseCount('articles', 5);
    }

    /** @test */
    public function it_fetches_new_articles_on_second_run()
    {
        $service = new ArticleFetchService();
        
        // 第 1 次采集
        $firstCount = $service->fetchAll();
        $this->assertEquals(5, $firstCount);
        
        // 第 2 次采集
        $secondCount = $service->fetchAll();
        $this->assertEquals(5, $secondCount, '第 2 次也应该采集 5 篇新文章');
        
        // 验证数据库有 10 篇（5+5）
        $this->assertDatabaseCount('articles', 10);
    }

    /** @test */
    public function it_does_not_duplicate_articles()
    {
        $service = new ArticleFetchService();
        
        // 采集两次
        $service->fetchAll();
        $service->fetchAll();
        
        // 验证没有重复的 source_url
        $articles = Article::all();
        $urls = $articles->pluck('source_url');
        
        $this->assertEquals($urls->unique()->count(), $urls->count(), 'source_url 不应该重复');
    }

    /** @test */
    public function each_article_has_required_fields()
    {
        $service = new ArticleFetchService();
        $service->fetchAll();
        
        $article = Article::first();
        
        $this->assertNotNull($article->title);
        $this->assertNotNull($article->slug);
        $this->assertNotNull($article->content);
        $this->assertNotNull($article->source_url);
        $this->assertNotNull($article->category_id);
        $this->assertTrue($article->is_published);
    }

    /** @test */
    public function article_slug_is_unique()
    {
        $service = new ArticleFetchService();
        $service->fetchAll();
        
        // 验证 slug 没有重复
        $articles = Article::all();
        $slugs = $articles->pluck('slug');
        
        $this->assertEquals($slugs->unique()->count(), $slugs->count(), 'slug 不应该重复');
    }
}
