<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\EmailSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailSystemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_email_subscription()
    {
        $subscription = EmailSubscription::create([
            'email' => 'test@example.com',
            'subscribed_to_daily' => true,
        ]);

        $this->assertDatabaseHas('email_subscriptions', [
            'email' => 'test@example.com',
            'subscribed_to_daily' => true,
        ]);
    }

    /** @test */
    public function it_can_toggle_subscription_preference()
    {
        $subscription = EmailSubscription::create([
            'email' => 'test@example.com',
            'subscribed_to_daily' => true,
            'subscribed_to_weekly' => true,
        ]);

        $subscription->update(['subscribed_to_daily' => false]);

        $this->assertFalse($subscription->fresh()->subscribed_to_daily);
        $this->assertTrue($subscription->fresh()->subscribed_to_weekly);
    }

    /** @test */
    public function it_can_unsubscribe_all()
    {
        $subscription = EmailSubscription::create([
            'email' => 'test@example.com',
            'subscribed_to_daily' => true,
        ]);

        $subscription->unsubscribeAll();

        $this->assertFalse($subscription->fresh()->isSubscribedToDaily());
        $this->assertNotNull($subscription->fresh()->unsubscribed_at);
    }

    /** @test */
    public function registration_creates_email_subscription()
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('email_subscriptions', [
            'email' => 'test@example.com',
        ]);
    }
}
