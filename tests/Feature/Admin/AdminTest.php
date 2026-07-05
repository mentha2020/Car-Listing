<?php

namespace Tests\Feature\Admin;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->client = User::factory()->create(['role' => 'client']);
    }

    public function test_admin_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_client_cannot_view_admin_dashboard(): void
    {
        $response = $this->actingAs($this->client)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_view_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_pending_cars(): void
    {
        Car::factory()->count(3)->create(['status' => 'pending']);

        $response = $this->actingAs($this->admin)->get(route('admin.cars.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_approve_a_car(): void
    {
        $car = Car::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->admin)->put(route('admin.cars.approve', $car));
        $response->assertRedirect();
        $this->assertDatabaseHas('cars', ['id' => $car->id, 'status' => 'approved']);
    }

    public function test_admin_can_reject_a_car(): void
    {
        $car = Car::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->admin)->put(route('admin.cars.reject', $car), [
            'rejection_reason' => 'Does not meet quality standards',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'status' => 'rejected',
            'rejection_reason' => 'Does not meet quality standards',
        ]);
    }

    public function test_client_cannot_approve_a_car(): void
    {
        $car = Car::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->client)->put(route('admin.cars.approve', $car));
        $response->assertStatus(403);
        $this->assertDatabaseHas('cars', ['id' => $car->id, 'status' => 'pending']);
    }

    public function test_admin_can_view_users(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_ban_a_user(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.ban', $this->client));
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $this->client->id, 'is_banned' => true]);
    }

    public function test_admin_can_unban_a_user(): void
    {
        $this->client->update(['is_banned' => true]);

        $response = $this->actingAs($this->admin)->put(route('admin.users.unban', $this->client));
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $this->client->id, 'is_banned' => false]);
    }
}
