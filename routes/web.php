<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/pets', [PetController::class, 'index'])->name('pets.index');
Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
Route::get('/pets/{id}', [PetController::class, 'show'])->name('pets.show');
Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
