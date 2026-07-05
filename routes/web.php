<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
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
    Route::post('/favorites/{car}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle')->middleware('throttle:20,1');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [MessageController::class, 'send'])->name('messages.send')->middleware('throttle:10,1');
    Route::post('/messages/car/{car}/user/{seller}', [MessageController::class, 'store'])->name('messages.create')->middleware('throttle:5,1');

    Route::get('/payments/{car}/plans', [PaymentController::class, 'showPlans'])->name('payments.plans');
    Route::post('/payments/{car}/checkout', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/{car}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/webhook.php';
