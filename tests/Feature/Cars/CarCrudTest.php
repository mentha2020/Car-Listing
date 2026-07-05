<?php

namespace Tests\Feature\Cars;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CarCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = User::factory()->create(['role' => 'client']);
    }

    public function test_client_can_view_my_cars_page(): void
    {
        $response = $this->actingAs($this->client)->get(route('my-cars.index'));
        $response->assertStatus(200);
    }

    public function test_client_can_view_create_car_form(): void
    {
        $response = $this->actingAs($this->client)->get(route('my-cars.create'));
        $response->assertStatus(200);
    }

    public function test_client_can_store_a_car(): void
    {
        $response = $this->actingAs($this->client)->post(route('my-cars.store'), [
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2022,
            'price' => 25000,
            'mileage' => 15000,
            'fuel_type' => 'petrol',
            'transmission' => 'automatic',
            'city' => 'Colombo',
            'description' => 'Well maintained car',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cars', [
            'user_id' => $this->client->id,
            'make' => 'Toyota',
            'model' => 'Corolla',
            'status' => 'pending',
        ]);
    }

    public function test_guest_cannot_store_a_car(): void
    {
        $response = $this->post(route('my-cars.store'), [
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2022,
            'price' => 25000,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_client_can_edit_own_car(): void
    {
        $car = Car::factory()->create(['user_id' => $this->client->id]);

        $response = $this->actingAs($this->client)->get(route('my-cars.edit', $car));
        $response->assertStatus(200);
    }

    public function test_client_cannot_edit_other_users_car(): void
    {
        $otherUser = User::factory()->create(['role' => 'client']);
        $car = Car::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->client)->get(route('my-cars.edit', $car));
        $response->assertStatus(403);
    }

    public function test_client_can_update_own_car(): void
    {
        $car = Car::factory()->create(['user_id' => $this->client->id, 'status' => 'approved']);

        $response = $this->actingAs($this->client)->put(route('my-cars.update', $car), [
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2023,
            'price' => 30000,
            'mileage' => 10000,
            'fuel_type' => 'petrol',
            'transmission' => 'manual',
            'city' => 'Kandy',
            'description' => 'Updated description',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'make' => 'Honda',
            'model' => 'Civic',
        ]);
    }

    public function test_client_can_delete_own_car(): void
    {
        $car = Car::factory()->create(['user_id' => $this->client->id]);

        $response = $this->actingAs($this->client)->delete(route('my-cars.destroy', $car));
        $response->assertRedirect(route('my-cars.index'));
        $this->assertDatabaseMissing('cars', ['id' => $car->id]);
    }

    public function test_client_cannot_delete_other_users_car(): void
    {
        $otherUser = User::factory()->create(['role' => 'client']);
        $car = Car::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->client)->delete(route('my-cars.destroy', $car));
        $response->assertStatus(403);
        $this->assertDatabaseHas('cars', ['id' => $car->id]);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $car = Car::factory()->create();
        $response = $this->get(route('my-cars.index'));
        $response->assertRedirect('/login');
    }
}
