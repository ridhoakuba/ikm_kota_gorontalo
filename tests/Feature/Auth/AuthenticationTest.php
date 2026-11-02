<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    public function test_switching_accounts_logs_out_previous_user(): void
    {
        $user1 = User::factory()->create(['email' => 'user1@test.com']);
        $user2 = User::factory()->create(['email' => 'user2@test.com']);

        // Login sebagai user1
        $this->actingAs($user1);
        $this->assertAuthenticatedAs($user1);

        // Login sebagai user2 tanpa logout manual sebelumnya
        $response = $this->post('/login', [
            'email' => $user2->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user2);
        $response->assertRedirect('/dashboard');
    }

    public function test_kelurahan_users_can_authenticate_using_kelurahan_id(): void
    {
        $kelurahan = \App\Models\Kelurahan::create([
            'nama_kelurahan' => 'Dumbo Raya',
        ]);

        $userKelurahan = User::factory()->create([
            'role' => 'kelurahan',
            'kelurahan_id' => $kelurahan->id,
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'kelurahan_id' => $kelurahan->id,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($userKelurahan);
        $response->assertRedirect('/dashboard');
    }
}
