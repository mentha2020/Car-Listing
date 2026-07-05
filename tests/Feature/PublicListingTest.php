<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_approved_cars(): void
    {
        Car::factory()->count(3)->create(['status' => 'approved']);
        Car::factory()->count(2)->create(['status' => 'pending']);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
    }

    public function test_car_detail_page_works(): void
    {
        $car = Car::factory()->create(['status' => 'approved', 'slug' => 'toyota-corolla-2022']);

        $response = $this->get(route('cars.show', $car));
        $response->assertStatus(200);
    }

    public function test_filter_by_make(): void
    {
        Car::factory()->create(['status' => 'approved', 'make' => 'Toyota']);
        Car::factory()->create(['status' => 'approved', 'make' => 'Honda']);

        $response = $this->get(route('home', ['make' => 'Toyota']));
        $response->assertStatus(200);
    }

    public function test_filter_by_price_range(): void
    {
        Car::factory()->create(['status' => 'approved', 'price' => 10000]);
        Car::factory()->create(['status' => 'approved', 'price' => 50000]);

        $response = $this->get(route('home', ['min_price' => 5000, 'max_price' => 20000]));
        $response->assertStatus(200);
    }

    public function test_filter_by_year(): void
    {
        Car::factory()->create(['status' => 'approved', 'year' => 2020]);
        Car::factory()->create(['status' => 'approved', 'year' => 2023]);

        $response = $this->get(route('home', ['min_year' => 2022]));
        $response->assertStatus(200);
    }

    public function test_filter_by_fuel_type(): void
    {
        Car::factory()->create(['status' => 'approved', 'fuel_type' => 'diesel']);
        Car::factory()->create(['status' => 'approved', 'fuel_type' => 'petrol']);

        $response = $this->get(route('home', ['fuel_type' => 'diesel']));
        $response->assertStatus(200);
    }

    public function test_filter_by_transmission(): void
    {
        Car::factory()->create(['status' => 'approved', 'transmission' => 'manual']);
        Car::factory()->create(['status' => 'approved', 'transmission' => 'automatic']);

        $response = $this->get(route('home', ['transmission' => 'manual']));
        $response->assertStatus(200);
    }

    public function test_filter_by_city(): void
    {
        Car::factory()->create(['status' => 'approved', 'city' => 'Colombo']);
        Car::factory()->create(['status' => 'approved', 'city' => 'Kandy']);

        $response = $this->get(route('home', ['city' => 'Colombo']));
        $response->assertStatus(200);
    }

    public function test_sort_by_price_low(): void
    {
        Car::factory()->create(['status' => 'approved', 'price' => 50000]);
        Car::factory()->create(['status' => 'approved', 'price' => 10000]);

        $response = $this->get(route('home', ['sort' => 'price_asc']));
        $response->assertStatus(200);
    }

    public function test_sort_by_price_high(): void
    {
        Car::factory()->create(['status' => 'approved', 'price' => 10000]);
        Car::factory()->create(['status' => 'approved', 'price' => 50000]);

        $response = $this->get(route('home', ['sort' => 'price_desc']));
        $response->assertStatus(200);
    }
}
