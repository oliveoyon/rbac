<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboards', [DashboardController::class, 'index'])->name('dashboard.index');

Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('district-management', [DashboardController::class, 'districts'])->name('dashboard.districts');
    Route::post('districts', [DashboardController::class, 'districtAdd'])->name('districts.add');  // Add District
    Route::put('districts/{district}', [DashboardController::class, 'districtUpdate'])->name('districts.update');  // Update District
    Route::delete('districts/{district}', [DashboardController::class, 'districtDelete'])->name('districts.delete');  // Delete District

    Route::get('pngo-management', [DashboardController::class, 'pngos'])->name('dashboard.pngos');
    Route::post('pngos', [DashboardController::class, 'pngoAdd'])->name('pngos.add');  // Add District
    Route::put('pngos/{pngo}', [DashboardController::class, 'pngoUpdate'])->name('pngos.update');  // Update District
    Route::delete('pngos/{pngo}', [DashboardController::class, 'pngoDelete'])->name('pngos.delete');  // Delete District

    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index'); // List all users
Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create'); // Show form to create a new user
Route::post('/admin/users', [UserController::class, 'store'])->name('users.store'); // Store a new user
Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // Show form to edit a user
Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update'); // Update a user
Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Delete a user
    


});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
