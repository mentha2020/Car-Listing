<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'car_id' => Car::factory(),
            'amount' => $this->faker->randomFloat(2, 5, 20),
            'type' => 'featured',
            'status' => 'pending',
        ];
    }
}
