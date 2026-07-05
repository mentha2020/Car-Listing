<?php

use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
    Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');
    Route::put('/cars/{car}/approve', [CarController::class, 'approve'])->name('cars.approve');
    Route::put('/cars/{car}/reject', [CarController::class, 'reject'])->name('cars.reject');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::put('/users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
});
