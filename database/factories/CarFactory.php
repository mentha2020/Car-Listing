<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        $make = $this->faker->randomElement(['Toyota', 'Honda', 'Ford', 'BMW', 'Hyundai', 'Kia']);
        $model = $this->faker->randomElement(['Corolla', 'Civic', 'Ranger', '3 Series', 'Tucson', 'Sportage']);

        return [
            'user_id' => User::factory(),
            'make' => $make,
            'model' => $model,
            'year' => $this->faker->numberBetween(2015, 2025),
            'price' => $this->faker->numberBetween(5000, 80000),
            'mileage' => $this->faker->numberBetween(1000, 150000),
            'fuel_type' => $this->faker->randomElement(['petrol', 'diesel', 'electric', 'hybrid']),
            'transmission' => $this->faker->randomElement(['manual', 'automatic']),
            'city' => $this->faker->randomElement(['Colombo', 'Kandy', 'Galle', 'Negombo']),
            'description' => $this->faker->sentence(),
            'status' => 'pending',
            'is_featured' => false,
            'views_count' => 0,
        ];
    }
}
