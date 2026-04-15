<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\UserPendingApprovalNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_and_admins_are_notified(): void
    {
        Notification::fake();

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        $response = $this->post('/register', [
            'name' => 'Test User',
            'cpf' => 123456,
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('login', absolute: false));

        Notification::assertSentTo(
            $admin,
            UserPendingApprovalNotification::class,
            function ($notification, $channels) {
                return in_array('mail', $channels) && in_array('database', $channels);
            }
        );
    }
}
