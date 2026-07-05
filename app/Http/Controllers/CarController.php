<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\CarImage;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CarController extends Controller
{
    public function __construct(
        private ImageService $imageService
    ) {}

    public function index(Request $request): View
    {
        $cars = $request->user()
            ->cars()
            ->with('primaryImage')
            ->latest()
            ->paginate(12);

        return view('my-cars.index', compact('cars'));
    }

    public function create(): View
    {
        $makes = [
            'Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes-Benz',
            'Hyundai', 'Kia', 'Nissan', 'Chevrolet', 'Volkswagen',
            'Audi', 'Lexus', 'Mazda', 'Subaru', 'Mitsubishi',
            'Suzuki', 'Peugeot', 'Renault', 'Fiat', 'Jeep',
        ];

        return view('my-cars.create', compact('makes'));
    }

    public function store(StoreCarRequest $request): RedirectResponse
    {
        $car = $request->user()->cars()->create([
            ...$request->validated(),
            'status' => 'pending',
        ]);

        if ($request->has('images')) {
            $this->imageService->uploadCarImages($car, $request->file('images'));
        }

        return redirect()->route('my-cars.index')
            ->with('success', 'Your listing has been submitted for review!');
    }

    public function edit(Car $car): View
    {
        abort_unless($car->user_id === auth()->id(), 403);

        $makes = [
            'Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes-Benz',
            'Hyundai', 'Kia', 'Nissan', 'Chevrolet', 'Volkswagen',
            'Audi', 'Lexus', 'Mazda', 'Subaru', 'Mitsubishi',
            'Suzuki', 'Peugeot', 'Renault', 'Fiat', 'Jeep',
        ];

        $car->load('images');

        return view('my-cars.edit', compact('car', 'makes'));
    }

    public function update(UpdateCarRequest $request, Car $car): RedirectResponse
    {
        $car->update($request->validated());

        if ($request->has('images')) {
            $this->imageService->uploadCarImages($car, $request->file('images'));
        }

        return redirect()->route('my-cars.index')
            ->with('success', 'Listing updated! It has been resubmitted for review.');
    }

    public function destroy(Car $car): RedirectResponse
    {
        abort_unless($car->user_id === auth()->id(), 403);

        $this->imageService->deleteCarImages($car);
        $car->delete();

        return redirect()->route('my-cars.index')
            ->with('success', 'Listing deleted successfully.');
    }

    public function removeImage(Car $car, CarImage $image): RedirectResponse
    {
        abort_unless($car->user_id === auth()->id(), 403);
        abort_unless($image->car_id === $car->id, 404);

        $this->imageService->deleteImage($image);

        return back()->with('success', 'Image removed.');
    }

    public function setPrimary(Car $car, CarImage $image): RedirectResponse
    {
        abort_unless($car->user_id === auth()->id(), 403);
        abort_unless($image->car_id === $car->id, 404);

        $this->imageService->setPrimary($car, $image);

        return back()->with('success', 'Primary image updated.');
    }
}
