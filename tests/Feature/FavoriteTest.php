<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    private User $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = User::factory()->create(['role' => 'client']);
    }

    public function test_client_can_view_favorites(): void
    {
        $response = $this->actingAs($this->client)->get(route('favorites.index'));
        $response->assertStatus(200);
    }

    public function test_client_can_toggle_favorite(): void
    {
        $car = Car::factory()->create(['status' => 'approved']);

        $response = $this->actingAs($this->client)->post(route('favorites.toggle', $car));
        $response->assertRedirect();
        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->client->id,
            'car_id' => $car->id,
        ]);
    }

    public function test_client_can_unfavorite_a_car(): void
    {
        $car = Car::factory()->create(['status' => 'approved']);
        Favorite::create(['user_id' => $this->client->id, 'car_id' => $car->id]);

        $response = $this->actingAs($this->client)->post(route('favorites.toggle', $car));
        $response->assertRedirect();
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->client->id,
            'car_id' => $car->id,
        ]);
    }

    public function test_guest_cannot_toggle_favorite(): void
    {
        $car = Car::factory()->create(['status' => 'approved']);
        $response = $this->post(route('favorites.toggle', $car));
        $response->assertRedirect('/login');
    }
}
