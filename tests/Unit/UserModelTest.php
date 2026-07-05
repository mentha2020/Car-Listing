<?php

namespace Tests\Unit;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($admin->isAdmin());

        $client = User::factory()->create(['role' => 'client']);
        $this->assertFalse($client->isAdmin());
    }

    public function test_user_is_client(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $this->assertTrue($client->isClient());

        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertFalse($admin->isClient());
    }

    public function test_user_has_many_cars(): void
    {
        $user = User::factory()->create();
        Car::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->cars);
    }

    public function test_user_has_many_favorites(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create();
        \App\Models\Favorite::create(['user_id' => $user->id, 'car_id' => $car->id]);

        $this->assertCount(1, $user->favorites);
    }

    public function test_user_has_sent_conversations(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $car = Car::factory()->create();
        \App\Models\Conversation::create([
            'car_id' => $car->id,
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
        ]);

        $this->assertCount(1, $sender->sentConversations);
    }

    public function test_user_has_received_conversations(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $car = Car::factory()->create();
        \App\Models\Conversation::create([
            'car_id' => $car->id,
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
        ]);

        $this->assertCount(1, $receiver->receivedConversations);
    }
}
