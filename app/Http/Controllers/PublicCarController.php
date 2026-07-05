<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicCarController extends Controller
{
    public function show(Car $car): View
    {
        abort_unless($car->isApproved(), 404);

        $car->load(['images', 'user']);
        $car->incrementViews();

        $relatedCars = Car::approved()
            ->where('id', '!=', $car->id)
            ->where(function ($q) use ($car) {
                $q->where('make', $car->make)
                  ->orWhere('city', $car->city);
            })
            ->with('primaryImage')
            ->take(4)
            ->get();

        $isFavorited = auth()->check()
            ? auth()->user()->favorites()->where('car_id', $car->id)->exists()
            : false;

        return view('cars.show', compact('car', 'relatedCars', 'isFavorited'));
    }
}
