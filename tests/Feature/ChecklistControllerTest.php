<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ChecklistControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_requires_authentication()
    {
        $response = $this->get('/checklists');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_checklists()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/checklists');
        $response->assertStatus(200);
    }

    // Add more tests for create, store, show, edit, update, destroy, etc.
}
