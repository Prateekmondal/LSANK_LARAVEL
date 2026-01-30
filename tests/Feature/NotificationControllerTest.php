<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_index_requires_authentication()
    {
        $response = $this->get('/notifications');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_notifications()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/notifications');
        $response->assertStatus(200);
    }

    // Add more tests for markAsRead, markAllAsRead, destroy, etc.
}
