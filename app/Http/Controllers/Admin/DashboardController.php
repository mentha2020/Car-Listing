<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $stats = [
            'total_cars' => Car::count(),
            'pending_cars' => Car::pending()->count(),
            'approved_cars' => Car::approved()->count(),
            'rejected_cars' => Car::where('status', 'rejected')->count(),
            'total_users' => User::count(),
            'new_users_month' => User::where('created_at', '>=', now()->subMonth())->count(),
            'total_revenue' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
            'total_favorites' => \App\Models\Favorite::count(),
        ];

        $weeklyCars = Car::selectRaw('date(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subWeeks(12))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $carsByStatus = [
            Car::where('status', 'pending')->count(),
            Car::where('status', 'approved')->count(),
            Car::where('status', 'rejected')->count(),
            Car::where('status', 'draft')->count(),
        ];

        $topCities = Car::select('city', \DB::raw('count(*) as count'))
            ->groupBy('city')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        $recentCars = Car::with('user', 'primaryImage')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'weeklyCars', 'carsByStatus', 'topCities', 'recentCars'));
    }
}
