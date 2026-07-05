<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $favorites = auth()->user()
            ->favorites()
            ->with(['car.primaryImage', 'car.user'])
            ->latest()
            ->paginate(12);

        return view('favorites', compact('favorites'));
    }

    public function toggle(Car $car): RedirectResponse
    {
        $favorite = auth()->user()->favorites()->where('car_id', $car->id)->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Removed from favorites.');
        }

        auth()->user()->favorites()->create(['car_id' => $car->id]);
        return back()->with('success', 'Added to favorites!');
    }
}
