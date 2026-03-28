<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePageTest extends TestCase
{
    // 不使用 RefreshDatabase，避免清空数据库
    // use RefreshDatabase;

    /** @test */
    public function home_page_loads_successfully()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function home_page_contains_value_proposition()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('每天 10 分钟，发现 AI 副业机会');
    }

    /** @test */
    public function home_page_has_cta_buttons()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('免费注册');
        $response->assertSee('开通 VIP');
    }

    /** @test */
    public function home_page_shows_sections()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('今日精选项目');
        $response->assertSee('今日精选文章');
        $response->assertSee('热门分类');
    }
}
