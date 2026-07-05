<?php

use App\Models\Car;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Car::where('is_featured', true)
        ->where('featured_until', '<', now())
        ->update([
            'is_featured' => false,
            'featured_at' => null,
            'featured_until' => null,
        ]);
})->daily();
