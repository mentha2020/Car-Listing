<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
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

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'mileage_low':
                $query->orderBy('mileage', 'asc');
                break;
            default:
                $query->latest();
        }

        $cars = $query->paginate(12)->withQueryString();

        $makes = Car::approved()->distinct()->pluck('make')->sort()->values();
        $cities = Car::approved()->distinct()->pluck('city')->sort()->values();

        return view('home', compact('cars', 'makes', 'cities'));
    }
}
