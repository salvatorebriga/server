<?php

use App\Http\Controllers\Api\ApartmentsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Apartment;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

Route::get('/apartments', [ApartmentsController::class, 'index'])->name('apartments.index');
Route::get('/apartments/{id}', [ApartmentsController::class, 'show'])->name('apartments.show');
