<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Notifications\CarApprovedNotification;
use App\Notifications\CarRejectedNotification;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with('user', 'primaryImage');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $cars = $query->latest()->paginate(15)->withQueryString();

        return view('admin.cars.index', compact('cars'));
    }

    public function show(Car $car)
    {
        $car->load(['user', 'images']);

        return view('admin.cars.show', compact('car'));
    }

    public function approve(Car $car)
    {
        $car->approve();
        $car->user->notify(new CarApprovedNotification($car));

        return redirect()->route('admin.cars.index', ['status' => 'pending'])
            ->with('success', "Car listing approved: {$car->year} {$car->make} {$car->model}");
    }

    public function reject(Request $request, Car $car)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $car->reject($request->rejection_reason);
        $car->user->notify(new CarRejectedNotification($car));

        return redirect()->route('admin.cars.index', ['status' => 'pending'])
            ->with('success', "Car listing rejected: {$car->year} {$car->make} {$car->model}");
    }
}
