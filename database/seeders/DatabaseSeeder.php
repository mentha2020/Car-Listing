<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@carlisting.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Clients
        $clients = [];
        $clientData = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['name' => 'Mike Wilson', 'email' => 'mike@example.com'],
            ['name' => 'Sarah Brown', 'email' => 'sarah@example.com'],
            ['name' => 'David Lee', 'email' => 'david@example.com'],
        ];

        foreach ($clientData as $data) {
            $clients[] = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]);
        }

        // Cars
        $makes = ['Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes-Benz', 'Hyundai', 'Kia', 'Nissan', 'Chevrolet', 'Volkswagen'];
        $models = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Hilux', 'Land Cruiser'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'HR-V', 'City'],
            'Ford' => ['Ranger', 'Escape', 'Explorer', 'Mustang', 'F-150'],
            'BMW' => ['3 Series', '5 Series', 'X3', 'X5', '7 Series'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'GLC', 'GLE', 'S-Class'],
            'Hyundai' => ['Tucson', 'Santa Fe', 'i30', 'Kona', 'Accent'],
            'Kia' => ['Sportage', 'Sorento', 'Cerato', 'Seltos', 'Stonic'],
            'Nissan' => ['X-Trail', 'Qashqai', 'Patrol', 'Tiida', 'Juke'],
            'Chevrolet' => ['Equinox', 'Traverse', 'Malibu', 'Camaro', 'Tahoe'],
            'Volkswagen' => ['Golf', 'Tiguan', 'Passat', 'Polo', 'Touareg'],
        ];
        $cities = ['Colombo', 'Kandy', 'Galle', 'Negombo', 'Jaffna', 'Matara', 'Kurunegala', 'Ratnapura'];
        $statuses = ['approved', 'pending', 'rejected', 'draft'];
        $fuelTypes = ['petrol', 'diesel', 'electric', 'hybrid'];
        $transmissions = ['manual', 'automatic'];

        for ($i = 0; $i < 30; $i++) {
            $make = $makes[array_rand($makes)];
            $model = $models[$make][array_rand($models[$make])];
            $status = $statuses[array_rand($statuses)];
            $user = $clients[array_rand($clients)];

            Car::create([
                'user_id' => $user->id,
                'make' => $make,
                'model' => $model,
                'year' => rand(2015, 2025),
                'price' => rand(5000, 80000),
                'mileage' => rand(1000, 150000),
                'fuel_type' => $fuelTypes[array_rand($fuelTypes)],
                'transmission' => $transmissions[array_rand($transmissions)],
                'city' => $cities[array_rand($cities)],
                'description' => "Excellent condition {$make} {$model}. Well maintained with full service history. One owner from new. All documents available.",
                'status' => $status,
                'is_featured' => $status === 'approved' && rand(0, 1),
                'featured_at' => $status === 'approved' && rand(0, 1) ? now()->subDays(rand(1, 5)) : null,
                'featured_until' => $status === 'approved' && rand(0, 1) ? now()->addDays(rand(1, 25)) : null,
                'views_count' => rand(10, 500),
                'slug' => Str::slug("{$make}-{$model}-" . rand(2015, 2025) . "-" . uniqid()),
            ]);
        }
    }
}
