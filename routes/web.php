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

    Route::get('/users', [UserController::class, 'index'])->name('users.index'); // List all users
    Route::post('addUser', [UserController::class, 'addUser'])->name('addUser');
    Route::post('getUserDetails', [UserController::class, 'getUserDetails'])->name('getUserDetails');
    Route::post('updateUserDetails', [UserController::class, 'updateUserDetails'])->name('updateUserDetails');
        // Route::post('deleteClass', [AcademicController::class, 'deleteClass'])->name('deleteClass');
    


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
