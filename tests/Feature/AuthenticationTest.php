<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_the_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_a_farmer_can_register_and_reach_the_dashboard(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Amina Farmer',
            'email' => 'amina@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'amina@example.com']);
    }

    public function test_a_farmer_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create([
            'email' => 'farmer@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        $logoutResponse = $this->post(route('logout'));

        $logoutResponse->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invalid_login_is_rejected(): void
    {
        User::factory()->create([
            'email' => 'farmer@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'farmer@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
