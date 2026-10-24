<?php
use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;

// Katalog UTS hanya menerima GET dan HEAD. Semua pengelolaan data lewat seeder.
Route::get('/', [CarController::class, 'index'])->name('home');
Route::get('/mobil', [CarController::class, 'index'])->name('cars.index');
Route::get('/mobil/{car}', [CarController::class, 'show'])->whereNumber('car')->name('cars.show');
