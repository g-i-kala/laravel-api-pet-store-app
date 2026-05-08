<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/pets', [PetController::class, 'index'])->name('index');
Route::get('/pets/{id}', [PetController::class, 'show'])->name('show');
