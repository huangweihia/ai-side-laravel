<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use App\Models\User;
use App\Models\Comment;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectDetailTest extends TestCase
{
    // 不使用 RefreshDatabase，避免清空数据库
    // use RefreshDatabase;

    /** @test */
    public function project_detail_page_loads_successfully()
    {
        $project = Project::first();
        
        if (!$project) {
            $this->markTestSkipped('没有项目数据');
        }
        
        $response = $this->get(route('projects.show', $project));
        
        $response->assertStatus(200);
    }

    /** @test */
    public function project_detail_shows_monetization_analysis()
    {
        $project = Project::factory()->create([
            'difficulty' => 3,
            'income_range' => '5000-20000',
            'time_commitment' => '10-20h/week',
        ]);
        
        $response = $this->get(route('projects.show', $project));
        
        $response->assertStatus(200);
        $response->assertSee('变现分析');
        $response->assertSee('难度等级');
        $response->assertSee('月收入预估');
        $response->assertSee('时间投入');
    }

    /** @test */
    public function project_detail_shows_tech_stack()
    {
        $project = Project::factory()->create([
            'tech_stack' => ['Python', 'TensorFlow', 'FastAPI'],
        ]);
        
        $response = $this->get(route('projects.show', $project));
        
        $response->assertStatus(200);
        $response->assertSee('技术栈');
        $response->assertSee('Python');
        $response->assertSee('TensorFlow');
    }

    /** @test */
    public function project_detail_shows_resources()
    {
        $project = Project::factory()->create([
            'resources' => [
                ['title' => '官方文档', 'url' => 'https://example.com', 'type' => '文档'],
                ['title' => '视频教程', 'url' => 'https://youtube.com', 'type' => '视频'],
            ],
        ]);
        
        $response = $this->get(route('projects.show', $project));
        
        $response->assertStatus(200);
        $response->assertSee('教程资源');
        $response->assertSee('官方文档');
    }

    /** @test */
    public function guest_user_sees_login_prompt_for_comment()
    {
        $project = Project::factory()->create();
        
        $response = $this->get(route('projects.show', $project));
        
        $response->assertStatus(200);
        $response->assertSee('登录后才能发表评论');
        $response->assertSee('立即登录');
    }

    /** @test */
    public function authenticated_user_can_see_comment_form()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('projects.show', $project));
        
        $response->assertStatus(200);
        $response->assertSee('发表评论');
    }

    /** @test */
    public function project_detail_shows_favorite_button()
    {
        $project = Project::factory()->create();
        
        $response = $this->get(route('projects.show', $project));
        
        $response->assertStatus(200);
        $response->assertSee('收藏');
    }

    /** @test */
    public function project_detail_shows_related_projects()
    {
        $project1 = Project::factory()->create(['category_id' => 1]);
        $project2 = Project::factory()->create(['category_id' => 1]);
        $project3 = Project::factory()->create(['category_id' => 2]);
        
        $response = $this->get(route('projects.show', $project1));
        
        $response->assertStatus(200);
        $response->assertSee('相关项目');
        $response->assertSee($project2->name);
    }

    /** @test */
    public function favorite_toggle_works_for_authenticated_user()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        
        // 首次收藏
        $response = $this->actingAs($user)->post(route('projects.favorite', $project));
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'isFavorited' => true,
        ]);
        
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => Project::class,
            'favoritable_id' => $project->id,
        ]);
        
        // 取消收藏
        $response = $this->actingAs($user)->post(route('projects.favorite', $project));
        
        $response->assertJson([
            'success' => true,
            'isFavorited' => false,
        ]);
    }

    /** @test */
    public function guest_user_cannot_favorite()
    {
        $project = Project::factory()->create();
        
        $response = $this->post(route('projects.favorite', $project));
        
        $response->assertStatus(401);
    }
}
