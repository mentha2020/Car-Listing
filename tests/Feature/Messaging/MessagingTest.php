<?php

namespace Tests\Feature\Messaging;

use App\Models\Car;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingTest extends TestCase
{
    use RefreshDatabase;

    private User $sender;
    private User $receiver;
    private Car $car;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sender = User::factory()->create(['role' => 'client']);
        $this->receiver = User::factory()->create(['role' => 'client']);
        $this->car = Car::factory()->create(['user_id' => $this->receiver->id, 'status' => 'approved']);
    }

    public function test_client_can_view_conversations(): void
    {
        $response = $this->actingAs($this->sender)->get(route('messages.index'));
        $response->assertStatus(200);
    }

    public function test_client_can_start_a_conversation(): void
    {
        $response = $this->actingAs($this->sender)->post(route('messages.create', [$this->car, $this->receiver]), [
            'body' => 'Hello, is this car still available?',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('conversations', [
            'car_id' => $this->car->id,
            'sender_id' => $this->sender->id,
            'receiver_id' => $this->receiver->id,
        ]);
        $this->assertDatabaseHas('messages', [
            'body' => 'Hello, is this car still available?',
        ]);
    }

    public function test_client_can_reply_to_conversation(): void
    {
        $conversation = Conversation::create([
            'car_id' => $this->car->id,
            'sender_id' => $this->sender->id,
            'receiver_id' => $this->receiver->id,
        ]);

        $response = $this->actingAs($this->receiver)->post(route('messages.send', $conversation), [
            'body' => 'Yes, it is still available!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'body' => 'Yes, it is still available!',
        ]);
    }

    public function test_client_can_view_conversation(): void
    {
        $conversation = Conversation::create([
            'car_id' => $this->car->id,
            'sender_id' => $this->sender->id,
            'receiver_id' => $this->receiver->id,
        ]);

        $response = $this->actingAs($this->sender)->get(route('messages.show', $conversation));
        $response->assertStatus(200);
    }

    public function test_non_participant_cannot_view_conversation(): void
    {
        $conversation = Conversation::create([
            'car_id' => $this->car->id,
            'sender_id' => $this->sender->id,
            'receiver_id' => $this->receiver->id,
        ]);

        $outsider = User::factory()->create(['role' => 'client']);
        $response = $this->actingAs($outsider)->get(route('messages.show', $conversation));
        $response->assertStatus(403);
    }
}
