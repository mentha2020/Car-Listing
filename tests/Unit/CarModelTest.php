<?php

namespace Tests\Unit;

use App\Models\Car;
use App\Models\CarImage;
use App\Models\Conversation;
use App\Models\Favorite;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_car_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $car->user);
        $this->assertEquals($user->id, $car->user->id);
    }

    public function test_car_has_many_images(): void
    {
        $car = Car::factory()->create();
        CarImage::create(['car_id' => $car->id, 'path' => 'test.jpg']);
        CarImage::create(['car_id' => $car->id, 'path' => 'test2.jpg']);

        $this->assertCount(2, $car->images);
    }

    public function test_car_has_many_favorites(): void
    {
        $car = Car::factory()->create();
        Favorite::create(['user_id' => 1, 'car_id' => $car->id]);

        $this->assertCount(1, $car->favorites);
    }

    public function test_car_slug_is_auto_generated(): void
    {
        $car = Car::factory()->create(['make' => 'Toyota', 'model' => 'Corolla', 'year' => 2022]);

        $this->assertNotEmpty($car->slug);
        $this->assertStringContainsString('toyota', $car->slug);
    }

    public function test_car_primary_image_method(): void
    {
        $car = Car::factory()->create();
        CarImage::create(['car_id' => $car->id, 'path' => 'primary.jpg', 'is_primary' => true]);
        CarImage::create(['car_id' => $car->id, 'path' => 'secondary.jpg', 'is_primary' => false]);

        $this->assertEquals('primary.jpg', $car->primaryImage->path);
    }

    public function test_car_approved_scope(): void
    {
        Car::factory()->create(['status' => 'approved']);
        Car::factory()->create(['status' => 'pending']);
        Car::factory()->create(['status' => 'approved']);

        $this->assertCount(2, Car::approved()->get());
    }

    public function test_car_pending_scope(): void
    {
        Car::factory()->create(['status' => 'pending']);
        Car::factory()->create(['status' => 'approved']);
        Car::factory()->create(['status' => 'pending']);

        $this->assertCount(2, Car::pending()->get());
    }

    public function test_car_is_approved_method(): void
    {
        $car = Car::factory()->create(['status' => 'approved']);
        $this->assertTrue($car->isApproved());

        $pendingCar = Car::factory()->create(['status' => 'pending']);
        $this->assertFalse($pendingCar->isApproved());
    }

    public function test_car_route_key_name_is_slug(): void
    {
        $car = new Car();
        $this->assertEquals('slug', $car->getRouteKeyName());
    }
}
