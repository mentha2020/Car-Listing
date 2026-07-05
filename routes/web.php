<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicCarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/cars/{car}', [PublicCarController::class, 'show'])->name('cars.show');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'client'])->group(function () {
    Route::get('/my-cars', [CarController::class, 'index'])->name('my-cars.index');
    Route::get('/my-cars/create', [CarController::class, 'create'])->name('my-cars.create');
    Route::post('/my-cars', [CarController::class, 'store'])->name('my-cars.store');
    Route::get('/my-cars/{car}/edit', [CarController::class, 'edit'])->name('my-cars.edit');
    Route::put('/my-cars/{car}', [CarController::class, 'update'])->name('my-cars.update');
    Route::delete('/my-cars/{car}', [CarController::class, 'destroy'])->name('my-cars.destroy');
    Route::delete('/my-cars/{car}/images/{image}', [CarController::class, 'removeImage'])->name('my-cars.remove-image');
    Route::post('/my-cars/{car}/images/{image}/primary', [CarController::class, 'setPrimary'])->name('my-cars.set-primary');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{car}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
