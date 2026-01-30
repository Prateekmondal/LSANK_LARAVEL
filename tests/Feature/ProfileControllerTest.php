<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_index_requires_authentication()
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_profile()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
    }

    // Add more tests for edit, update, update_avatar, destroy, etc.
}
