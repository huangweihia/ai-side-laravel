<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\EmailSetting;
use App\Models\EmailTemplate;
use App\Mail\DigestEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class EmailSystemTest extends TestCase
{
    // 不使用 RefreshDatabase，避免清空数据库
    // use RefreshDatabase;

    /** @test */
    public function email_settings_page_loads_for_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/email-manager');
        $response->assertStatus(200);
    }

    /** @test */
    public function regular_user_cannot_access_email_settings()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->get('/admin/email-manager');
        $response->assertStatus(403);
    }

    /** @test */
    public function can_subscribe_to_newsletter()
    {
        $response = $this->post('/api/email/subscribe', [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('email_settings', [
            'email' => 'subscriber@example.com',
            'subscribed' => true,
        ]);
    }

    /** @test */
    public function can_unsubscribe_from_newsletter()
    {
        $setting = EmailSetting::create([
            'email' => 'unsubscribe@example.com',
            'subscribed' => true,
        ]);

        $response = $this->get(route('unsubscribe.show', ['token' => $setting->unsubscribe_token]));
        $response->assertStatus(200);
    }
}
