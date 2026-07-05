<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\User;
use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        return [
            'car_id' => Car::factory(),
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
        ];
    }
}
