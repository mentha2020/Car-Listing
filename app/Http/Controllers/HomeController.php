<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        // If any filters are applied, show filtered results
        $hasFilters = $request->has(['search', 'make', 'model', 'year_from', 'year_to', 'price_from', 'price_to', 'fuel_type', 'transmission', 'mileage_to', 'city']);

        if ($hasFilters) {
            return $this->filteredResults($request);
        }

        // Homepage: show featured + latest
        $featuredCars = Car::approved()
            ->with(['primaryImage', 'user'])
            ->where('is_featured', true)
            ->where('featured_until', '>', now())
            ->take(8)
            ->get();

        $latestCars = Car::approved()
            ->with(['primaryImage', 'user'])
            ->latest()
            ->take(8)
            ->get();

        $stats = [
            'total_cars' => Car::approved()->count(),
            'total_users' => \App\Models\User::where('role', 'client')->count(),
            'total_cities' => Car::approved()->distinct('city')->count('city'),
        ];

        $makes = Car::approved()->distinct()->pluck('make')->sort()->values();
        $cities = Car::approved()->distinct()->pluck('city')->sort()->values();

        return view('home', compact('featuredCars', 'latestCars', 'stats', 'makes', 'cities'));
    }

    private function filteredResults(Request $request): View
    {
        $query = Car::approved()->with(['primaryImage', 'user'])
            ->when($request->make, fn ($q, $v) => $q->byMake($v))
            ->when($request->model, fn ($q, $v) => $q->byModel($v))
            ->when($request->year_from || $request->year_to, fn ($q) => $q->byYearRange($request->year_from, $request->year_to))
            ->when($request->price_from || $request->price_to, fn ($q) => $q->byPriceRange($request->price_from, $request->price_to))
            ->when($request->fuel_type, fn ($q, $v) => $q->where('fuel_type', $v))
            ->when($request->transmission, fn ($q, $v) => $q->where('transmission', $v))
            ->when($request->mileage_to, fn ($q, $v) => $q->byMileage($v))
            ->when($request->city, fn ($q, $v) => $q->byCity($v))
            ->when($request->search, fn ($q, $v) => $q->where(function ($q2) use ($v) {
                $q2->where('make', 'like', "%{$v}%")
                   ->orWhere('model', 'like', "%{$v}%")
                   ->orWhere('description', 'like', "%{$v}%");
            }));

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'mileage_low' => $query->orderBy('mileage', 'asc'),
            default => $query->latest(),
        };

        $cars = $query->paginate(12)->withQueryString();
        $makes = Car::approved()->distinct()->pluck('make')->sort()->values();
        $cities = Car::approved()->distinct()->pluck('city')->sort()->values();

        return view('home-results', compact('cars', 'makes', 'cities'));
    }
}
