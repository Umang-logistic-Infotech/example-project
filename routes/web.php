<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::controller(UserController::class)->middleware(['auth', 'verified'])->group(function () {});
Route::get('/users', [UserController::class, 'getUsers'])->name('users');

Route::get('/users1', [UserController::class, 'getUsers1'])->name('getUsers1');

// Route::post('/edit/{id}', [UserController::class, 'edit'])->name('edit.row');
Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('delete.row');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
