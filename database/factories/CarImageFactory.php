<?php

namespace Database\Factories;

use App\Models\CarImage;
use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarImageFactory extends Factory
{
    protected $model = CarImage::class;

    public function definition(): array
    {
        return [
            'car_id' => Car::factory(),
            'path' => 'images/' . $this->faker->uuid() . '.jpg',
            'is_primary' => false,
            'sort_order' => 0,
        ];
    }
}
