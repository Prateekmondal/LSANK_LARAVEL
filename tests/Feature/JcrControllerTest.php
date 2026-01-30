<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class JcrControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_jcr_index_requires_authentication()
    {
        $response = $this->get('/jcr');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_jcrs()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/jcr');
        $response->assertStatus(200);
    }

    // Add more tests for create, store, show, edit, update, destroy, preview, submit, etc.
}
