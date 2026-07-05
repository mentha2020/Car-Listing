<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannedMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_banned_user_is_logged_out_and_redirected(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'is_banned' => true,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Your account has been banned. Please contact support.');
    }

    public function test_banned_user_cannot_access_messages(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'is_banned' => true,
        ]);

        $response = $this->actingAs($user)->get(route('messages.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_banned_user_cannot_toggle_favorite(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'is_banned' => true,
        ]);

        $car = \App\Models\Car::factory()->create(['status' => 'approved']);
        $response = $this->actingAs($user)->post(route('favorites.toggle', $car));
        $response->assertRedirect(route('login'));
    }

    public function test_unbanned_user_can_access_routes(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'is_banned' => false,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);
    }
}
